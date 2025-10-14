<?php
session_start();
require_once __DIR__ . '/../autoload.php';

use src\Repository\PostoRepository;

if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    header('Location: ../index.php');
    exit;
}

$repository = new PostoRepository();
$postos = $repository->findAllWithCombustiveis();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<body>
    <div id="modalAdicionarPosto" class="modal">
        <div class="modal-content">
            <form method="POST" action="actions/posto_actions.php">
                <input type="hidden" name="action" value="create">
                </form>
        </div>
    </div>

    <div id="modalEditarPosto" class="modal">
        <div class="modal-content">
            <form method="POST" action="actions/posto_actions.php">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="idposto">
                </form>
        </div>
    </div>

    <div id="modalAdicionarCombustivel" class="modal">
        <div class="modal-content">
            <form method="POST" action="actions/combustivel_actions.php">
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="idposto" id="idposto">
                </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const postosData = requests.reduce((acc, posto) => {
            acc[posto.idposto] = posto;
            return acc;
        }, {});


        function salvarCombustivel(idcombustivel) {
            const precoInput = document.getElementById(`preco-${idcombustivel}`);
            const novoPreco = parseFloat(precoInput.value).toFixed(2);
            // ... sua validação ...
            window.location.href = `actions/combustivel_actions.php?action=update_price&idcombustivel=${idcombustivel}&precoCombustivel=${novoPreco}`;
        }

        function excluirCombustivel(idcombustivel) {
            // ... seu Swal ...
            window.location.href = `actions/combustivel_actions.php?action=delete&idcombustivel=${idcombustivel}`;
        }

        function excluirPosto(idposto) {
            // ... seu Swal ...
            window.location.href = `actions/posto_actions.php?action=delete&id=${idposto}`;
        }

        // A função renderTable precisa ser ajustada para usar a variável 'postosData'
        function renderTable(filtered) {
            // ...
            document.querySelectorAll(".btn-edit").forEach(el => {
                const idposto = el.getAttribute("data-idposto");
                el.addEventListener("click", () => {
                    abrirModalEditarPosto(postosData[idposto]); // Usar o objeto 'postosData'
                });
            });

            document.querySelectorAll(".btn-view").forEach(el => {
                const idposto = el.getAttribute("data-idposto");
                el.addEventListener("click", () => {
                    abrirModalCombustiveis(postosData[idposto].combustiveis, idposto); // Usar o objeto 'postosData'
                });
            });
        }
        
        // Na busca, filtre o array 'requests' e passe para renderTable
        searchInput.addEventListener('input', () => {
            const term = searchInput.value.toLowerCase();
            const filtered = requests.filter(r =>
                r.nome.toLowerCase().includes(term) ||
                r.endereco.toLowerCase().includes(term)
            );
            renderTable(filtered); // A função renderTable já espera um array
        });

        // Chamada inicial
        renderTable(requests);
    </script>
</body>
</html>