<?php

class Database_User
{
    private $host = "localhost:3306";
    private $db = "phichaia_student";
    // private $username = "adminregister";
    // private $password = "0Z219iu&p";
    private $username = "root";
    private $password = "storage";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection Error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

class Database_Regis
{
    private $host = "localhost:3306";
    private $db = "phichaia_regis";
    // private $username = "adminregister";
    // private $password = "0Z219iu&p";
    private $username = "root";
    private $password = "storage";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection Error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

?>