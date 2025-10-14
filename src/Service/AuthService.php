<?php
namespace src\Service;

use src\Repository\UsuarioRepository;
use src\Factory\UserFactory; // Será criado a seguir

class AuthService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UsuarioRepository();
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
            return $user_data;
        }

        return null;
    }
}
