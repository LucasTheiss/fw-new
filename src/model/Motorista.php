<?php
namespace src\Model;

class Motorista extends User
{
    public $idtransportadora;

    public function __construct()
    {
        $this->gerente = 0;
    }
}
