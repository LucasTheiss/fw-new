<?php
require_once '../../autoload.php';
require_once '../acesso.php';

use src\Service\SolicitacaoService;

$action = $_GET['action'] ?? null;
$id = (int)($_GET['id'] ?? 0);

if (!$action || $id <= 0) {
    header('Location: ../solicitacoes.php');
    exit;
}

$service = new SolicitacaoService();

try {
    if ($action === 'approve') {
        $service->aprovar($id);
        $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Solicitação aprovada.', 'icon' => 'success'];
    } elseif ($action === 'deny') {
        $service->negar($id);
        $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Solicitação negada/revogada.', 'icon' => 'success'];
    }
} catch (Exception $e) {
    $_SESSION['alert'] = ['title' => 'Erro!', 'text' => $e->getMessage(), 'icon' => 'error'];
}

header('Location: ../solicitacoes.php');
exit;