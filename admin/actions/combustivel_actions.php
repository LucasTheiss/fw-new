<?php
session_start();
require_once __DIR__ . '/../../autoload.php';
require_once '../acesso.php';

use src\Model\Combustivel;
use src\Repository\PostoRepository;

if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    header('Location: ../../index.php');
    exit;
}

$repository = new PostoRepository();
$action = $_REQUEST['action'] ?? '';

function setAlert($title, $text, $icon) {
    $_SESSION['alert'] = [
        'title' => $title, 'text' => $text, 'icon' => $icon, 'confirmButtonColor' => '#2563eb'
    ];
}

switch ($action) {
    case 'create':
        $combustivel = new Combustivel();
        $combustivel->idposto = $_POST['idposto'];
        $combustivel->preco = $_POST['precoCombustivel'];
        $combustivel->tipo = $_POST['tipoCombustivel'];

        if ($repository->saveCombustivel($combustivel)) {
            setAlert('Sucesso!', 'Combustível salvo com sucesso.', 'success');
        } else {
            setAlert('Erro!', 'Algo deu errado ao salvar o combustível.', 'error');
        }
        break;

    case 'update_price':
        $idcombustivel = (int)$_GET['idcombustivel'];
        $preco = (float)$_GET['precoCombustivel'];

        if ($repository->updateCombustivelPreco($idcombustivel, $preco)) {
            setAlert('Sucesso!', 'Preço do combustível alterado com sucesso.', 'success');
        } else {
            setAlert('Erro!', 'Algo deu errado ao alterar o preço.', 'error');
        }
        break;

    case 'delete':
        $idcombustivel = (int)$_GET['idcombustivel'];
        if ($repository->deleteCombustivel($idcombustivel)) {
            setAlert('Sucesso!', 'Combustível deletado com sucesso.', 'success');
        } else {
            setAlert('Erro!', 'Algo deu errado ao deletar o combustível.', 'error');
        }
        break;

    default:
        setAlert('Erro!', 'Ação desconhecida.', 'error');
        break;
}

header('Location: ../postos.php');
exit;