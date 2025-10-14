<?php
namespace src\Repository;

use Config\Database;
use PDO;
use src\Model\User;
use src\Model\Gerente;
use src\Model\Motorista;
// A UserFactory será criada mais tarde
// use src\Factory\UserFactory; 

class UsuarioRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ?: null;
    }

    public function create(User $user, int $idtransportadora = null): ?int
    {
        $sql = "INSERT INTO usuario (email, nome, senha, telefone, cpf, gerente, adm, idtransportadora) 
                VALUES (:email, :nome, :senha, :telefone, :cpf, :gerente, :adm, :idtransportadora)";
        
        $stmt = $this->db->prepare($sql);
        
        $success = $stmt->execute([
            ':email' => $user->email,
            ':nome' => $user->nome,
            ':senha' => password_hash($user->senha, PASSWORD_DEFAULT),
            ':telefone' => $user->telefone,
            ':cpf' => $user->cpf,
            ':gerente' => $user instanceof Gerente ? 1 : 0,
            ':adm' => $user instanceof Admin ? 1 : 0,
            ':idtransportadora' => $idtransportadora
        ]);

        return $success ? $this->db->lastInsertId() : null;
    }
    
    public function findMotoristasByTransportadora(int $idtransportadora): array
    {
        $sql = "SELECT * FROM usuario WHERE idtransportadora = :idtransportadora AND gerente = 0 AND adm = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['idtransportadora' => $idtransportadora]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Motorista::class);
    }
}
