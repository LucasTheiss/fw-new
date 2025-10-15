<?php
namespace src\Strategy\Viagem;

use src\Model\Viagem;

interface StatusViagemInterface {
    public function iniciar(Viagem $viagem): void;
    public function finalizar(Viagem $viagem): void;
    public function cancelar(Viagem $viagem): void;
}