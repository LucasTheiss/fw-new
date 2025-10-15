<?php
namespace src\Factory;

use src\Model\Admin;
use src\Model\Gerente;
use src\Model\Motorista;
use src\Model\User;
use InvalidArgumentException;

class UserFactory
{
    public static function create(array $data): User
    {
        $role = self::determineRole($data);

        switch ($role) {
            case 'admin':
                $user = new Admin();
                break;
            case 'gerente':
                $user = new Gerente();
                $user->idtransportadora = $data['idtransportadora'] ?? null;
                break;
            case 'motorista':
                $user = new Motorista();
                $user->idtransportadora = $data['idtransportadora'] ?? null;
                break;
            default:
                throw new InvalidArgumentException("Tipo de utilizador ('role') inválido ou não determinado.");
        }

        $user->idusuario = (int) $data['idusuario'];
        $user->nome = $data['nome'];
        $user->email = $data['email'];
        $user->senha = $data['senha'];

        $user->role = $role;

        return $user;
    }

    private static function determineRole(array $data): string
    {
        if (!empty($data['adm'])) {
            return 'admin';
        }
        if (!empty($data['gerente'])) {
            return 'gerente';
        }
        return 'motorista';
    }
}