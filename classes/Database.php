<?php

class Database {
    private $host = 'localhost:3307';
    private $db_name = 'biblioteca';
    private $username = 'root';
    private $password = '';
    public $conn;

    // Método para obtener la conexión a la base de datos
    public function getConnection() {
        $this ->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password);

                $this->conn->setAttribute(PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);

                  $this->conn->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );
        } catch (PDOException $exception) {
            die("Error de conexión: " . $exception->getMessage());
        }

       return $this->conn;
    }
}