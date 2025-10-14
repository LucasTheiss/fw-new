<?php
session_start();
require_once __DIR__ . '/../../autoload.php';

use src\Repository\DenunciaRepository;

if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    header('Location: ../../index.php');
    exit;
}

$repository = new DenunciaRepository();
$action = $_REQUEST['action'] ?? '';

function setAlert($title, $text, $icon) {
    $_SESSION['alert'] = [
        'title' => $title, 'text' => $text, 'icon' => $icon, 'confirmButtonColor' => '#2563eb'
    ];
}

switch ($action) {
    case 'delete':
        $iddenuncia = (int)$_GET['iddenuncia'];
        if ($repository->delete($iddenuncia)) {
            setAlert('Sucesso!', 'Denúncia deletada com sucesso.', 'success');
        } else {
            setAlert('Erro!', 'Algo deu errado ao deletar a denúncia.', 'error');
        }
        break;
        
    default:
        setAlert('Erro!', 'Ação desconhecida.', 'error');
        break;
}

header('Location: ../denuncias.php');
exit;