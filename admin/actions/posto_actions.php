<?php
session_start();
require_once '../../autoload.php';
require_once '../admRole.php'; // Garante que apenas o admin pode executar estas ações

use src\Repository\PostoRepository;

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$postoRepo = new PostoRepository();
$redirect_url = '../postos.php';

switch ($action) {
    case 'create':
        $success = $postoRepo->create($_POST);
        $_SESSION[$success ? 'success_message' : 'error_message'] = $success ? "Posto registado com sucesso!" : "Erro ao registar o posto.";
        break;

    case 'update':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error_message'] = "ID do posto não fornecido.";
            break;
        }
        $success = $postoRepo->update($id, $_POST);
        $_SESSION[$success ? 'success_message' : 'error_message'] = $success ? "Posto atualizado com sucesso!" : "Erro ao atualizar o posto.";
        break;

    case 'delete':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error_message'] = "ID do posto não fornecido.";
            break;
        }
        $success = $postoRepo->delete($id);
        $_SESSION[$success ? 'success_message' : 'error_message'] = $success ? "Posto apagado com sucesso!" : "Erro ao apagar o posto.";
        break;

    default:
        $_SESSION['error_message'] = "Ação desconhecida.";
        break;
}

header("Location: $redirect_url");
exit;
