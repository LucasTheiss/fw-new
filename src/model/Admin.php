<?php
namespace src\Model;

class Admin extends User
{
    public function __construct()
    {
        $this->adm = 1;
    }
}
