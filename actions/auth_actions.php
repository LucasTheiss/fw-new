<?php
session_start();
require_once '../autoload.php';

use src\Service\AuthService;
use src\Service\RegistrationService;

$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {
    case 'login':
        $authService = new AuthService();
        $user_data = $authService->attemptLogin($_POST['email'] ?? '', $_POST['senha'] ?? '');

        if ($user_data) {
            $_SESSION['user_id'] = $user_data['idusuario'];
            $_SESSION['user_name'] = $user_data['nome'];
            $_SESSION['user_role'] = $user_data['role'];

            if ($user_data['role'] === 'gerente' || $user_data['role'] === 'motorista') {
                $_SESSION['id_transportadora'] = $user_data['idtransportadora'];
            }
            
            $redirect_path = '/' . $user_data['role'];
            header('Location: ' . $redirect_path);
            exit;
        }

        $_SESSION['error_message'] = "E-mail ou senha inválidos.";
        header('Location: ../login.php');
        exit;

    case 'register':
        $registrationService = new RegistrationService();
        $success = $registrationService->register(
            $_POST['transportadora'] ?? [],
            $_POST['gerente'] ?? [],
            $_FILES['anexos'] ?? []
        );

        if ($success) {
            $_SESSION['success_message'] = "Registo enviado com sucesso! Aguarde a aprovação.";
            header('Location: ../index.php');
        } else {
            $_SESSION['error_message'] = "Ocorreu um erro no registo. Por favor, tente novamente.";
            header('Location: ../cadastro_transportadora.php');
        }
        exit;

    case 'logout':
        session_destroy();
        header('Location: ../login.php');
        exit;

    default:
        $_SESSION['error_message'] = "Ação desconhecida.";
        header('Location: ../login.php');
        exit;
}
