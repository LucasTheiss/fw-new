<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Login - FuelWise</title>
    <?php include 'elements/head.html'; ?>
</head>
<body>
    <?php include 'elements/header.php'; ?>
    <div class="container">
        <div class="card" style="max-width: 450px; margin: 3rem auto;">
            <div class="card-header">
                <h1>Aceda à sua conta</h1>
            </div>
            <div class="card-body">
                <?php include 'elements/alert.php'; ?>
                <form action="actions/auth_actions.php" method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn primary" style="width: 100%;">Entrar</button>
                </form>
            </div>
            <div class="card-footer">
                Não tem uma conta? <a href="cadastro_transportadora.php">Registe a sua transportadora</a>.
            </div>
        </div>
    </div>
</body>
</html>
