<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Login - FuelWise</title>
    <?php include 'elements/head.html' ?>
<body>
    <?php include 'elements/header.php'; ?>

    <main class="auth-container">
        <div class="card" style="max-width: 450px;">
            <div class="card-header">
                <h1>Aceda à sua conta</h1>
                <p>Digite suas credenciais para continuar.</p>
            </div>
            <form class="card-body" action="actions/auth_actions.php" method="POST">
                <?php include 'elements/alert.php'; ?>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input id="email" name="email" type="email" placeholder="seu@email.com" required autocomplete="email" />
                    <p class="hint hidden"></p>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input id="senha" name="senha" type="password" placeholder="********" required />
                    <p class="hint hidden"></p>
                </div>
                <button class="btn primary" style="width: 100%;" type="submit" onclick="validarLogin(event, this)">Entrar</button>
            </form>
            <div class="card-footer">
                Não tem uma conta? <a href="cadastro_transportadora.php">Registe a sua transportadora</a>.
            </div>
        </div>
    </main>

    <script src='../js/login.js'></script>
</body>
</html>