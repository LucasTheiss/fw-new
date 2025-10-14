<?php
namespace src\Repository;

use Config\Database;
use src\Model\Veiculo;

class VeiculoRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function findById(int $idveiculo): ?Veiculo
    {
        $sql = "SELECT * FROM veiculo WHERE idveiculo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idveiculo);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object(Veiculo::class);
    }

    public function findByTransportadora(int $idtransportadora): array
    {
        $sql = "SELECT * FROM veiculo WHERE idtransportadora = ? ORDER BY modelo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idtransportadora);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $veiculos = [];
        while ($row = $result->fetch_object(Veiculo::class)) {
            $veiculos[] = $row;
        }
        return $veiculos;
    }

    public function create(Veiculo $veiculo): bool
    {
        $sql = "INSERT INTO veiculo (idtransportadora, placa, modelo, eixos, litragem, observacao) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "issids",
            $veiculo->idtransportadora,
            $veiculo->placa,
            $veiculo->modelo,
            $veiculo->eixos,
            $veiculo->litragem,
            $veiculo->observacao
        );
        return $stmt->execute();
    }

    public function update(Veiculo $veiculo): bool
    {
        $sql = "UPDATE veiculo SET placa = ?, modelo = ?, eixos = ?, litragem = ?, observacao = ? 
                WHERE idveiculo = ? AND idtransportadora = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssidsii",
            $veiculo->placa,
            $veiculo->modelo,
            $veiculo->eixos,
            $veiculo->litragem,
            $veiculo->observacao,
            $veiculo->idveiculo,
            $veiculo->idtransportadora
        );
        return $stmt->execute();
    }

    public function delete(int $idveiculo, int $idtransportadora): bool
    {
        // A condição `idtransportadora` garante que um gerente só possa deletar seus próprios veículos
        $sql = "DELETE FROM veiculo WHERE idveiculo = ? AND idtransportadora = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $idveiculo, $idtransportadora);
        return $stmt->execute();
    }
}