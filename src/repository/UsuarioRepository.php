<?php
namespace src\Repository;

use Config\Database;
use src\Model\User;
use src\Model\Gerente;
use src\Model\Motorista;
use src\Factory\UserFactory;

class UsuarioRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE email = ?");
        if (!$stmt) return null;

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $data ?: null;
    }

    public function create(User $user, int $idtransportadora = null): ?int
    {
        $sql = "INSERT INTO usuario (email, nome, senha, telefone, cpf, gerente, adm, idtransportadora)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $email = $user->email;
        $nome = $user->nome;
        $senha = password_hash($user->senha, PASSWORD_DEFAULT);
        $telefone = $user->telefone;
        $cpf = $user->cpf;
        $gerente = $user instanceof Gerente ? 1 : 0;
        $adm = property_exists($user, 'adm') && $user->adm ? 1 : 0;
        $idtransp = $idtransportadora;

        $stmt->bind_param(
            "ssssiiii",
            $email,
            $nome,
            $senha,
            $telefone,
            $cpf,
            $gerente,
            $adm,
            $idtransp
        );

        $success = $stmt->execute();
        $insertId = $success ? $this->db->insert_id : null;
        $stmt->close();

        return $insertId;
    }

    public function findMotoristasByTransportadora(int $idtransportadora): array
    {
        $sql = "SELECT * FROM usuario WHERE idtransportadora = ? AND gerente = 0 AND adm = 0";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $idtransportadora);
        $stmt->execute();
        $result = $stmt->get_result();

        $motoristas = [];
        while ($row = $result->fetch_assoc()) {
            $motorista = new Motorista();
            foreach ($row as $key => $value) {
                $motorista->$key = $value;
            }
            $motoristas[] = $motorista;
        }

        $stmt->close();
        return $motoristas;
    }
}
