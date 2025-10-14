<?php
namespace config;

use mysqli;

class Database {
    private static $instance = null;
    private $conn;

    private $host = 'localhost:3306';
    private $user = 'root';
    private $password = '';
    private $database = 'fw';

    private function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Falha na conexão: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8");
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}