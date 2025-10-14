<?php
namespace src\Service;

use src\Repository\SolicitacaoRepository;
use src\Strategy\Solicitacao\StatusPendente;
use src\Strategy\Solicitacao\StatusAprovado;
use Exception;

class SolicitacaoService {
    private $solicitacaoRepo;

    public function __construct() {
        $this->solicitacaoRepo = new SolicitacaoRepository();
    }

    private function getStrategy(int $status) {
        return match ($status) {
            0 => new StatusPendente(),
            1 => new StatusAprovado(),
            default => throw new Exception("Status de solicitação inválido."),
        };
    }

    public function aprovar(int $idsolicitacao): void {
        $solicitacao = $this->solicitacaoRepo->findById($idsolicitacao);
        if (!$solicitacao) throw new Exception("Solicitação não encontrada.");
        
        $this->getStrategy($solicitacao->status)->aprovar($solicitacao);
    }

    public function negar(int $idsolicitacao): void {
        $solicitacao = $this->solicitacaoRepo->findById($idsolicitacao);
        if (!$solicitacao) throw new Exception("Solicitação não encontrada.");

        $this->getStrategy($solicitacao->status)->negar($solicitacao);
    }
}