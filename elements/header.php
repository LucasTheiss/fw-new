<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$baseUrl = "/FW"; 
?>

<header class="header">
    <a href="<?= $baseUrl ?>/index.php" class="header-logo">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.77 7.23L19.78 7.22L16.06 3.5L15 4.56L17.11 6.67C16.17 7.03 15.5 7.93 15.5 9C15.5 10.38 16.62 11.5 18 11.5C18.36 11.5 18.69 11.42 19 11.29V18.5C19 19.05 18.55 19.5 18 19.5C17.45 19.5 17 19.05 17 18.5V14C17 12.9 16.1 12 15 12H14V5C14 3.9 13.1 3 12 3H6C4.9 3 4 3.9 4 5V21H14V13.5H15.5V18.5C15.5 19.88 16.62 21 18 21C19.38 21 20.5 19.88 20.5 18.5V9C20.5 8.31 20.22 7.68 19.77 7.23ZM12 13.5V19H6V12H12V13.5ZM12 10H6V5H12V10ZM18 10C17.45 10 17 9.55 17 9C17 8.45 17.45 8 18 8C18.55 8 19 8.45 19 9C19 9.55 18.55 10 18 10Z"/>
        </svg>
        <span class="logo-text">Fuel<span class="logo-highlight">Wise</span></span>
    </a>

    <nav class="header-nav" id="main-nav">
        <ul>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li><a href="<?= $baseUrl ?>/index.php">Início</a></li>
                <li><a href="<?= $baseUrl ?>/cadastro_transportadora.php">Cadastrar Transportadora</a></li>
                <li><a href="<?= $baseUrl ?>/login.php" class="btn primary">Login</a></li>

            <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'motorista'): ?>
                <li><a href="<?= $baseUrl ?>/motorista/index.php">Início</a></li>
                <li><a href="<?= $baseUrl ?>/motorista/postos.php">Checar Postos</a></li>
                <li><a href="<?= $baseUrl ?>/suporte.php">Suporte</a></li>
                <li><a href="<?= $baseUrl ?>/logout.php">Sair</a></li>

            <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'gerente'): ?>
                <li><a href="<?= $baseUrl ?>/gerente/index.php">Dashboard</a></li>
                <li><a href="<?= $baseUrl ?>/gerente/integrantes.php">Integrantes</a></li>
                <li><a href="<?= $baseUrl ?>/gerente/veiculos.php">Veículos</a></li>
                <li><a href="<?= $baseUrl ?>/gerente/viagens.php">Viagens</a></li>
                <li><a href="<?= $baseUrl ?>/logout.php">Sair</a></li>

            <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <li><a href="<?= $baseUrl ?>/admin/index.php">Dashboard</a></li>
                <li><a href="<?= $baseUrl ?>/admin/solicitacoes.php">Solicitações</a></li>
                <li><a href="<?= $baseUrl ?>/admin/postos.php">Postos</a></li>
                <li><a href="<?= $baseUrl ?>/admin/denuncias.php">Denúncias</a></li>
                <li><a href="<?= $baseUrl ?>/logout.php">Sair</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION["user_name"])): ?>
                <li class="nav-user-info"><?= htmlspecialchars($_SESSION["user_name"]); ?></li>
            <?php endif; ?>
        </ul>
    </nav>

    <button class="header-toggle" id="mobile-menu-toggle" aria-controls="main-nav" aria-expanded="false">
        <span class="header-toggle-bar"></span>
        <span class="header-toggle-bar"></span>
        <span class="header-toggle-bar"></span>
    </button>
</header>