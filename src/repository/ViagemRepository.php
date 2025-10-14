<?php
namespace src\Repository;

use Config\Database;
use src\Model\Viagem;

class ViagemRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function findById(int $idviagem): ?Viagem
    {
        $stmt = $this->conn->prepare("SELECT * FROM viagem WHERE idviagem = ?");
        $stmt->bind_param("i", $idviagem);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object(Viagem::class);
    }

    public function findByTransportadora(int $idtransportadora): array
    {
        $sql = "SELECT v.*, u.nome as motorista_nome, ve.placa as veiculo_placa
                FROM viagem v
                JOIN usuario u ON v.idmotorista = u.idusuario
                JOIN veiculo ve ON v.idveiculo = ve.idveiculo
                WHERE u.idtransportadora = ?
                ORDER BY v.data_partida DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idtransportadora);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create(Viagem $viagem): bool
    {
        $sql = "INSERT INTO viagem (idveiculo, idmotorista, origem, destino, distancia_km, data_partida, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'agendada')";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iissds",
            $viagem->idveiculo,
            $viagem->idmotorista,
            $viagem->origem,
            $viagem->destino,
            $viagem->distancia_km,
            $viagem->data_partida
        );
        return $stmt->execute();
    }

    public function updateStatus(int $idviagem, string $status): bool
    {
        $sql = "UPDATE viagem SET status = ?";
        $params = [$status];

        if ($status === 'finalizada') {
            $sql .= ", data_chegada = CURRENT_TIMESTAMP";
        }
        
        $sql .= " WHERE idviagem = ?";
        $params[] = $idviagem;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($params)-1) . 'i', ...$params);
        return $stmt->execute();
    }
}