<?php
namespace src\Repository;

use Config\Database;
use PDO;
use src\Model\Veiculo;

class VeiculoRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByTransportadora(int $idtransportadora): array
    {
        $sql = "SELECT * FROM veiculo WHERE idtransportadora = :idtransportadora";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idtransportadora' => $idtransportadora]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Veiculo::class);
    }

    public function create(Veiculo $veiculo): bool
    {
        $sql = "INSERT INTO veiculo (idtransportadora, marca, modelo, ano, placa, capacidade_tanque) 
                VALUES (:idtransportadora, :marca, :modelo, :ano, :placa, :capacidade_tanque)";
        
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':idtransportadora' => $veiculo->idtransportadora,
            ':marca' => $veiculo->marca,
            ':modelo' => $veiculo->modelo,
            ':ano' => $veiculo->ano,
            ':placa' => $veiculo->placa,
            ':capacidade_tanque' => $veiculo->capacidade_tanque
        ]);
    }
}
