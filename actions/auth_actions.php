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
        $userData = $authService->attemptLogin($_POST['email'] ?? '', $_POST['senha'] ?? '');

        if ($userData) {
            $_SESSION['user_id'] = $userData['idusuario'];
            $_SESSION['user_name'] = $userData['nome'];
            $_SESSION['user_role'] = $userData['role']; 
            
            if (isset($userData['idtransportadora'])) {
                $_SESSION['id_transportadora'] = $userData['idtransportadora'];
            }
            
            header('Location: /FW/' . $userData['role'] . '/');
            exit;
        }

        $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'E-mail ou senha inválidos.', 'icon' => 'error'];
        header('Location: /FW/login.php');
        exit;

    case 'register':
        $registrationService = new RegistrationService();
        $success = $registrationService->registerFromForm($_POST);

        if ($success) {
            unset($_SESSION['form_data']); // Limpa os dados do formulário da sessão
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