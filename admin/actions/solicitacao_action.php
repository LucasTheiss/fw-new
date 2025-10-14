<?php
require_once '../../autoload.php';
require_once '../acesso.php';

use src\Repository\SolicitacaoRepository;

$action = $_GET['action'] ?? null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$action || $id <= 0) {
    header('Location: ../solicitacoes.php');
    exit;
}

$solicitacaoRepo = new SolicitacaoRepository();
$success = false;

if ($action === 'approve') {
    $success = $solicitacaoRepo->approve($id);
    $_SESSION['alert'] = [
        'title' => $success ? 'Sucesso!' : 'Erro!',
        'text' => $success ? 'Solicitação aprovada com sucesso.' : 'Falha ao aprovar a solicitação.',
        'icon' => $success ? 'success' : 'error',
    ];
} elseif ($action === 'deny') {
    $success = $solicitacaoRepo->deny($id);
    $_SESSION['alert'] = [
        'title' => $success ? 'Sucesso!' : 'Erro!',
        'text' => $success ? 'Solicitação negada com sucesso.' : 'Falha ao negar a solicitação.',
        'icon' => $success ? 'success' : 'error',
    ];
}

header('Location: ../solicitacoes.php');
exit;
