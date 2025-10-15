<?php
require_once '../autoload.php';
require_once 'acesso.php';

use src\Repository\UsuarioRepository;

$usuarioRepo = new UsuarioRepository();
$integrantes = $usuarioRepo->findIntegrantesByTransportadora($idtransportadora);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Integrantes - Gerente</title>
    <?php include_once("../elements/head.html") ?>
    <style>
        .table-form-row {
            display: flex;
            align-items: center;
        }
        .table-form-row input {
            flex-grow: 1;
            margin-right: 5px; 
        }
        .table-form-row .actions {
            white-space: nowrap;
        }
    </style>
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
                    <h1>Gestão de Integrantes</h1>
                    <button onclick="openAddModal()" class="btn primary">Adicionar Novo</button>
                </div>
                <p>Gerencie os motoristas da sua transportadora. Edite os campos diretamente na tabela e salve.</p>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 25%;">Nome</th>
                            <th style="width: 25%;">Email</th>
                            <th style="width: 15%;">Telefone</th>
                            <th style="width: 15%;">CPF</th>
                            <th style="width: 20%;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($integrantes)): ?>
                            <tr><td colspan="5">Nenhum integrante encontrado.</td></tr>
                        <?php else: ?>
                            <?php foreach ($integrantes as $integrante): ?>
                                <tr>
                                    <form action="actions/integrantes_actions.php" method="POST">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="idusuario" value="<?= $integrante['idusuario'] ?>">
                                        
                                        <td data-label="Nome">
                                            <input type="text" name="nome" value="<?= htmlspecialchars($integrante['nome']) ?>" class="form-control" required>
                                        </td>
                                        <td data-label="Email">
                                            <input type="email" name="email" value="<?= htmlspecialchars($integrante['email']) ?>" class="form-control" required>
                                        </td>
                                        <td data-label="Telefone">
                                            <input type="text" name="telefone" value="<?= htmlspecialchars($integrante['telefone']) ?>" class="form-control">
                                        </td>
                                        <td data-label="CPF">
                                            <input type="text" name="cpf" value="<?= htmlspecialchars($integrante['cpf']) ?>" class="form-control" required>
                                        </td>
                                        <td data-label="Ações">
                                            <div class="actions">
                                                <button type="submit" class="btn-icon btn-save" title="Salvar Alterações">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2563eb" viewBox="0 0 24 24">
                                                        <path d="M17 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7l-2-4zm-1 16h-8v-4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v4zm-1-10H9V5h6v4z"/>
                                                    </svg>
                                                </button>
                                                <a href="actions/integrantes_actions.php?action=delete&id=<?= $integrante['idusuario'] ?>" class="btn-icon btn-deny" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este integrante?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </a>
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

    <div id="addIntegranteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Adicionar Novo Integrante</h2>
                <span class="close" onclick="closeModal('addIntegranteModal')">&times;</span>
            </div>
            <form action="actions/integrante_actions.php" method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group"><label for="nome">Nome Completo</label><input type="text" id="nome" name="nome" required></div>
                <div class="form-group"><label for="email">E-mail</label><input type="email" id="email" name="email" required></div>
                <div class="form-group"><label for="cpf">CPF</label><input type="text" id="cpf" name="cpf" required></div>
                <div class="form-group"><label for="telefone">Telefone</label><input type="text" id="telefone" name="telefone" required></div>
                <div class="form-group"><label for="senha">Senha Provisória</label><input type="password" id="senha" name="senha" required><p class="hint">O motorista deverá alterar esta senha no primeiro acesso.</p></div>
                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" class="btn" onclick="closeModal('addIntegranteModal')">Cancelar</button>
                    <button type="submit" class="btn primary">Salvar Integrante</button>
                </div>
            </form>
        </div>
    </div>

<script>
    function openAddModal() {
        document.getElementById('addIntegranteModal').style.display = 'block';
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