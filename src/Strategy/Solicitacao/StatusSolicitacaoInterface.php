<?php
namespace src\Strategy\Solicitacao;

use src\Model\Solicitacao;

interface StatusSolicitacaoInterface {
    public function aprovar(Solicitacao $solicitacao): void;
    public function negar(Solicitacao $solicitacao): void;
}