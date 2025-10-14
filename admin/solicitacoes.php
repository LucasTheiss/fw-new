<?php
require_once '../autoload.php';
require_once 'acesso.php';

use src\Repository\SolicitacaoRepository;

$solicitacaoRepo = new SolicitacaoRepository();
$solicitacoes = $solicitacaoRepo->findAll(); // O método já busca todos os dados necessários
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
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($solicitacoes)): ?>
                            <tr>
                                <td colspan="5">Nenhuma solicitação encontrada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($solicitacoes as $solicitacao): ?>
                            <tr>
                                <td data-label="Transportadora"><?= htmlspecialchars($solicitacao['nomeTransportadora']) ?></td>
                                <td data-label="Gerente"><?= htmlspecialchars($solicitacao['nomeUsuario']) ?></td>
                                <td data-label="Email"><?= htmlspecialchars($solicitacao['emailUsuario']) ?></td>
                                <td data-label="Estado">
                                    <?php
                                        $status_info = match((int)$solicitacao['status']) {
                                            0 => ['class' => 'badge-pending', 'text' => 'Pendente'],
                                            1 => ['class' => 'badge-success', 'text' => 'Aprovado'],
                                            2 => ['class' => 'badge-denied', 'text' => 'Negado'],
                                            default => ['class' => '', 'text' => 'Desconhecido']
                                        };
                                    ?>
                                    <span class="badge <?= $status_info['class'] ?>"><?= $status_info['text'] ?></span>
                                </td>
                                <td data-label="Ações">
                                    <div class="actions">
                                        <button class="btn-icon btn-view" title="Ver Detalhes" onclick='openDetailsModal(<?= json_encode($solicitacao) ?>)'>
                                            <svg class="icon" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="3"></circle><path d="M2 12c2-4 6-7 10-7s8 3 10 7c-2 4-6 7-10 7s-8-3-10-7z"></path></svg>
                                        </button>
                                        <?php if ($solicitacao['status'] != '1') { ?>
                                            <a href="actions/solicitacao_action.php?action=approve&id=<?= $solicitacao['idsolicitacao'] ?>" class="btn-icon btn-approve" title="Aprovar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            </a>
                                        <?php } else if ($solicitacao['status'] != 2) {?>
                                            <a href="actions/solicitacao_action.php?action=deny&id=<?= $solicitacao['idsolicitacao'] ?>" class="btn-icon btn-deny" title="Negar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </a>
                                        <?php } ?>
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

    <div id="detailsModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2>Detalhes da Solicitação</h2>
                <span class="close" onclick="closeModal('detailsModal')">&times;</span>
            </div>
            <div class="modal-body" style="padding-top: 1rem;">
                <h4>Dados da Empresa</h4>
                <p><strong>Nome:</strong> <span id="modal-nomeTransportadora"></span></p>
                <p><strong>CNPJ:</strong> <span id="modal-cnpj"></span></p>
                <p><strong>Telefone:</strong> <span id="modal-telefoneEmpresa"></span></p>
                <p><strong>Endereço:</strong> <span id="modal-endereco"></span>, <span id="modal-cidade"></span> - <span id="modal-estado"></span>, <span id="modal-cep"></span></p>
                
                <h4 style="margin-top: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">Dados do Gerente</h4>
                <p><strong>Nome:</strong> <span id="modal-nomeUsuario"></span> <span id="modal-sobrenome"></span></p>
                <p><strong>Email:</strong> <span id="modal-emailUsuario"></span></p>
                <p><strong>CPF:</strong> <span id="modal-cpf"></span></p>
                <p><strong>Telefone:</strong> <span id="modal-telefoneUsuario"></span></p>
            </div>
        </div>
    </div>

<script>
    function openDetailsModal(solicitacao) {
        // Preenche os dados da empresa
        document.getElementById('modal-nomeTransportadora').textContent = solicitacao.nomeTransportadora;
        document.getElementById('modal-cnpj').textContent = formatCNPJ(solicitacao.cnpj);
        document.getElementById('modal-telefoneEmpresa').textContent = formatTelefone(solicitacao.telefoneEmpresa);
        document.getElementById('modal-endereco').textContent = solicitacao.endereco;
        document.getElementById('modal-cep').textContent = formatCEP(solicitacao.cep);
        document.getElementById('modal-cidade').textContent = solicitacao.cidade;
        document.getElementById('modal-estado').textContent = solicitacao.estado;

        // Preenche os dados do gerente
        document.getElementById('modal-nomeUsuario').textContent = solicitacao.nomeUsuario;
        document.getElementById('modal-sobrenome').textContent = solicitacao.sobrenome;
        document.getElementById('modal-emailUsuario').textContent = solicitacao.emailUsuario;
        document.getElementById('modal-cpf').textContent = formatCPF(solicitacao.cpf);
        document.getElementById('modal-telefoneUsuario').textContent = formatTelefone(solicitacao.telefoneUsuario);
        
        // Exibe o modal
        document.getElementById('detailsModal').style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Fecha o modal se o usuário clicar fora do conteúdo
    window.onclick = function(event) {
        const modal = document.getElementById('detailsModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Funções de formatação para melhor visualização
    function formatCPF(cpf) {
        return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
    }

    function formatCNPJ(cnpj) {
        return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
    }

    function formatCEP(cep) {
        return cep.replace(/(\d{5})(\d{3})/, "$1-$2");
    }

    function formatTelefone(tel) {
        if (tel.length === 11) {
            return tel.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
        }
        return tel.replace(/(\d{2})(\d{4})(\d{4})/, "($1) $2-$3");
    }
</script>

</body>
</html>