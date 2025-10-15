<?php
namespace src\Strategy\Solicitacao;

use src\Model\Solicitacao;
use src\Repository\SolicitacaoRepository;
use Exception;

class StatusAprovado implements StatusSolicitacaoInterface {
    public function aprovar(Solicitacao $solicitacao): void {
        throw new Exception("Esta solicitação já foi aprovada.");
    }

    public function negar(Solicitacao $solicitacao): void {
        $repo = new SolicitacaoRepository();
        $repo->executarRevogacao($solicitacao);
    }
}