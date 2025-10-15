<?php
namespace src\Repository;

use config\Database;

class DashboardRepository {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getStats() {
        $stats = [];
        $tables = [
            'veiculo' => 'totalVeiculos',
            'transportadora_usuario' => 'totalUsuarios',
            'viagem' => 'totalViagens',
            'denuncia' => 'totalDenuncias',
            'transportadora' => 'totalTransportadoras',
            'posto' => 'totalPostos'
        ];

        foreach ($tables as $table => $key) {
            $query = "SELECT COUNT(*) AS total FROM $table";
            $result = $this->conn->query($query);
            $stats[$key] = ($result && $row = $result->fetch_assoc()) ? (int)$row['total'] : 0;
        }
        return $stats;
    }

    public function getMonthlyUserRegistrations() {
        $data = [];
        $sql = "SELECT DATE_FORMAT(datalogin, '%Y-%m') AS mes, COUNT(*) AS total_usuarios
                FROM transportadora_usuario
                WHERE datalogin >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY mes ORDER BY mes";
        $result = $this->conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = ['mes' => $row['mes'], 'usuarios' => (int)$row['total_usuarios']];
            }
        }
        return $data;
    }

    public function getMonthlyTransportadoraRegistrations() {
        $data = [];
        $sql = "SELECT DATE_FORMAT(dataCriacao, '%Y-%m') AS mes, COUNT(*) AS total_transportadoras
                FROM transportadora
                WHERE dataCriacao >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY mes ORDER BY mes";
        $result = $this->conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = ['mes' => $row['mes'], 'quantidade' => (int)$row['total_transportadoras']];
            }
        }
        return $data;
    }

    public function getManagerStats(int $idtransportadora) {
        $stats = [];

        $stmt_veiculos = $this->conn->prepare("SELECT COUNT(*) AS total FROM veiculo WHERE idtransportadora = ?");
        $stmt_veiculos->bind_param("i", $idtransportadora);
        $stmt_veiculos->execute();
        $result_veiculos = $stmt_veiculos->get_result()->fetch_assoc();
        $stats['totalVeiculos'] = (int)$result_veiculos['total'];

        $stmt_motoristas = $this->conn->prepare("SELECT COUNT(*) AS total FROM transportadora_usuario tu JOIN usuario ON tu.idusuario = usuario.idusuario  WHERE idtransportadora = ? AND gerente = 0");
        $stmt_motoristas->bind_param("i", $idtransportadora);
        $stmt_motoristas->execute();
        $result_motoristas = $stmt_motoristas->get_result()->fetch_assoc();
        $stats['totalMotoristas'] = (int)$result_motoristas['total'];

        $stmt_viagens = $this->conn->prepare(
            "SELECT COUNT(v.idviagem) AS total FROM viagem v
             JOIN usuario u ON v.idusuario = u.idusuario JOIN transportadora_usuario tu ON u.idusuario = tu.idusuario
             WHERE tu.idtransportadora = ?"
        );
        $stmt_viagens->bind_param("i", $idtransportadora);
        $stmt_viagens->execute();
        $result_viagens = $stmt_viagens->get_result()->fetch_assoc();
        $stats['totalViagens'] = (int)$result_viagens['total'];

        return $stats;
    }
}