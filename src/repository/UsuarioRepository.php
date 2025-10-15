<?php
namespace src\Repository;

use Config\Database;
use src\Model\User;
use src\Model\Gerente;
use src\Model\Motorista;
use src\Model\Admin;

class UsuarioRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function findById(int $idusuario): ?array
    {
        $stmt = $this->conn->prepare("SELECT idusuario, nome, email, cpf, telefone FROM usuario WHERE idusuario = ?");
        $stmt->bind_param("i", $idusuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare("SELECT u.*, tu.idtransportadora FROM usuario u LEFT JOIN transportadora_usuario tu ON u.idusuario = tu.idusuario WHERE u.email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function findByCpfOrEmail(string $cpf, string $email, int $excludeId = 0): ?array
    {
        $sql = "SELECT idusuario FROM usuario WHERE (cpf = ? OR email = ?)";
        if ($excludeId > 0) {
            $sql .= " AND idusuario != ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $cpf, $email, $excludeId);
        } else {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $cpf, $email);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create(User $user, int $idtransportadora): bool
    {
        $this->conn->begin_transaction();
        try {
            $hashed_password = password_hash($user->senha, PASSWORD_DEFAULT);
            $gerente_flag = 0; 
            $admin_flag = 0;

            $stmt_user = $this->conn->prepare("INSERT INTO usuario (email, nome, senha, telefone, cpf, gerente, adm) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_user->bind_param("sssssii", $user->email, $user->nome, $hashed_password, $user->telefone, $user->cpf, $gerente_flag, $admin_flag);
            
            if (!$stmt_user->execute()) throw new \Exception("Falha ao criar usuário.");
            
            $idusuario = $this->conn->insert_id;

            $stmt_link = $this->conn->prepare("INSERT INTO transportadora_usuario (idusuario, idtransportadora, datalogin) VALUES (?, ?, ?)");
            $data = date('Y-m-d');
            $stmt_link->bind_param("iis", $idusuario, $idtransportadora, $data);
            if (!$stmt_link->execute()) throw new \Exception("Falha ao vincular usuário à transportadora.");

            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function update(User $user): bool
    {
        if (!empty($user->senha)) {
            $hashed_password = password_hash($user->senha, PASSWORD_DEFAULT);
            $sql = "UPDATE usuario SET nome = ?, email = ?, cpf = ?, telefone = ?, senha = ? WHERE idusuario = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssssi", $user->nome, $user->email, $user->cpf, $user->telefone, $hashed_password, $user->idusuario);
        } else {
            $sql = "UPDATE usuario SET nome = ?, email = ?, cpf = ?, telefone = ? WHERE idusuario = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssi", $user->nome, $user->email, $user->cpf, $user->telefone, $user->idusuario);
        }
        
        return $stmt->execute();
    }

    public function delete(int $idusuario, int $idtransportadora): bool
    {
        $this->conn->begin_transaction();
        try {
            $stmt_link = $this->conn->prepare("DELETE FROM transportadora_usuario WHERE idusuario = ? AND idtransportadora = ?");
            $stmt_link->bind_param("ii", $idusuario, $idtransportadora);
            $stmt_link->execute();

            if ($stmt_link->affected_rows > 0) {
                $stmt_user = $this->conn->prepare("DELETE FROM usuario WHERE idusuario = ?");
                $stmt_user->bind_param("i", $idusuario);
                $stmt_user->execute();
            } else {
                throw new \Exception("Usuário não pertence a esta transportadora.");
            }
            
            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
    
    public function findIntegrantesByTransportadora(int $idtransportadora): array
    {
        $sql = "SELECT u.idusuario, u.nome, u.email, u.telefone, u.cpf 
                FROM usuario u
                JOIN transportadora_usuario tu ON u.idusuario = tu.idusuario
                WHERE tu.idtransportadora = ? AND u.gerente = 0 
                ORDER BY u.nome";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idtransportadora);
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}