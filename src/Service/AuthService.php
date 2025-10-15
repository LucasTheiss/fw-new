<?php
namespace src\Service;

use src\Repository\UsuarioRepository;
use src\Factory\UserFactory;
use src\Model\User;

class AuthService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UsuarioRepository();
    }

    public function attemptLogin(string $email, string $senha): ?User
    {
        $user_data = $this->userRepository->findByEmail($email);

        if ($user_data && password_verify($senha, $user_data['senha'])) {
            return UserFactory::create($user_data);
        }

        return null;
    }
}