<?php
class Database {
    private $host = "localhost";
    private $dbname = "lab_rfid_system";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnect() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname}",
                    $this->username,
                    $this->password,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}

function db() {
    static $db = null;

    if ($db === null) {
        $database = new Database();
        $db = $database->getConnect();
    }

    return $db;
}
