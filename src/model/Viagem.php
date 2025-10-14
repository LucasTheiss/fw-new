<?php
namespace src\Model;

class Viagem
{
    public $idviagem;
    public $idusuario;
    public $idveiculo;
    public $data_inicio;
    public $data_termino;
    public $endereco_origem;
    public $latitude_origem;
    public $longitude_origem;
    public $endereco_destino;
    public $latitude_destino;
    public $longitude_destino;
    public $latitude_atual;
    public $longitude_atual;
    public $carga;
    public $peso;
    public $obs;
    public $status; // TINYINT: 0=Agendada, 1=Em Curso, 2=Finalizada, 3=Cancelada
}