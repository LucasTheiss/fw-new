<?php
require_once '../autoload.php';
require_once 'acesso.php'; // Verificação de sessão

use src\Repository\VeiculoRepository;

$veiculoRepo = new VeiculoRepository();
// Supondo que você tenha um método para buscar veículos por transportadora
$veiculos = $veiculoRepo->findByTransportadora($idtransportadora); 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Veículos - Gerente</title>
    <?php include_once("../elements/head.html") ?>
</head>
<body>
    <div class="sidebar">
        <?php include('../elements/sidebar.php') ?>
    </div>

    <div class="main">
        <?php include('../elements/header.php') ?>
        <div class="content">
            <?php include('../elements/alert.php') ?>
            <div class="page-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h1>Gestão de Veículos</h1>
                    <button onclick="openAddModal()" class="btn primary">Adicionar Novo</button>
                </div>
                <p>Gerencie os veículos da sua frota.</p>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Modelo</th>
                            <th>Marca</th>
                            <th>Ano</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($veiculos)): ?>
                            <tr>
                                <td colspan="5">Nenhum veículo encontrado.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($veiculos as $veiculo): ?>
                            <tr>
                                <td data-label="Placa"><?= htmlspecialchars($veiculo->placa) ?></td>
                                <td data-label="Modelo"><?= htmlspecialchars($veiculo->modelo) ?></td>
                                <td data-label="Marca"><?= htmlspecialchars($veiculo->marca) ?></td>
                                <td data-label="Ano"><?= htmlspecialchars($veiculo->ano) ?></td>
                                <td data-label="Ações">
                                    <div class="actions">
                                        <button class="btn-icon btn-view" title="Editar">✏️</button>
                                        <button class="btn-icon btn-deny" title="Excluir">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="addVeiculoModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Adicionar Novo Veículo</h2>
                <span class="close" onclick="closeModal('addVeiculoModal')">&times;</span>
            </div>
            <form action="actions/veiculo_actions.php" method="POST">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label for="placa">Placa</label>
                    <input type="text" id="placa" name="placa" required>
                </div>
                <div class="form-group">
                    <label for="marca">Marca</label>
                    <input type="text" id="marca" name="marca" required>
                </div>
                 <div class="form-group">
                    <label for="modelo">Modelo</label>
                    <input type="text" id="modelo" name="modelo" required>
                </div>
                <div class="form-group">
                    <label for="ano">Ano</label>
                    <input type="number" id="ano" name="ano" required>
                </div>
                <div class="form-group">
                    <label for="capacidade_tanque">Capacidade do Tanque (Litros)</label>
                    <input type="number" id="capacidade_tanque" name="capacidade_tanque" required>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" class="btn" onclick="closeModal('addVeiculoModal')">Cancelar</button>
                    <button type="submit" class="btn primary">Salvar Veículo</button>
                </div>
            </form>
        </div>
    </div>

<script>
    function openAddModal() {
        document.getElementById('addVeiculoModal').style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    }
</script>
</body>
</html>