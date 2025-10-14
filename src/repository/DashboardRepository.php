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
}