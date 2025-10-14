<?php
namespace src\Factory;

use src\Model\Admin;
use src\Model\Gerente;
use src\Model\Motorista;
use src\Model\User;
use InvalidArgumentException;

class UserFactory
{
    /**
     * Cria uma instância de um tipo de utilizador a partir de um array de dados do banco.
     *
     * @param array $data Dados do utilizador.
     * @return User O objeto de utilizador correspondente.
     */
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
                $user->cnh = $data['cnh'] ?? null;
                break;
            default:
                // Lança uma exceção se não conseguir determinar a role, para segurança.
                throw new InvalidArgumentException("Tipo de utilizador ('role') inválido ou não determinado.");
        }

        // Atribui propriedades comuns a todos os utilizadores
        $user->idusuario = (int) $data['idusuario'];
        $user->nome = $data['nome'];
        $user->email = $data['email'];
        $user->senha = $data['senha']; // A senha já vem com hash do banco

        return $user;
    }

    /**
     * Determina a role do utilizador com base nos campos booleanos do banco de dados.
     */
    private static function determineRole(array $data): string
    {
        if (!empty($data['adm'])) {
            return 'admin';
        }
        if (!empty($data['gerente'])) {
            return 'gerente';
        }
        return 'motorista'; // Assume motorista como padrão se não for admin ou gerente
    }
}
