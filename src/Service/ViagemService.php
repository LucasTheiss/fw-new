<?php
namespace src\Service;

use src\Model\Viagem;
use src\Repository\ViagemRepository;
use src\Strategy\Viagem\StatusAgendada;
use src\Strategy\Viagem\StatusEmCurso;
use src\Strategy\Viagem\StatusFinalizada;
use src\Strategy\Viagem\StatusCancelada;
use Exception;

class ViagemService {
    private $viagemRepo;

    public function __construct() {
        $this->viagemRepo = new ViagemRepository();
    }

    private function getStrategy(Viagem $viagem) {
        return match ($viagem->status) {
            'agendada' => new StatusAgendada(),
            'em_curso' => new StatusEmCurso(),
            'finalizada' => new StatusFinalizada(),
            'cancelada' => new StatusCancelada(),
            default => throw new Exception("Status da viagem desconhecido."),
        };
    }

    public function iniciar(int $idviagem): void {
        $viagem = $this->viagemRepo->findById($idviagem);
        $this->getStrategy($viagem)->iniciar($viagem);
    }

    public function finalizar(int $idviagem): void {
        $viagem = $this->viagemRepo->findById($idviagem);
        $this->getStrategy($viagem)->finalizar($viagem);
    }

    public function cancelar(int $idviagem): void {
        $viagem = $this->viagemRepo->findById($idviagem);
        $this->getStrategy($viagem)->cancelar($viagem);
    }
}