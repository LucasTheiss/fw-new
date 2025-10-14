<?php
require_once '../autoload.php';
require_once 'acesso.php'; // Verificação de sessão

use src\Repository\UsuarioRepository;

$usuarioRepo = new UsuarioRepository();
$integrantes = $usuarioRepo->findMotoristasByTransportadora($idtransportadora);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Integrantes - Gerente</title>
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
                    <h1>Gestão de Integrantes</h1>
                    <button onclick="openAddModal()" class="btn primary">Adicionar Novo</button>
                </div>
                <p>Gerencie os motoristas da sua transportadora.</p>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>CPF</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($integrantes)): ?>
                            <tr>
                                <td colspan="5">Nenhum integrante encontrado.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($integrantes as $integrante): ?>
                            <tr>
                                <td data-label="Nome"><?= htmlspecialchars($integrante['nome']) ?></td>
                                <td data-label="Email"><?= htmlspecialchars($integrante['email']) ?></td>
                                <td data-label="Telefone"><?= htmlspecialchars($integrante['telefone']) ?></td>
                                <td data-label="CPF"><?= htmlspecialchars($integrante['cpf']) ?></td>
                                <td data-label="Ações">
                                    <div class="actions">
                                        <a href="#" class="btn-icon btn-view" title="Editar">✏️</a>
                                        <a href="#" class="btn-icon btn-deny" title="Excluir">🗑️</a>
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

    <div id="addIntegranteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Adicionar Novo Integrante</h2>
                <span class="close" onclick="closeModal('addIntegranteModal')">&times;</span>
            </div>
            <form action="actions/integrantes_actions.php" method="POST">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha Provisória</label>
                    <input type="password" id="senha" name="senha" required>
                    <p class="hint">O motorista deverá alterar esta senha no primeiro acesso.</p>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" class="btn" onclick="closeModal('addIntegranteModal')">Cancelar</button>
                    <button type="submit" class="btn primary">Salvar Integrante</button>
                </div>
            </form>
        </div>
    </div>

<script>
    // Funções para controlar o modal
    function openAddModal() {
        document.getElementById('addIntegranteModal').style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Fecha o modal se o usuário clicar fora dele
    window.onclick = function(event) {
        const modal = document.getElementById('addIntegranteModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>