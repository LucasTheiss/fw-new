<?php
session_start();
require_once __DIR__ . '/../autoload.php';

use src\Repository\DashboardRepository;

if (!isset($_SESSION['adm']) || $_SESSION['adm'] != 1) {
    header('Location: ../index.php');
    exit;
}

$dashboardRepo = new DashboardRepository();

$stats = $dashboardRepo->getStats();
$dataGraficoUsuarios = $dashboardRepo->getMonthlyUserRegistrations();
$dataGraficoTransportadoras = $dashboardRepo->getMonthlyTransportadoraRegistrations();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Início</title>
    <?php include_once("../elements/head.html") ?>
</head>
<body>
    <div class="sidebar">
        <?php include('../elements/alert.php') ?>
        <?php include('../elements/sidebar.php') ?>
    </div>

    <div class="main">
        <?php include('../elements/header.php') ?>
        <div class="content">
            <header class="page-header">
                <h1>Início</h1>
                <p>Visualize e gerencie dados do aplicativo.</p>
            </header>

            <div class="dashboards">
                <div class="row">
                    <div class="dashboard">
                        <p>Veículos cadastrados                         
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#BBBBBB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="7" width="15" height="10" rx="2" ry="2"></rect>
                                <path d="M16 7h5l2 5v5h-7"></path>
                                <circle cx="5.5" cy="17.5" r="2"></circle>
                                <circle cx="18.5" cy="17.5" r="2"></circle>
                            </svg>
                        </p>
                        <h2><?php echo $stats['totalVeiculos']; ?></h2>
                    </div>
                    <div class="dashboard">
                        <p>Usuarios cadastrados                         
                            <svg width="24" height="24" fill="none" stroke="#BBBBBB" stroke-width="1.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5 21v-1a7 7 0 0114 0v1" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </p>
                        <h2><?php echo $stats['totalUsuarios']; ?></h2>
                    </div>
                    <div class="dashboard">
                        <p>Viagens cadastradas                         
                            <svg width="24" height="24" fill="none" stroke="#BBBBBB" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="9" r="2.5"/>
                            </svg>
                        </p>
                        <h2><?php echo $stats['totalViagens']; ?></h2>
                    </div>
                    <div class="dashboard">
                        <p>Denúncias pendentes                         
                            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#BBBBBB" stroke-width="1.8">
                                <path d="M12 9v4" stroke-linecap="round" />
                                <circle cx="12" cy="17" r="1" />
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            </svg>
                        </p>
                        <h2><?php echo $stats['totalDenuncias']; ?></h2>
                    </div>
                    <div class="dashboard">
                        <p>Transportadoras cadastradas                         
                            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#BBBBBB" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M3 12a9 9 0 0 1 15.5-6.36M21 12a9 9 0 0 1-15.5 6.36" />
                                <polyline points="3 7 3 3 7 3" />
                                <polyline points="21 17 21 21 17 21" />
                            </svg>
                        </p>
                        <h2><?php echo $stats['totalTransportadoras']; ?></h2>
                    </div>
                    <div class="dashboard">
                        <p>Postos Cadastrados                         
                            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#BBBBBB" stroke-width="1.8">
                            <path d="M3 3h10v18H3z" />
                            <path d="M14 8h2a2 2 0 0 1 2 2v5a2 2 0 0 0 2 2h1" />
                            <circle cx="7" cy="17" r="1" />
                            </svg>
                        </p>
                        <h2><?php echo $stats['totalPostos']; ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="row graficos">
                <div class="card">
                    <h2>Usuário cadastrados</h2>
                    <canvas id="graficoUsuarios"></canvas>
                </div>
                <div class="card">
                    <h2>Transportadoras cadastradas</h2>
                    <canvas id="graficoTransportadoras"></canvas>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const graficoUsuariosData = <?= json_encode($dataGraficoUsuarios) ?>;

  const ctxUsuarios = document.getElementById('graficoUsuarios').getContext('2d');
  const labelsUsuarios = graficoUsuariosData.map(item => item.mes);
  const dadosUsuarios = graficoUsuariosData.map(item => item.usuarios);

  new Chart(ctxUsuarios, {
    type: 'line',
    data: {
      labels: labelsUsuarios,
      datasets: [
        {
          label: 'Usuários Cadastrados',
          data: dadosUsuarios,
          borderColor: '#ff6384',
          backgroundColor: '#ff638455',
          tension: 0.3
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              return 'Usuários: ' + context.formattedValue;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0
          }
        }
      }
    }
  });
</script>

<script>
  const graficoTransportadorasData = <?= json_encode($dataGraficoTransportadoras) ?>;

  const ctxTransportadoras = document.getElementById('graficoTransportadoras')?.getContext('2d');

  if (ctxTransportadoras) {
    const labelsTransportadoras = graficoTransportadorasData.map(item => item.mes);
    const dadosTransportadoras = graficoTransportadorasData.map(item => item.quantidade);

    new Chart(ctxTransportadoras, {
      type: 'line',
      data: {
        labels: labelsTransportadoras,
        datasets: [{
          label: 'Transportadoras Criadas',
          data: dadosTransportadoras,
          borderColor: '#36a2eb',
          backgroundColor: '#36a2eb55',
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        plugins: {
          tooltip: {
            callbacks: {
              label: function(context) {
                return 'Transportadoras: ' + context.formattedValue;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        }
      }
    });
  }
</script>

</body>
</html>