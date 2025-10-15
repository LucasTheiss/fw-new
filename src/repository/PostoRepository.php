<?php
namespace src\Repository;

use config\Database;
use src\Model\Posto;

class PostoRepository {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function findAllWithCombustiveis() {
        $sql = "SELECT p.idposto, p.nome, p.endereco, p.latitude, p.longitude, c.idcombustivel, c.tipo, c.preco 
                FROM posto p    
                LEFT JOIN combustivel c ON p.idposto = c.idposto 
                ORDER BY p.idposto DESC";
        $result = $this->conn->query($sql);
        
        $postos = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $idposto = (int)$row['idposto'];
                if (!isset($postos[$idposto])) {
                    $posto = new Posto();
                    $posto->idposto = $idposto;
                    $posto->nome = $row['nome'];
                    $posto->endereco = $row['endereco'];
                    $posto->latitude = $row['latitude'];
                    $posto->longitude = $row['longitude'];
                    $postos[$idposto] = $posto;
                }
                if (!empty($row['tipo'])) {
                    $combustivel = new \src\Model\Combustivel();
                    $combustivel->idcombustivel = $row['idcombustivel'];
                    $combustivel->tipo = $row['tipo'];
                    $combustivel->preco = $row['preco'];
                    $postos[$idposto]->combustiveis[] = $combustivel;
                }
            }
        }
        return $postos;
    }

    public function save(Posto $posto) {
        $stmt = $this->conn->prepare("INSERT INTO posto (nome, endereco, latitude, longitude) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdd", $posto->nome, $posto->endereco, $posto->latitude, $posto->longitude);
        return $stmt->execute();
    }

    public function update(Posto $posto) {
        $stmt = $this->conn->prepare("UPDATE posto SET nome = ?, endereco = ?, latitude = ?, longitude = ? WHERE idposto = ?");
        $stmt->bind_param("ssddi", $posto->nome, $posto->endereco, $posto->latitude, $posto->longitude, $posto->idposto);
        return $stmt->execute();
    }

    public function delete($idposto) {
        $this->conn->begin_transaction();
        try {
            $stmt1 = $this->conn->prepare("DELETE FROM combustivel WHERE idposto = ?");
            $stmt1->bind_param("i", $idposto);
            $stmt1->execute();

            $stmt2 = $this->conn->prepare("DELETE FROM posto WHERE idposto = ?");
            $stmt2->bind_param("i", $idposto);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (\mysqli_sql_exception $exception) {
            $this->conn->rollback();
            return false;
        }
    }
    
    public function saveCombustivel(\src\Model\Combustivel $combustivel) {
        $stmt_check = $this->conn->prepare("SELECT idcombustivel FROM combustivel WHERE idposto = ? AND tipo = ?");
        $stmt_check->bind_param("ii", $combustivel->idposto, $combustivel->tipo);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $stmt = $this->conn->prepare("UPDATE combustivel SET preco = ? WHERE idposto = ? AND tipo = ?");
            $stmt->bind_param("dii", $combustivel->preco, $combustivel->idposto, $combustivel->tipo);
        } else {
            $stmt = $this->conn->prepare("INSERT INTO combustivel (idposto, preco, tipo) VALUES (?, ?, ?)");
            $stmt->bind_param("idi", $combustivel->idposto, $combustivel->preco, $combustivel->tipo);
        }
        return $stmt->execute();
    }

    public function updateCombustivelPreco($idcombustivel, $preco) {
        $stmt = $this->conn->prepare("UPDATE combustivel SET preco = ? WHERE idcombustivel = ?");
        $stmt->bind_param("di", $preco, $idcombustivel);
        return $stmt->execute();
    }

    public function deleteCombustivel($idcombustivel) {
        $stmt = $this->conn->prepare("DELETE FROM combustivel WHERE idcombustivel = ?");
        $stmt->bind_param("i", $idcombustivel);
        return $stmt->execute();
    }
}