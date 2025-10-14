<?php
namespace src\Model;

abstract class User
{
    public $idusuario;
    public $email;
    public $nome;
    public $senha;
    public $telefone;
    public $cpf;
    public $gerente = 0;
    public $adm = 0;
}
