<?php
session_start();
require_once __DIR__ . '/../../autoload.php';

use src\Repository\SolicitacaoRepository;

if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    header('Location: ../../index.php');
    exit;
}

$repository = new SolicitacaoRepository();
$action = $_REQUEST['action'] ?? '';
$id = (int)($_REQUEST['id'] ?? 0);
$result = ['success' => false, 'message' => 'Ação ou ID inválido.'];

function setAlert($title, $text, $icon) {
    $_SESSION['alert'] = [
        'title' => $title, 'text' => $text, 'icon' => $icon, 'confirmButtonColor' => '#2563eb'
    ];
}

if ($id > 0) {
    switch ($action) {
        case 'approve':
            $result = $repository->approve($id);
            break;
        case 'deny':
            $result = $repository->deny($id);
            break;
    }
}

if ($result['success']) {
    setAlert('Sucesso!', $result['message'], 'success');
} else {
    setAlert('Erro!', $result['message'], 'warning');
}

header('Location: ../solicitacoes.php');
exit;