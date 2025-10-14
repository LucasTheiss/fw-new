<?php
namespace src\Repository;

use config\Database;
use src\Model\Solicitacao;
use DateTime, DateTimeZone;

class SolicitacaoRepository {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM solicitacao WHERE idsolicitacao = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) return null;
        
        $solicitacao = new Solicitacao();
        $data = $result->fetch_assoc();
        foreach ($data as $key => $value) {
            if (property_exists($solicitacao, $key)) {
                $solicitacao->$key = $value;
            }
        }
        return $solicitacao;
    }

    public function findAll() {
        $sql = "SELECT * FROM solicitacao ORDER BY status ASC, idsolicitacao DESC";
        $result = $this->conn->query($sql);
        $requests = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $requests[] = $row; // Mantendo como array para compatibilidade com JS existente
            }
        }
        return $requests;
    }

    public function approve($idsolicitacao) {
        $solicitacao = $this->findById($idsolicitacao);
        if (!$solicitacao) return ['success' => false, 'message' => 'Solicitação não encontrada.'];

        // 1. Validar se CNPJ ou CPF já existem
        $stmt_check_cnpj = $this->conn->prepare("SELECT idtransportadora FROM transportadora WHERE cnpj = ?");
        $stmt_check_cnpj->bind_param("s", $solicitacao->cnpj);
        $stmt_check_cnpj->execute();
        if ($stmt_check_cnpj->get_result()->num_rows > 0) {
            return ['success' => false, 'message' => 'Este CNPJ já está registrado.'];
        }

        $stmt_check_cpf = $this->conn->prepare("SELECT idusuario FROM usuario WHERE cpf = ?");
        $stmt_check_cpf->bind_param("s", $solicitacao->cpf);
        $stmt_check_cpf->execute();
        if ($stmt_check_cpf->get_result()->num_rows > 0) {
            return ['success' => false, 'message' => 'Este CPF já está registrado.'];
        }

        // 2. Iniciar transação
        $this->conn->begin_transaction();

        try {
            // 3. Inserir Usuário
            $stmt_user = $this->conn->prepare("INSERT INTO usuario(email, nome, senha, telefone, cpf, gerente) VALUES(?, ?, ?, ?, ?, 1)");
            $stmt_user->bind_param("sssss", $solicitacao->emailUsuario, $solicitacao->nomeUsuario, $solicitacao->senha, $solicitacao->telefoneUsuario, $solicitacao->cpf);
            $stmt_user->execute();
            $idusuario = $this->conn->insert_id;

            // 4. Inserir Transportadora
            $now = (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('Y-m-d H:i:s');
            $stmt_trans = $this->conn->prepare("INSERT INTO transportadora(nome, endereco, cidade, estado, cep, cnpj, dataCriacao) VALUES(?, ?, ?, ?, ?, ?, ?)");
            $stmt_trans->bind_param("sssssss", $solicitacao->nomeTransportadora, $solicitacao->endereco, $solicitacao->cidade, $solicitacao->estado, $solicitacao->cep, $solicitacao->cnpj, $now);
            $stmt_trans->execute();
            $idtransportadora = $this->conn->insert_id;

            // 5. Lincar os dois
            $stmt_link = $this->conn->prepare("INSERT INTO transportadora_usuario(idusuario, idtransportadora, datalogin) VALUES(?, ?, ?)");
            $data = date('Y-m-d');
            $stmt_link->bind_param("iis", $idusuario, $idtransportadora, $data);
            $stmt_link->execute();

            // 6. Atualizar status da solicitação
            $stmt_status = $this->conn->prepare("UPDATE solicitacao SET status = 1 WHERE idsolicitacao = ?");
            $stmt_status->bind_param("i", $idsolicitacao);
            $stmt_status->execute();
            
            // 7. Commit
            $this->conn->commit();
            return ['success' => true, 'message' => 'Solicitação aprovada com sucesso.'];

        } catch (\mysqli_sql_exception $exception) {
            $this->conn->rollback();
            return ['success' => false, 'message' => 'Erro no banco de dados: ' . $exception->getMessage()];
        }
    }
    
    public function deny($idsolicitacao) {
        $solicitacao = $this->findById($idsolicitacao);
        if (!$solicitacao) return ['success' => false, 'message' => 'Solicitação não encontrada.'];

        // Se a solicitação já foi aprovada, negar significa revogar e deletar tudo.
        if ($solicitacao->status == 1) {
            return $this->revokeApproval($solicitacao);
        } else {
            // Se estiver pendente, apenas muda o status para 'negado'
            $stmt = $this->conn->prepare("UPDATE solicitacao SET status = 2 WHERE idsolicitacao = ?");
            $stmt->bind_param("i", $idsolicitacao);
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Solicitação negada com sucesso.'];
            }
            return ['success' => false, 'message' => 'Falha ao negar a solicitação.'];
        }
    }

    private function revokeApproval(Solicitacao $solicitacao) {
        $this->conn->begin_transaction();
        try {
            // Busca IDs relacionados à transportadora pelo CNPJ
            $stmt_ids = $this->conn->prepare("SELECT idtransportadora FROM transportadora WHERE cnpj = ?");
            $stmt_ids->bind_param("s", $solicitacao->cnpj);
            $stmt_ids->execute();
            $result = $stmt_ids->get_result();
            if ($row = $result->fetch_assoc()) {
                $idtransportadora = $row['idtransportadora'];

                // Deletar em ordem para não violar as chaves estrangeiras
                $this->conn->query("DELETE FROM veiculo WHERE idtransportadora = $idtransportadora");
                $this->conn->query("DELETE FROM pagamento WHERE idtransportadora = $idtransportadora");
                
                // Pega IDs dos usuários antes de deletar a relação
                $user_result = $this->conn->query("SELECT idusuario FROM transportadora_usuario WHERE idtransportadora = $idtransportadora");
                $user_ids = [];
                while ($user_row = $user_result->fetch_assoc()) {
                    $user_ids[] = $user_row['idusuario'];
                }

                $this->conn->query("DELETE FROM transportadora_usuario WHERE idtransportadora = $idtransportadora");
                
                if (!empty($user_ids)) {
                    $this->conn->query("DELETE FROM usuario WHERE idusuario IN (" . implode(',', $user_ids) . ")");
                }

                $this->conn->query("DELETE FROM transportadora WHERE idtransportadora = $idtransportadora");
            }
            
            // Finalmente, atualiza o status da solicitação
            $stmt_status = $this->conn->prepare("UPDATE solicitacao SET status = 2 WHERE idsolicitacao = ?");
            $stmt_status->bind_param("i", $solicitacao->idsolicitacao);
            $stmt_status->execute();

            $this->conn->commit();
            return ['success' => true, 'message' => 'Aprovação revogada e dados excluídos com sucesso.'];
        } catch (\mysqli_sql_exception $exception) {
            $this->conn->rollback();
            return ['success' => false, 'message' => 'Erro ao revogar aprovação: ' . $exception->getMessage()];
        }
    }
}