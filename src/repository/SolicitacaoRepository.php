<?php
namespace src\Repository;

use Config\Database;
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

    public function updateStatus(int $idsolicitacao, int $status): bool {
        $stmt = $this->conn->prepare("UPDATE solicitacao SET status = ? WHERE idsolicitacao = ?");
        $stmt->bind_param("ii", $status, $idsolicitacao);
        return $stmt->execute();
    }

    public function executarAprovacao(Solicitacao $solicitacao): bool {
        $this->conn->begin_transaction();
        try {
            $stmt_user = $this->conn->prepare("INSERT INTO usuario(email, nome, senha, telefone, cpf, gerente) VALUES(?, ?, ?, ?, ?, 1)");
            $stmt_user->bind_param("sssss", $solicitacao->emailUsuario, $solicitacao->nomeUsuario, $solicitacao->senha, $solicitacao->telefoneUsuario, $solicitacao->cpf);
            $stmt_user->execute();
            $idusuario = $this->conn->insert_id;

            $now = (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('Y-m-d H:i:s');
            $stmt_trans = $this->conn->prepare("INSERT INTO transportadora(nome, endereco, cidade, estado, cep, cnpj, dataCriacao) VALUES(?, ?, ?, ?, ?, ?, ?)");
            $stmt_trans->bind_param("sssssss", $solicitacao->nomeTransportadora, $solicitacao->endereco, $solicitacao->cidade, $solicitacao->estado, $solicitacao->cep, $solicitacao->cnpj, $now);
            $stmt_trans->execute();
            $idtransportadora = $this->conn->insert_id;

            $stmt_link = $this->conn->prepare("INSERT INTO transportadora_usuario(idusuario, idtransportadora, datalogin) VALUES(?, ?, ?)");
            $data = date('Y-m-d');
            $stmt_link->bind_param("iis", $idusuario, $idtransportadora, $data);
            $stmt_link->execute();

            $this->updateStatus($solicitacao->idsolicitacao, 1); 
            
            $this->conn->commit();
            return true;
        } catch (\mysqli_sql_exception $exception) {
            $this->conn->rollback();
            return false;
        }
    }
    
    public function executarRevogacao(Solicitacao $solicitacao): bool {
        $this->conn->begin_transaction();
        try {
            $stmt_ids = $this->conn->prepare("SELECT idtransportadora FROM transportadora WHERE cnpj = ?");
            $stmt_ids->bind_param("s", $solicitacao->cnpj);
            $stmt_ids->execute();
            $result = $stmt_ids->get_result();
            if ($row = $result->fetch_assoc()) {
                $idtransportadora = $row['idtransportadora'];
                // ... (toda a lógica de delete em cascata)
            }
            
            $this->updateStatus($solicitacao->idsolicitacao, 2); 

            $this->conn->commit();
            return true;
        } catch (\mysqli_sql_exception $exception) {
            $this->conn->rollback();
            return false;
        }
    }
}