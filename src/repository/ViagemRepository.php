<?php
namespace src\Repository;

use Config\Database;
use src\Model\Viagem;

class ViagemRepository
{
    private $conn;

    const STATUS_AGENDADA = 0;
    const STATUS_EM_CURSO = 1;
    const STATUS_FINALIZADA = 2;
    const STATUS_CANCELADA = 3;

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
                JOIN usuario u ON v.idusuario = u.idusuario
                JOIN veiculo ve ON v.idveiculo = ve.idveiculo
                WHERE ve.idtransportadora = ?
                ORDER BY v.data_inicio DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idtransportadora);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStatus(int $idviagem, int $status): bool
    {
        $sql = "UPDATE viagem SET status = ?";
        
        if ($status === self::STATUS_FINALIZADA) {
            $sql .= ", data_termino = NOW()";
        }
        
        $sql .= " WHERE idviagem = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $status, $idviagem);
        return $stmt->execute();
    }

    public function create(Viagem $viagem): bool
    {
        $sql = "INSERT INTO viagem (idusuario, idveiculo, data_inicio, endereco_origem, latitude_origem, longitude_origem, endereco_destino, latitude_destino, longitude_destino, carga, peso, obs, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $status = self::STATUS_AGENDADA; 

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iisssdssdsssi",
            $viagem->idusuario,
            $viagem->idveiculo,
            $viagem->data_inicio,
            $viagem->endereco_origem,
            $viagem->latitude_origem,
            $viagem->longitude_origem,
            $viagem->endereco_destino,
            $viagem->latitude_destino,
            $viagem->longitude_destino,
            $viagem->carga,
            $viagem->peso,
            $viagem->obs,
            $status
        );
        return $stmt->execute();
    }
}