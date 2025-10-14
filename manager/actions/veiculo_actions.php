<?php
require_once '../../autoload.php';
require_once '../acesso.php';

use src\Repository\VeiculoRepository;
use src\Model\Veiculo;

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$veiculoRepo = new VeiculoRepository();

switch ($action) {
    case 'create':
        if (empty($_POST['placa']) || empty($_POST['modelo']) || empty($_POST['eixos']) || empty($_POST['litragem'])) {
            $_SESSION['alert'] = ['title' => 'Atenção!', 'text' => 'Todos os campos obrigatórios devem ser preenchidos.', 'icon' => 'warning'];
            break;
        }
        $veiculo = new Veiculo();
        $veiculo->idtransportadora = $idtransportadora;
        $veiculo->placa = $_POST['placa'];
        $veiculo->modelo = $_POST['modelo'];
        $veiculo->eixos = (int)$_POST['eixos'];
        $veiculo->litragem = (float)$_POST['litragem'];
        $veiculo->observacao = $_POST['observacao'];

        if ($veiculoRepo->create($veiculo)) {
            $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Veículo cadastrado.', 'icon' => 'success'];
        } else {
            $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'Não foi possível cadastrar o veículo.', 'icon' => 'error'];
        }
        break;

    case 'update':
        if (empty($_POST['idveiculo']) || empty($_POST['placa']) || empty($_POST['modelo'])) {
            $_SESSION['alert'] = ['title' => 'Atenção!', 'text' => 'Campos essenciais estão faltando.', 'icon' => 'warning'];
            break;
        }
        $veiculo = new Veiculo();
        $veiculo->idveiculo = (int)$_POST['idveiculo'];
        $veiculo->idtransportadora = $idtransportadora;
        $veiculo->placa = $_POST['placa'];
        $veiculo->modelo = $_POST['modelo'];
        $veiculo->eixos = (int)$_POST['eixos'];
        $veiculo->litragem = (float)$_POST['litragem'];
        $veiculo->observacao = $_POST['observacao'];

        if ($veiculoRepo->update($veiculo)) {
            $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Veículo atualizado.', 'icon' => 'success'];
        } else {
            $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'Não foi possível atualizar o veículo.', 'icon' => 'error'];
        }
        break;

    case 'delete':
        $idveiculo = (int)($_GET['id'] ?? 0);
        if ($idveiculo <= 0) {
            $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'ID de veículo inválido.', 'icon' => 'error'];
            break;
        }
        if ($veiculoRepo->delete($idveiculo, $idtransportadora)) {
            $_SESSION['alert'] = ['title' => 'Sucesso!', 'text' => 'Veículo excluído.', 'icon' => 'success'];
        } else {
            $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'Não foi possível excluir o veículo.', 'icon' => 'error'];
        }
        break;
}

header('Location: ../veiculos.php');
exit;