<?php
require_once '../autoload.php';

use src\Repository\SolicitacaoRepository;

$solicitacaoRepo = new SolicitacaoRepository();
$solicitacoes = $solicitacaoRepo->findAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Solicitações - Admin</title>
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
                <h1>Solicitações de Cadastro</h1>
                <p>Aprove ou negue os pedidos de registo de novas transportadoras.</p>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Transportadora</th>
                            <th>Gerente</th>
                            <th>Data</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($solicitacoes)): ?>
                            <tr>
                                <td colspan="5">Nenhuma solicitação pendente encontrada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($solicitacoes as $solicitacao): ?>
                            <tr>
                                <td data-label="Transportadora"><?= htmlspecialchars($solicitacao['nome_fantasia']) ?></td>
                                <td data-label="Gerente"><?= htmlspecialchars($solicitacao['gerente_nome']) ?></td>
                                <td data-label="Data"><?= date('d/m/Y', strtotime($solicitacao['data_solicitacao'])) ?></td>
                                <td data-label="Estado">
                                    <span class="badge badge-pending"><?= htmlspecialchars($solicitacao['status']) ?></span>
                                </td>
                                <td data-label="Ações">
                                    <div class="actions">
                                        <a href="actions/solicitacao_action.php?action=approve&id=<?= $solicitacao['idsolicitacao'] ?>" class="btn-icon btn-approve" title="Aprovar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        </a>
                                        <a href="actions/solicitacao_action.php?action=deny&id=<?= $solicitacao['idsolicitacao'] ?>" class="btn-icon btn-deny" title="Negar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </a>
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
</body>
</html>
