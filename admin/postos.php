<?php
require_once '../autoload.php';

use src\Repository\PostoRepository;

$postoRepo = new PostoRepository();
$postos = $postoRepo->findAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Gestão de Postos - Admin</title>
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
                <div class="search-wrapper">
                    <h1>Gestão de Postos</h1>
                    <button class="btn primary" onclick="openAddModal()">Adicionar Novo Posto</button>
                </div>
                <p>Adicione, edite ou remova postos de combustível.</p>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Endereço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($postos)): ?>
                            <tr>
                                <td colspan="4">Nenhum posto encontrado.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($postos as $posto): ?>
                            <tr>
                                <td data-label="Nome"><?= htmlspecialchars($posto['nome']) ?></td>
                                <td data-label="CNPJ"><?= htmlspecialchars($posto['cnpj']) ?></td>
                                <td data-label="Endereço"><?= htmlspecialchars($posto['endereco']) ?></td>
                                <td data-label="Ações">
                                    <div class="actions">
                                        <button class="btn-icon btn-view" onclick='openEditModal(<?= json_encode($posto) ?>)'>
                                            <!-- Icon Edit -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <a href="actions/posto_actions.php?action=delete&id=<?= $posto['idposto'] ?>" onclick="return confirm('Tem a certeza que deseja apagar este posto?')" class="btn-icon btn-deny">
                                            <!-- Icon Delete -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </a>
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

    <!-- Modal Adicionar Posto -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Adicionar Novo Posto</h2>
                <span class="close" onclick="closeModal('addModal')">&times;</span>
            </div>
            <form action="actions/posto_actions.php" method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label for="add_nome">Nome</label>
                    <input type="text" id="add_nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="add_cnpj">CNPJ</label>
                    <input type="text" id="add_cnpj" name="cnpj" required>
                </div>
                <div class="form-group">
                    <label for="add_endereco">Endereço</label>
                    <input type="text" id="add_endereco" name="endereco" required>
                </div>
                <button type="submit" class="btn primary">Adicionar</button>
            </form>
        </div>
    </div>

    <!-- Modal Editar Posto -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Editar Posto</h2>
                <span class="close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form action="actions/posto_actions.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" id="edit_id" name="id">
                <div class="form-group">
                    <label for="edit_nome">Nome</label>
                    <input type="text" id="edit_nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="edit_cnpj">CNPJ</label>
                    <input type="text" id="edit_cnpj" name="cnpj" required>
                </div>
                <div class="form-group">
                    <label for="edit_endereco">Endereço</label>
                    <input type="text" id="edit_endereco" name="endereco" required>
                </div>
                <button type="submit" class="btn primary">Guardar Alterações</button>
            </form>
        </div>
    </div>

<script>
    function openAddModal() {
        document.getElementById('addModal').style.display = 'block';
    }

    function openEditModal(posto) {
        document.getElementById('edit_id').value = posto.idposto;
        document.getElementById('edit_nome').value = posto.nome;
        document.getElementById('edit_cnpj').value = posto.cnpj;
        document.getElementById('edit_endereco').value = posto.endereco;
        document.getElementById('editModal').style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    window.onclick = function(event) {
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        if (event.target == addModal) {
            addModal.style.display = "none";
        }
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    }
</script>

</body>
</html>
