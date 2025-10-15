<?php
require_once '../autoload.php';
require_once 'acesso.php';

use src\Repository\DenunciaRepository;

$denunciaRepo = new DenunciaRepository();
$denuncias = $denunciaRepo->findAllWithDetails();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Denúncias - Admin</title>
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
                <h1>Denúncias Pendentes</h1>
                <p>Analise e resolva as denúncias enviadas pelos utilizadores.</p>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Posto Denunciado</th>
                            <th>Motivo</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($denuncias)): ?>
                            <tr>
                                <td colspan="4">Nenhuma denúncia pendente encontrada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($denuncias as $denuncia): ?>
                            <tr>
                                <td data-label="Posto"><?= htmlspecialchars($denuncia->titulo) ?></td>
                                <td data-label="Motivo"><?= htmlspecialchars($denuncia->motivo) ?></td>
                                <td data-label="Data"><?= date('d/m/Y', strtotime($denuncia->data_criacao)) ?></td>
                                <td data-label="Ações">
                                    <div class="actions">
                                        <a href="actions/denuncia_actions.php?action=delete&id=<?= $denuncia->iddenuncia ?>" onclick="return confirm('Tem a certeza que deseja marcar esta denúncia como resolvida?')" class="btn-icon btn-approve" title="Marcar como Resolvida">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
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

