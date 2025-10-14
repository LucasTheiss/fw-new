<?php
namespace src\Repository;

use Config\Database;
use PDO;
use src\Model\Pagamento;

class PagamentoRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(Pagamento $pagamento): bool
    {
        $sql = "INSERT INTO pagamento (idviagem, idposto, valor, litros, data_pagamento, path_anexo) 
                VALUES (:idviagem, :idposto, :valor, :litros, NOW(), :path_anexo)";
        
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':idviagem' => $pagamento->idviagem,
            ':idposto' => $pagamento->idposto,
            ':valor' => $pagamento->valor,
            ':litros' => $pagamento->litros,
            ':path_anexo' => $pagamento->path_anexo
        ]);
    }
}
