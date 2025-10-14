<?php
namespace src\Strategy\Viagem;

use src\Model\Viagem;
use src\Repository\ViagemRepository;
use Exception;

class StatusAgendada implements StatusViagemInterface {
    public function iniciar(Viagem $viagem): void {
        $repo = new ViagemRepository();
        $repo->updateStatus($viagem->idviagem, ViagemRepository::STATUS_EM_CURSO);
    }

    public function finalizar(Viagem $viagem): void {
        throw new Exception("Uma viagem agendada não pode ser finalizada diretamente.");
    }

    public function cancelar(Viagem $viagem): void {
        $repo = new ViagemRepository();
        $repo->updateStatus($viagem->idviagem, ViagemRepository::STATUS_CANCELADA);
    }
}