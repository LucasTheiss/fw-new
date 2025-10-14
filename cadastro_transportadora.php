<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include 'elements/head.html' ?>
    <title>Registo - FuelWise</title>
</head>
<body>
    <?php
        include('elements/header.php');
    ?>

    <main class="auth-container">
        <div class="card" style="max-width: 768px;">
            <div class="card-header">
                <h1>Cadastre sua Empresa</h1>
                <p>Preencha as seções para solicitar sua conta.</p>
            </div>

            <form class="form" method="POST" action="actions/auth_actions.php">
                <input type="hidden" name="action" value="register">
                <div class="card-body">
                    <?php include('elements/alert.php'); ?>
                    <div class="tabs">
                        <button class="tab-btn active" type="button" data-tab="empresa">1. Informações da Empresa</button>
                        <button class="tab-btn" type="button" data-tab="pessoal">2. Informações Pessoais</button>
                    </div>

                    <div class="tab-content" id="empresa">
                        <div class="form-group">
                            <label>Nome da Empresa</label>
                            <input placeholder="Nome da empresa" name="nomeEmpresa" value="<?= htmlspecialchars($_SESSION['form_data']['nomeEmpresa'] ?? '') ?>" />
                            <p class="hint hidden"></p>
                        </div>
                        <div class="form-group">
                            <label>Logradouro</label>
                            <input placeholder="Ex: Av. Brasil, 123" name="endereco" value="<?= htmlspecialchars($_SESSION['form_data']['endereco'] ?? '') ?>"/>
                            <p class="hint hidden"></p>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>CEP</label>
                                <input placeholder="01000-000" name="cep" value="<?= htmlspecialchars($_SESSION['form_data']['cep'] ?? '') ?>" />
                                <p class="hint hidden"></p>
                            </div>
                            <div class="form-group">
                                <label>Cidade</label>
                                <input placeholder="São Paulo" name="cidade" value="<?= htmlspecialchars($_SESSION['form_data']['cidade'] ?? '') ?>" />
                                <p class="hint hidden"></p>
                            </div>
                            <div class="form-group">
                                <label>Estado (UF)</label>
                                <input placeholder="SP" name="estado" maxlength="2" value="<?= htmlspecialchars($_SESSION['form_data']['estado'] ?? '') ?>" />
                                <p class="hint hidden"></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Telefone da Empresa</label>
                                <input placeholder="(11) 98765-4321" name="telefoneEmpresa" value="<?= htmlspecialchars($_SESSION['form_data']['telefoneEmpresa'] ?? '') ?>" />
                                <p class="hint hidden"></p>
                            </div>
                            <div class="form-group">
                                <label>CNPJ</label>
                                <input placeholder="12.345.678/0001-99" name="cnpj" value="<?= htmlspecialchars($_SESSION['form_data']['cnpj'] ?? '') ?>" />
                                <p class="hint">Somente números.</p>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <button class="btn primary" type="button" onclick="alterarAba('empresa', 'pessoal')">Continuar</button>
                        </div>
                    </div>

                    <div class="tab-content hidden" id="pessoal">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nome</label>
                                <input placeholder="João" name="nomePessoal" value="<?= htmlspecialchars($_SESSION['form_data']['nomePessoal'] ?? '') ?>" />
                                <p class="hint hidden"></p>
                            </div>
                            <div class="form-group">
                                <label>Sobrenome</label>
                                <input placeholder="Silva" name="sobrenome" value="<?= htmlspecialchars($_SESSION['form_data']['sobrenome'] ?? '') ?>" />
                                <p class="hint hidden"></p>
                            </div>
                        </div>
                        <div class="form-row">
                             <div class="form-group">
                                <label>CPF</label>
                                <input type="text" placeholder="123.456.789-12" name="cpf" value="<?= htmlspecialchars($_SESSION['form_data']['cpf'] ?? '') ?>" />
                                <p class="hint hidden"></p>
                            </div>
                            <div class="form-group">
                                <label>E-mail</label>
                                <input type="email" placeholder="joao.silva@exemplo.com" name="email" value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>" />
                                <p class="hint">Este será seu usuário para login.</p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Senha</label>
                                <input type="password" placeholder="********" name="senha" />
                                <p class="hint">A senha deve ter no mínimo 8 caracteres e um especial.</p>
                            </div>
                            <div class="form-group">
                                <label>Confirmar Senha</label>
                                <input type="password" placeholder="********" name="confirmarSenha" />
                                <p class="hint hidden"></p>
                            </div>
                        </div>
                         <div class="form-group">
                            <label>Telefone Pessoal</label>
                            <input placeholder="(11) 91234-5678" name="telefonePessoal" value="<?= htmlspecialchars($_SESSION['form_data']['telefonePessoal'] ?? '') ?>" />
                             <p class="hint hidden"></p>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <button type="button" class="btn" onclick="alterarAba('pessoal', 'empresa', false)">Voltar</button>
                            <button type="submit" class="btn primary" onclick="validarFormularioCompleto(event, this)">Enviar Cadastro</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card-footer">
                Já tem uma conta? <a href="login.php">Faça login aqui</a>.
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/cadastro.js"></script>
</body>
</html>