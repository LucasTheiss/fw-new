<?php
namespace src\Model;

class Viagem
{
    public $idviagem;
    public $idveiculo;
    public $idmotorista;
    public $origem;
    public $destino;
    public $distancia_km;
    public $data_partida;
    public $data_chegada;
    public $status; // agendada, em_curso, finalizada
}
