<?php
session_start();
require_once '../autoload.php';

use src\Service\AuthService;
use src\Service\RegistrationService;

$action = $_POST['action'] ?? $_GET['action'] ?? null;

if (!$action) {
    $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'Ação inválida.', 'icon' => 'error'];
    header('Location: /FW/login.php');
    exit;
}

switch ($action) {
    case 'login':
        $authService = new AuthService();
        $user = $authService->attemptLogin($_POST['email'] ?? '', $_POST['senha'] ?? '');

        if ($user) {
            $_SESSION['user_id'] = $user->idusuario;
            $_SESSION['user_name'] = $user->nome;
            $_SESSION['user_role'] = $user->role; 
            $_SESSION['id_transportadora'] = $user->idtransportadora ?? 0;
            
            if ($user->role != 'admin'){
                if ($user->role == 'motorista'){
                    $redirect = 'driver';
                } else if ($user->role == 'gerente'){
                    $redirect = 'manager';
                }
            }
            header('Location: /FW/' . $redirect . '/');
            exit;
        }

        $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'E-mail ou senha inválidos.', 'icon' => 'error'];
        header('Location: /FW/login.php');
        exit;

    case 'register':
        $registrationService = new RegistrationService();
        $success = $registrationService->registerFromForm($_POST);

        if ($success) {
            unset($_SESSION['form_data']);
            $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Solicitação de cadastro enviada com sucesso! Aguarde a aprovação.', 'icon' => 'success'];
            header('Location: /FW/login.php');
        } else {
            header('Location: /FW/cadastro_transportadora.php');
        }
        exit;

    case 'logout':
        session_destroy();
        header('Location: /FW/login.php');
        exit;

    default:
        $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'Ação desconhecida.', 'icon' => 'error'];
        header('Location: /FW/login.php');
        exit;
}