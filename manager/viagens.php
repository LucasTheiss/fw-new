<?php
require_once '../autoload.php';
require_once 'acesso.php';

use src\Repository\ViagemRepository;
use src\Repository\UsuarioRepository;
use src\Repository\VeiculoRepository;

$viagemRepo = new ViagemRepository();
$usuarioRepo = new UsuarioRepository();
$veiculoRepo = new VeiculoRepository();

$viagens = $viagemRepo->findByTransportadora($idtransportadora);
$motoristas = $usuarioRepo->findIntegrantesByTransportadora($idtransportadora);
$veiculos = $veiculoRepo->findByTransportadora($idtransportadora);

function getStatusText(int $status): string {
    return match ($status) {
        ViagemRepository::STATUS_AGENDADA => 'Agendada',
        ViagemRepository::STATUS_EM_CURSO => 'Em Curso',
        ViagemRepository::STATUS_FINALIZADA => 'Finalizada',
        ViagemRepository::STATUS_CANCELADA => 'Cancelada',
        default => 'Desconhecido',
    };
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Viagens - Gerente</title>
    <?php include_once("../elements/head.html") ?>
</head>
<body>
    <div class="sidebar"><?php include('../elements/sidebar.php') ?></div>
    <div class="main">
        <?php include('../elements/header.php') ?>
        <div class="content">
            <?php include('../elements/alert.php') ?>
            <div class="page-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h1>Gestão de Viagens</h1>
                    <button onclick="openAddModal()" class="btn primary">Agendar Nova Viagem</button>
                </div>
                <p>Gerencie as viagens da sua frota.</p>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Rota</th>
                            <th>Carga</th>
                            <th>Motorista / Veículo</th>
                            <th>Data Início</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($viagens)): ?>
                            <tr><td colspan="6">Nenhuma viagem encontrada.</td></tr>
                        <?php else: ?>
                            <?php foreach ($viagens as $viagem): ?>
                                <tr>
                                    <td data-label="Status"><?= getStatusText((int)$viagem['status']) ?></td>
                                    <td data-label="Rota"><?= htmlspecialchars($viagem['endereco_origem']) ?> -> <?= htmlspecialchars($viagem['endereco_destino']) ?></td>
                                    <td data-label="Carga"><?= htmlspecialchars($viagem['carga']) ?> (<?= htmlspecialchars($viagem['peso']) ?> kg)</td>
                                    <td data-label="Motorista/Veículo"><?= htmlspecialchars($viagem['motorista_nome']) ?> / <?= htmlspecialchars($viagem['veiculo_placa']) ?></td>
                                    <td data-label="Início"><?= date('d/m/Y H:i', strtotime($viagem['data_inicio'])) ?></td>
                                    <td data-label="Ações">
                                        <div class="actions">
                                            <?php if ($viagem['status'] == ViagemRepository::STATUS_AGENDADA): ?>
                                                <a href="actions/viagem_actions.php?action=iniciar&id=<?= $viagem['idviagem'] ?>" class="btn-icon" title="Iniciar Viagem">▶️</a>
                                                <a href="actions/viagem_actions.php?action=cancelar&id=<?= $viagem['idviagem'] ?>" class="btn-icon btn-deny" title="Cancelar" onclick="return confirm('Certeza?')">❌</a>
                                            <?php elseif ($viagem['status'] == ViagemRepository::STATUS_EM_CURSO): ?>
                                                <a href="actions/viagem_actions.php?action=finalizar&id=<?= $viagem['idviagem'] ?>" class="btn-icon btn-approve" title="Finalizar Viagem">✔️</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="addViagemModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2>Agendar Nova Viagem</h2>
                <span class="close" onclick="closeModal('addViagemModal')">&times;</span>
            </div>
            <form action="actions/viagem_actions.php" method="POST">
                <input type="hidden" name="action" value="create">
                <input type="hidden" id="latitude_origem" name="latitude_origem">
                <input type="hidden" id="longitude_origem" name="longitude_origem">
                <input type="hidden" id="latitude_destino" name="latitude_destino">
                <input type="hidden" id="longitude_destino" name="longitude_destino">

                <div class="form-group"><label>Motorista</label><select name="idusuario" required><option value="">Selecione...</option><?php foreach ($motoristas as $motorista): ?><option value="<?= $motorista['idusuario'] ?>"><?= htmlspecialchars($motorista['nome']) ?></option><?php endforeach; ?></select></div>
                <div class="form-group"><label>Veículo</label><select name="idveiculo" required><option value="">Selecione...</option><?php foreach ($veiculos as $veiculo): ?><option value="<?= $veiculo->idveiculo ?>"><?= htmlspecialchars($veiculo->placa . ' - ' . $veiculo->modelo) ?></option><?php endforeach; ?></select></div>
                <div class="form-group"><label>Endereço de Origem</label><input type="text" id="endereco_origem" name="endereco_origem" required></div>
                <div class="form-group"><label>Endereço de Destino</label><input type="text" id="endereco_destino" name="endereco_destino" required></div>
                <div class="form-group"><label>Carga</label><input type="text" name="carga" required></div>
                <div class="form-group"><label>Peso (kg)</label><input type="number" step="0.01" name="peso" required></div>
                <div class="form-group"><label>Data de Início</label><input type="datetime-local" name="data_inicio" required></div>
                <div class="form-group"><label>Observação</label><textarea name="obs"></textarea></div>
                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" class="btn" onclick="closeModal('addViagemModal')">Cancelar</button>
                    <button type="submit" class="btn primary">Agendar</button>
                </div>
            </form>
        </div>
    </div>

<script>
    function openAddModal() { document.getElementById('addViagemModal').style.display = 'block'; }
    function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }
    window.onclick = function(event) { if (event.target.classList.contains('modal')) { event.target.style.display = "none"; } }

    async function getCoords(endereco, latInput, lonInput) {
        if (endereco.value.trim() === '') return;
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(endereco.value)}&format=json&limit=1`);
            const data = await response.json();
            if (data.length > 0) {
                latInput.value = data[0].lat;
                lonInput.value = data[0].lon;
                console.log(`Coordenadas para ${endereco.id}: ${latInput.value}, ${lonInput.value}`);
            }
        } catch (e) {
            console.error("Erro ao buscar coordenadas:", e);
        }
    }

    document.getElementById('endereco_origem').addEventListener('blur', function() {
        getCoords(this, document.getElementById('latitude_origem'), document.getElementById('longitude_origem'));
    });

    document.getElementById('endereco_destino').addEventListener('blur', function() {
        getCoords(this, document.getElementById('latitude_destino'), document.getElementById('longitude_destino'));
    });
</script>
</body>
</html>