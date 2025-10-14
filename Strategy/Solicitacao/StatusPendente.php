<?php
namespace src\Strategy\Solicitacao;

use src\Model\Solicitacao;
use src\Repository\SolicitacaoRepository;

class StatusPendente implements StatusSolicitacaoInterface {
    public function aprovar(Solicitacao $solicitacao): void {
        $repo = new SolicitacaoRepository();
        $repo->executarAprovacao($solicitacao);
    }

    public function negar(Solicitacao $solicitacao): void {
        $repo = new SolicitacaoRepository();
        $repo->updateStatus($solicitacao->idsolicitacao, 2); // 2 = Negado
    }
}