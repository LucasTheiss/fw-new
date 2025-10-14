<?php
namespace src\Model;

class Gerente extends User
{
    public $idtransportadora;

    public function __construct()
    {
        $this->gerente = 1;
    }
}
