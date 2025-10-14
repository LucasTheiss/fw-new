<?php
require_once '../autoload.php';
require_once 'acesso.php';

use src\Repository\VeiculoRepository;

$veiculoRepo = new VeiculoRepository();
$veiculos = $veiculoRepo->findByTransportadora($idtransportadora); 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Veículos - Gerente</title>
    <?php include_once("../elements/head.html") ?>
</head>
<body>
    <div class="sidebar"><?php include('../elements/sidebar.php') ?></div>
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
                            <th>Eixos</th>
                            <th>Litragem (L)</th>
                            <th>Observação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($veiculos)): ?>
                            <tr><td colspan="6">Nenhum veículo encontrado.</td></tr>
                        <?php else: ?>
                            <?php foreach ($veiculos as $veiculo): ?>
                                <tr>
                                    <form action="actions/veiculo_actions.php" method="POST">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="idveiculo" value="<?= $veiculo->idveiculo ?>">
                                        
                                        <td data-label="Placa"><input type="text" name="placa" value="<?= htmlspecialchars($veiculo->placa) ?>" required></td>
                                        <td data-label="Modelo"><input type="text" name="modelo" value="<?= htmlspecialchars($veiculo->modelo) ?>" required></td>
                                        <td data-label="Eixos"><input type="number" name="eixos" value="<?= htmlspecialchars($veiculo->eixos) ?>" required></td>
                                        <td data-label="Litragem"><input type="number" step="0.01" name="litragem" value="<?= htmlspecialchars($veiculo->litragem) ?>" required></td>
                                        <td data-label="Observação"><input type="text" name="observacao" value="<?= htmlspecialchars($veiculo->observacao ?? '') ?>"></td>
                                        <td data-label="Ações">
                                            <div class="actions">
                                                <button type="submit" class="btn-icon btn-approve" title="Salvar">&#10003;</button>
                                                <a href="actions/veiculo_actions.php?action=delete&id=<?= $veiculo->idveiculo ?>" class="btn-icon btn-deny" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este veículo?')">🗑️</a>
                                            </div>
                                        </td>
                                    </form>
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
                <div class="form-group"><label>Placa</label><input type="text" name="placa" required></div>
                <div class="form-group"><label>Modelo</label><input type="text" name="modelo" required></div>
                <div class="form-group"><label>Nº de Eixos</label><input type="number" name="eixos" required></div>
                <div class="form-group"><label>Litragem do Tanque</label><input type="number" step="0.01" name="litragem" required></div>
                <div class="form-group"><label>Observação</label><input type="text" name="observacao"></div>
                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" class="btn" onclick="closeModal('addVeiculoModal')">Cancelar</button>
                    <button type="submit" class="btn primary">Salvar Veículo</button>
                </div>
            </form>
        </div>
    </div>

<script>
    function openAddModal() { document.getElementById('addVeiculoModal').style.display = 'block'; }
    function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }
    window.onclick = function(event) { if (event.target.classList.contains('modal')) { event.target.style.display = "none"; } }
</script>
</body>
</html>