<?php
namespace src\Repository;

use config\Database;
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
        $sql = "SELECT * FROM veiculo WHERE idtransportadora = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idtransportadora);
        $stmt->execute();
        $result = $stmt->get_result();
        $veiculos = [];
        while ($row = $result->fetch_object(Veiculo::class)) {
            $veiculos[] = $row;
        }
        $stmt->close();
        return $veiculos;
    }

    public function create(Veiculo $veiculo): bool
    {
        $sql = "INSERT INTO veiculo (idtransportadora, marca, modelo, ano, placa, capacidade_tanque) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "isssis",
            $veiculo->idtransportadora,
            $veiculo->marca,
            $veiculo->modelo,
            $veiculo->ano,
            $veiculo->placa,
            $veiculo->capacidade_tanque
        );
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
