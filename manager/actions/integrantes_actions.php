<?php
require_once '../../autoload.php';
require_once '../acesso.php';

use src\Repository\UsuarioRepository;
use src\Model\Motorista;

$action = $_POST['action'] ?? $_GET['action'] ?? null;

if (!$idtransportadora) {
    $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'Sessão inválida.', 'icon' => 'error'];
    header('Location: ../../pages/login.php');
    exit;
}

$usuarioRepo = new UsuarioRepository();

switch ($action) {
    case 'create':
        if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['cpf']) || empty($_POST['senha'])) {
            $_SESSION['alert'] = ['title' => 'Atenção!', 'text' => 'Todos os campos são obrigatórios.', 'icon' => 'warning'];
            break;
        }

        $cpf = preg_replace('/\D/', '', $_POST['cpf']);
        if ($usuarioRepo->findByCpfOrEmail($cpf, $_POST['email'])) {
            $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'CPF ou E-mail já cadastrado.', 'icon' => 'error'];
            break;
        }

        $motorista = new Motorista();
        $motorista->nome = $_POST['nome'];
        $motorista->email = $_POST['email'];
        $motorista->cpf = $cpf;
        $motorista->telefone = $_POST['telefone'];
        $motorista->senha = $_POST['senha'];

        if ($usuarioRepo->create($motorista, $idtransportadora)) {
            $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Motorista cadastrado.', 'icon' => 'success'];
        } else {
            $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'Não foi possível cadastrar.', 'icon' => 'error'];
        }
        break;

    case 'update':
        if (empty($_POST['idusuario']) || empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['cpf'])) {
            $_SESSION['alert'] = ['title' => 'Atenção!', 'text' => 'Campos essenciais estão faltando.', 'icon' => 'warning'];
            break;
        }

        $idusuario = (int)$_POST['idusuario'];
        $cpf = preg_replace('/\D/', '', $_POST['cpf']);

        if ($usuarioRepo->findByCpfOrEmail($cpf, $_POST['email'], $idusuario)) {
            $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'CPF ou E-mail já pertence a outro usuário.', 'icon' => 'error'];
            break;
        }
        
        $motorista = new Motorista();
        $motorista->idusuario = $idusuario;
        $motorista->nome = $_POST['nome'];
        $motorista->email = $_POST['email'];
        $motorista->cpf = $cpf;
        $motorista->telefone = $_POST['telefone'];
        $motorista->senha = $_POST['senha'] ?? null; // Senha é opcional na atualização

        if ($usuarioRepo->update($motorista)) {
            $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Integrante atualizado.', 'icon' => 'success'];
        } else {
            $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'Não foi possível atualizar.', 'icon' => 'error'];
        }
        break;

    case 'delete':
        $idusuario = (int)($_GET['id'] ?? 0);
        if ($idusuario <= 0) {
            $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'ID de integrante inválido.', 'icon' => 'error'];
            break;
        }

        if ($usuarioRepo->delete($idusuario, $idtransportadora)) {
            $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Integrante excluído.', 'icon' => 'success'];
        } else {
            $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'Não foi possível excluir.', 'icon' => 'error'];
        }
        break;
}

header('Location: ../integrantes.php');
exit;