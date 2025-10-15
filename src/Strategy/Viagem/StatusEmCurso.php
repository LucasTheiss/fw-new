<?php
namespace src\Strategy\Viagem;

use src\Model\Viagem;
use src\Repository\ViagemRepository;
use Exception;

class StatusEmCurso implements StatusViagemInterface {
    public function iniciar(Viagem $viagem): void {
        throw new Exception("A viagem já está em curso.");
    }

    public function finalizar(Viagem $viagem): void {
        $repo = new ViagemRepository();
        $repo->updateStatus($viagem->idviagem, 2);
    }

    public function cancelar(Viagem $viagem): void {
        $repo = new ViagemRepository();
        $repo->updateStatus($viagem->idviagem, 3);
    }
}