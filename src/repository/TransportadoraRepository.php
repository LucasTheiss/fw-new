<?php
namespace src\Repository;

use Config\Database;
use PDO;
use src\Model\Transportadora;

class TransportadoraRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(Transportadora $transportadora): ?int
    {
        $sql = "INSERT INTO transportadora (razao_social, nome_fantasia, cnpj, endereco, telefone, email, status) 
                VALUES (:razao_social, :nome_fantasia, :cnpj, :endereco, :telefone, :email, :status)";
        
        $stmt = $this->db->prepare($sql);
        
        $success = $stmt->execute([
            ':razao_social' => $transportadora->razao_social,
            ':nome_fantasia' => $transportadora->nome_fantasia,
            ':cnpj' => $transportadora->cnpj,
            ':endereco' => $transportadora->endereco,
            ':telefone' => $transportadora->telefone,
            ':email' => $transportadora->email,
            ':status' => $transportadora->status ?? 'pendente'
        ]);

        return $success ? $this->db->lastInsertId() : null;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE transportadora SET status = :status WHERE idtransportadora = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
}
