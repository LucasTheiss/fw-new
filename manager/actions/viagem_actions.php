<?php
require_once '../../autoload.php';
require_once '../acesso.php';

use src\Model\Viagem;
use src\Repository\ViagemRepository;
use src\Service\ViagemService;

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$id = (int)($_GET['id'] ?? 0);

$viagemService = new ViagemService();

try {
    switch ($action) {
        case 'create':
            $viagem = new Viagem();
            $viagem->idusuario = (int)$_POST['idusuario'];
            $viagem->idveiculo = (int)$_POST['idveiculo'];
            $viagem->data_inicio = $_POST['data_inicio'];
            $viagem->endereco_origem = $_POST['endereco_origem'];
            $viagem->endereco_destino = $_POST['endereco_destino'];
            $viagem->carga = $_POST['carga'];
            $viagem->peso = (float)$_POST['peso'];
            $viagem->obs = $_POST['obs'];

            $repo = new ViagemRepository();
            if ($repo->create($viagem)) {
                $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Viagem agendada.', 'icon' => 'success'];
            } else {
                $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'Não foi possível agendar a viagem.', 'icon' => 'error'];
            }
            break;

        case 'iniciar':
            if ($id > 0) $viagemService->iniciar($id);
            $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Viagem iniciada.', 'icon' => 'success'];
            break;

        case 'finalizar':
            if ($id > 0) $viagemService->finalizar($id);
            $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Viagem finalizada.', 'icon' => 'success'];
            break;

        case 'cancelar':
            if ($id > 0) $viagemService->cancelar($id);
            $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Viagem cancelada.', 'icon' => 'success'];
            break;
    }
} catch (Exception $e) {
    $_SESSION['alert'] = ['title' => 'Erro de Operação!', 'text' => $e->getMessage(), 'icon' => 'error'];
}

header('Location: ../viagens.php');
exit;