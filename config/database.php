<?php

class Database {
    private static $instance = null;
    private $pdo;

    private $host = 'localhost';
    private $dbname = 'sistema_mentoria';
    private $user = 'root';
    private $pass = '';

    // Constructor privado
    private function __construct() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // Método estático para obtener la instancia
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Método para acceder al PDO
    public function getConnection() {
        return $this->pdo;
    }
}
