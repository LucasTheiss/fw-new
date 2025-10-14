<?php
session_start();
require_once __DIR__ . '/../autoload.php';

use src\Repository\SolicitacaoRepository;

if (!($_SESSION['role'] == 3)) {
    header('Location: ../index.php');
    exit;
}

$repository = new SolicitacaoRepository();
$requests = $repository->findAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <title>Solicitações</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../../css/solicitacoes_v2.css" rel="stylesheet">
  <link href="../../css/index.css" rel="stylesheet">
  <link href="../../css/header.css" rel="stylesheet">
  <link href="../../css/sidebar.css" rel="stylesheet">
  <link href="../../css/footer.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <?php
            include('../../elements/sidebar.php');
            include('../../elements/alert.php');
        ?>
    </div>
    <div class="main">
        <?php
            include('../../elements/header.php');
        ?>
        <div class="content">
            <header class="page-header">
                <h1>Solicitações de Cadastro</h1>
                <p>Revise e aprove ou negue as solicitações de cadastro de transportadoras.</p>
            </header>
            <div class="search-wrapper">
                <div class="search-container">
                    <svg class="icon" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                    id="searchInput"
                    type="search"
                    placeholder="Filtrar por nome de empresa ou gerente..."
                    class="search-input"
                    aria-label="Search"
                    />
                </div>
            </div>

            <div class="table-wrapper">
                <table aria-label="Registration requests table">
                <thead>
                    <tr>
                    <th>Empresa</th>
                    <th>Gerente</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th class="w-120px" style="">Ações</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modal" class="modal">...</div>


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const requests = <?php echo json_encode($requests); ?>;
    const icons = {
      eye: `<svg class="icon" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="3"></circle><path d="M2 12c2-4 6-7 10-7s8 3 10 7c-2 4-6 7-10 7s-8-3-10-7z"></path></svg>`,
      check: `<svg class="icon" viewBox="0 0 24 24" stroke="currentColor"><polyline points="20 6 9 17 4 12"></polyline></svg>`,
      x: `<svg class="icon" viewBox="0 0 24 24" stroke="currentColor"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`
    };
    
    // As funções JS de abrir modal, getStatusClass e renderTable continuam as mesmas.
    // Apenas as funções de negar e aprovar são atualizadas.

    function negarSolicitacao(idsolicitacao){
        Swal.fire({
            title: "Tem certeza?",
            text: "Você não será capaz de tornar uma solicitação pendente de novo! Caso a solicitação tenha sido aprovada, deletará todos registros da mesma.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Sim, tenho certeza!"
        }).then((result) => {
            if (result.isConfirmed){
                // ATUALIZADO: Aponta para o novo script de actions
                window.location.href = `actions/solicitacao_actions.php?action=deny&id=${idsolicitacao}`;
            }
        });
    }

    function aprovarSolicitacao(idsolicitacao){
        Swal.fire({
            title: "Tem certeza?",
            text: "Você não será capaz de tornar uma solicitação pendente de novo!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Cancelar",
            confirmButtonText: "Sim, tenho certeza!"
        }).then((result) => {
            if (result.isConfirmed){
                // ATUALIZADO: Aponta para o novo script de actions
                window.location.href = `actions/solicitacao_actions.php?action=approve&id=${idsolicitacao}`;
            }
        });
    }

    // O resto do seu JavaScript (renderTable, busca, etc.) permanece o mesmo.
    function renderTable(filtered) { /* ...código idêntico ao original... */ }
    function abrirModal(request) { /* ...código idêntico ao original... */ }
    function fecharModal() { /* ...código idêntico ao original... */ }
    function getStatusClass(status) { /* ...código idêntico ao original... */ }

    renderTable(requests);
	
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', () => {
      const term = searchInput.value.toLowerCase();
      const filtered = requests.filter(r =>
        r.nomeTransportadora.toLowerCase().includes(term) ||
        r.nomeUsuario.toLowerCase().includes(term) ||
        r.emailUsuario.toLowerCase().includes(term)
      );
      renderTable(filtered);
    });
  </script>
</body>
</html>