<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Registo de Transportadora - FuelWise</title>
    <?php include 'elements/head.html'; ?>
</head>
<body>
    <?php include 'elements/header.php'; ?>
    <div class="container">
        <div class="card" style="max-width: 700px; margin: 3rem auto;">
            <div class="card-header">
                <h1>Registo de Nova Transportadora</h1>
                <p>Preencha os dados abaixo. O seu registo será submetido para aprovação.</p>
            </div>
            <div class="card-body">
                <?php include 'elements/alert.php'; ?>
                <form action="actions/auth_actions.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="register">
                    
                    <h2>Dados da Transportadora</h2>
                    <div class="form-group">
                        <label for="razao_social">Razão Social</label>
                        <input type="text" id="razao_social" name="transportadora[razao_social]" required>
                    </div>
                    <div class="form-group">
                        <label for="nome_fantasia">Nome Fantasia</label>
                        <input type="text" id="nome_fantasia" name="transportadora[nome_fantasia]" required>
                    </div>
                    <div class="form-group">
                        <label for="cnpj">CNPJ</label>
                        <input type="text" id="cnpj" name="transportadora[cnpj]" required>
                    </div>

                    <h2 style="margin-top: 2rem;">Dados do Gerente da Conta</h2>
                    <div class="form-group">
                        <label for="gerente_nome">Nome Completo</label>
                        <input type="text" id="gerente_nome" name="gerente[nome]" required>
                    </div>
                    <div class="form-group">
                        <label for="gerente_email">E-mail</label>
                        <input type="email" id="gerente_email" name="gerente[email]" required>
                    </div>
                    <div class="form-group">
                        <label for="gerente_senha">Senha</label>
                        <input type="password" id="gerente_senha" name="gerente[senha]" required>
                    </div>

                    <h2 style="margin-top: 2rem;">Anexos</h2>
                    <div class="form-group">
                        <label for="anexos">Documentos (Contrato Social, etc.)</label>
                        <input type="file" id="anexos" name="anexos[]" multiple required>
                    </div>

                    <button type="submit" class="btn primary" style="width: 100%;">Enviar para Aprovação</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
