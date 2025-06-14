<?php

class Database {
    private static $instance = null;
    private $pdo;

    private const DB_CONFIG = [
        'host' => 'localhost',
        'dbname' => 'sistema_mentoria',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    ];

    private function __construct() {
        $this->connect();
    }

    private function __clone() {}

    public function __wakeup() {
        throw new Exception("No se puede deserializar una instancia de " . __CLASS__);
    }

    private function connect() {
        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                self::DB_CONFIG['host'],
                self::DB_CONFIG['dbname'],
                self::DB_CONFIG['charset']
            );

            $this->pdo = new PDO(
                $dsn,
                self::DB_CONFIG['user'],
                self::DB_CONFIG['pass'],
                self::DB_CONFIG['options']
            );

        } catch (PDOException $e) {
            error_log("Error de conexión a BD: " . $e->getMessage());
            throw new Exception("Error al conectar con la base de datos");
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        if (!$this->isConnected()) {
            $this->connect();
        }
        return $this->pdo;
    }

    private function isConnected(): bool {
        try {
            $this->pdo->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function query(string $sql, array $params = []): PDOStatement {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("ERROR MYSQL: " . $e->getMessage());
            error_log("CÓDIGO: " . $e->getCode());
            error_log("SQL: " . $sql);
            error_log("PARAMS: " . print_r($params, true));

            throw $e;
        }
    }

    public function fetchOne(string $sql, array $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    public function fetchAll(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll();
    }

    
    public function insert(string $sql, array $params = []): string {
        try {
            error_log("🔍 DEBUG INSERT - SQL: " . $sql);
            error_log("🔍 DEBUG INSERT - Params: " . json_encode($params));
            
            $this->query($sql, $params);
            $lastId = $this->getConnection()->lastInsertId();
            
            error_log("🔍 DEBUG INSERT - Last Insert ID: " . $lastId);
            error_log("🔍 DEBUG INSERT - Last Insert ID Type: " . gettype($lastId));
            
            return $lastId;
            
        } catch (Exception $e) {
            error_log("🔥 ERROR en insert(): " . $e->getMessage());
            throw $e;
        }
    }

    public function execute(string $sql, array $params = []): int {
        return $this->query($sql, $params)->rowCount();
    }

    public function beginTransaction(): void {
        $this->getConnection()->beginTransaction();
    }

    public function commit(): void {
        $this->getConnection()->commit();
    }

    public function rollback(): void {
        $this->getConnection()->rollback();
    }

    public function inTransaction(): bool {
        return $this->getConnection()->inTransaction();
    }

    public function close(): void {
        $this->pdo = null;
        self::$instance = null;
    }
}