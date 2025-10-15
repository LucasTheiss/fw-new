<?php
namespace src\Strategy\Viagem;
use src\Model\Viagem;
use Exception;

class StatusFinalizada implements StatusViagemInterface {
    public function iniciar(Viagem $viagem): void { throw new Exception("Não é possível iniciar uma viagem finalizada."); }
    public function finalizar(Viagem $viagem): void { throw new Exception("A viagem já foi finalizada."); }
    public function cancelar(Viagem $viagem): void { throw new Exception("Não é possível cancelar uma viagem finalizada."); }
}