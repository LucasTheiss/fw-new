<?php
require_once '../autoload.php';
require_once 'acesso.php'; // Inclui a verificação de sessão e permissão

use src\Repository\DashboardRepository;

// A variável $idtransportadora já está disponível a partir do acesso.php
$dashboardRepo = new DashboardRepository();
$stats = $dashboardRepo->getManagerStats($idtransportadora);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Dashboard - Gerente</title>
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
                <h1>Dashboard</h1>
                <p>Visão geral e estatísticas da sua transportadora.</p>
            </div>

            <div class="dashboards">
                <div class="row">
                    <div class="dashboard">
                        <p>Total de Veículos</p>
                        <h2><?= htmlspecialchars($stats['totalVeiculos'] ?? 0) ?></h2>
                    </div>
                    <div class="dashboard">
                        <p>Total de Motoristas</p>
                        <h2><?= htmlspecialchars($stats['totalMotoristas'] ?? 0) ?></h2>
                    </div>
                    <div class="dashboard">
                        <p>Total de Viagens</p>
                        <h2><?= htmlspecialchars($stats['totalViagens'] ?? 0) ?></h2>
                    </div>
                </div>
            </div>
            </div>
    </div>
</body>
</html>