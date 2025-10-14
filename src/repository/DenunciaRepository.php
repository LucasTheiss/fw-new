<?php
namespace src\Repository;

use config\Database;
use src\Model\Denuncia;

class DenunciaRepository {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function findAllWithDetails() {
        $sql = "SELECT d.*, u.nome AS nomeUsuario, u.email FROM denuncia d 
                JOIN usuario u ON d.idusuario = u.idusuario 
                ORDER BY d.data_criacao DESC";
        $result = $this->conn->query($sql);
        
        $denuncias = [];
        if ($result->num_rows > 0) {
            while($denuncia_row = $result->fetch_assoc()) {
                $denuncia = new Denuncia();
                $denuncia->iddenuncia = (int)$denuncia_row['iddenuncia'];
                $denuncia->titulo = $denuncia_row['titulo'];
                $denuncia->motivo = $denuncia_row['motivo'];
                $denuncia->data_criacao = $denuncia_row['data_criacao'];
                $denuncia->nomeUsuario = $denuncia_row['nomeUsuario'];
                $denuncia->email = $denuncia_row['email'];
                $denuncia->anexos = $this->findAnexosByDenunciaId($denuncia->iddenuncia);
                $denuncias[] = $denuncia;
            }
        }
        return $denuncias;
    }

    private function findAnexosByDenunciaId($iddenuncia) {
        $anexos = [];
        $stmt = $this->conn->prepare("SELECT * FROM anexos WHERE iddenuncia = ?");
        $stmt->bind_param("i", $iddenuncia);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($anexo_row = $result->fetch_assoc()) {
                $anexo = new \src\Model\Anexo();
                $baseUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/FuelWise/';
                $relativePath = str_replace('../', '/', $anexo_row['path']);
                
                $anexo->nome_arquivo = $anexo_row['nome_arquivo'];
                $anexo->path = $baseUrl . $relativePath;
                $anexos[] = $anexo;
            }
        }
        return $anexos;
    }

    public function delete($iddenuncia) {
        $this->conn->begin_transaction();
        try {
            $stmt1 = $this->conn->prepare("DELETE FROM anexos WHERE iddenuncia = ?");
            $stmt1->bind_param("i", $iddenuncia);
            $stmt1->execute();
            
            // Aqui você deveria adicionar a lógica para apagar os arquivos físicos do servidor se necessário

            $stmt2 = $this->conn->prepare("DELETE FROM denuncia WHERE iddenuncia = ?");
            $stmt2->bind_param("i", $iddenuncia);
            $stmt2->execute();

            $this->conn->commit();
            return true;
        } catch (\mysqli_sql_exception $exception) {
            $this->conn->rollback();
            return false;
        }
    }
}