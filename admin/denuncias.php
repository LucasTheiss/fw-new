<?php
session_start();
require_once __DIR__ . '/../autoload.php';

use src\Repository\DenunciaRepository;

if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    header('Location: ../index.php');
    exit;
}

$repository = new DenunciaRepository();
$denuncias = $repository->findAllWithDetails();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../../css/base.css" rel="stylesheet">
    <link href="../../css/components.css" rel="stylesheet">
    <title>Denuncias</title>
</head>
<body>
    <div class="content">
        <div class="table-wrapper">
            <table aria-label="Tabela de denúncias">
                <tbody id="tableBody"></tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const denuncias = <?php echo json_encode($denuncias); ?>;
        
        function excluirDenuncia(id) {
            Swal.fire({
                title: 'Excluir denúncia',
                text: 'Você tem certeza que deseja excluir essa denúncia?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Não, cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `actions/denuncia_actions.php?action=delete&iddenuncia=${id}`;
                }
            });
        }
        // ... resto do seu JS (renderTable, abrirModal, etc.) ...
    </script>
</body>
</html>