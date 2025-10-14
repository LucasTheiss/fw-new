<?php
namespace src\Service;

use src\Repository\UsuarioRepository;
use src\Factory\UserFactory; 
use config\Database; 

class AuthService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UsuarioRepository();
        require_once __DIR__ . '/../../config/Database.php'; 
        $this->conn = Database::getInstance()->getConnection();
    }

    public function attemptLogin(string $email, string $senha): ?array
    {
        $user_data = $this->userRepository->findByEmail($email);

        if ($user_data && password_verify($senha, $user_data['senha'])) {
            // Se o login for bem-sucedido, determina a role
            if ($user_data['adm']) {
                $user_data['role'] = 'admin';
            } elseif ($user_data['gerente']) {
                $user_data['role'] = 'gerente';
            } else {
                $user_data['role'] = 'motorista';
            }

            $idusuario = (int) $user_data['idusuario'];
            $sql = "SELECT idtransportadora FROM transportadora_usuario WHERE idusuario = $idusuario LIMIT 1";
            $result = mysqli_query($this->conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $user_data['idtransportadora'] = $row['idtransportadora'];
            } else {
                $user_data['idtransportadora'] = null;
            }
            return $user_data;
        }

        return null;
    }
}
