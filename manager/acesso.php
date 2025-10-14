<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'gerente') {
    header('Location: ../login.php');
    exit;
}

$idtransportadora = $_SESSION['id_transportadora'] ?? 0;

if ($idtransportadora <= 0) {
    $_SESSION['error_message'] = "Sessão inválida. ID da transportadora não encontrado.";
    header('Location: ../login.php');
    exit;
}
?>