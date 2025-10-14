<?php
session_start();
require_once __DIR__ . '/../../autoload.php';

use src\Model\Posto;
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

// VALIDAÇÃO DE POSTO
function validatePosto(&$posto) {
    if (strlen($posto->nome) < 2 || strlen($posto->nome) > 45) {
        setAlert('Erro!', 'O nome do posto deve ter entre 2 e 45 caracteres.', 'warning');
        return false;
    }
    if (strlen($posto->endereco) < 5 || strlen($posto->endereco) > 100) {
        setAlert('Erro!', 'Endereço inválido.', 'warning');
        return false;
    }
    if (!($posto->latitude <= 90 && $posto->latitude >= -90) || !($posto->longitude <= 180 && $posto->longitude >= -180)) {
        setAlert('Erro!', 'Insira coordenadas válidas.', 'warning');
        return false;
    }
    return true;
}


switch ($action) {
    case 'create':
        $posto = new Posto();
        $posto->nome = $_POST['nomePosto'];
        $posto->endereco = $_POST['enderecoPosto'];
        list($latitude, $longitude) = explode(", ", $_POST['coordenadasPosto']);
        $posto->latitude = (float)$latitude;
        $posto->longitude = (float)$longitude;

        if (validatePosto($posto) && $repository->save($posto)) {
            setAlert('Sucesso!', 'Posto adicionado com sucesso.', 'success');
        } else if (!isset($_SESSION['alert'])) {
            setAlert('Erro!', 'Algo deu errado ao salvar o posto.', 'error');
        }
        break;

    case 'update':
        $posto = new Posto();
        $posto->idposto = $_POST['idposto'];
        $posto->nome = $_POST['nomePosto'];
        $posto->endereco = $_POST['enderecoPosto'];
        list($latitude, $longitude) = explode(", ", $_POST['coordenadasPosto']);
        $posto->latitude = (float)$latitude;
        $posto->longitude = (float)$longitude;
        
        if (validatePosto($posto) && $repository->update($posto)) {
            setAlert('Sucesso!', 'Posto alterado com sucesso.', 'success');
        } else if (!isset($_SESSION['alert'])) {
            setAlert('Erro!', 'Algo deu errado ao alterar o posto.', 'error');
        }
        break;

    case 'delete':
        $idposto = (int)$_GET['id'];
        if ($repository->delete($idposto)) {
            setAlert('Sucesso!', 'Posto excluído com sucesso.', 'success');
        } else {
            setAlert('Erro!', 'Algo deu errado ao excluir o posto.', 'error');
        }
        break;
    
    default:
        setAlert('Erro!', 'Ação desconhecida.', 'error');
        break;
}

header('Location: ../postos.php');
exit;