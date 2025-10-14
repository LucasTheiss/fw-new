<?php
namespace src\Repository;

use Config\Database;
use PDO;
use src\Model\Viagem;

class ViagemRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByTransportadora(int $idtransportadora): array
    {
        $sql = "SELECT v.* FROM viagem v
                JOIN usuario u ON v.idmotorista = u.idusuario
                WHERE u.idtransportadora = :idtransportadora
                ORDER BY v.data_partida DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idtransportadora' => $idtransportadora]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Viagem::class);
    }

    public function findCurrentTripByDriver(int $idmotorista): ?Viagem
    {
        $sql = "SELECT * FROM viagem WHERE idmotorista = :idmotorista AND status = 'em_curso' LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idmotorista' => $idmotorista]);
        $result = $stmt->fetchObject(Viagem::class);
        return $result ?: null;
    }

    public function create(Viagem $viagem): bool
    {
        $sql = "INSERT INTO viagem (idveiculo, idmotorista, origem, destino, distancia_km, data_partida, status) 
                VALUES (:idveiculo, :idmotorista, :origem, :destino, :distancia_km, :data_partida, :status)";
        
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':idveiculo' => $viagem->idveiculo,
            ':idmotorista' => $viagem->idmotorista,
            ':origem' => $viagem->origem,
            ':destino' => $viagem->destino,
            ':distancia_km' => $viagem->distancia_km,
            ':data_partida' => $viagem->data_partida,
            ':status' => $viagem->status ?? 'agendada'
        ]);
    }

    public function updateStatus(int $idviagem, string $status): bool
    {
        $sql = "UPDATE viagem SET status = :status";
        $params = ['status' => $status, 'id' => $idviagem];

        if ($status === 'finalizada') {
            $sql .= ", data_chegada = CURRENT_TIMESTAMP";
        }

        $sql .= " WHERE idviagem = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
