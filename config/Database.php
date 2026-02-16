<?php

// Auto-detect environment based on hostname
function isLocalEnvironment()
{
    $localHosts = ['localhost', '127.0.0.1', '::1'];
    $serverName = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Check if running on localhost or local IP
    if (in_array($serverName, $localHosts)) {
        return true;
    }

    // Check for local development paths (XAMPP on Mac)
    if (strpos(__DIR__, '/Applications/XAMPP/') !== false) {
        return true;
    }

    // Check for common local domains
    if (strpos($serverName, '.local') !== false || strpos($serverName, '.test') !== false) {
        return true;
    }

    return false;
}

// Get database credentials based on environment
function getDbCredentials()
{
    if (isLocalEnvironment()) {
        return [
            'username' => 'root',
            'password' => 'storage'
        ];
    } else {
        return [
            'username' => 'adminregister',
            'password' => '0Z219iu&p'
        ];
    }
}

class Database_User
{
    private $host = "localhost:3306";
    private $db = "phichaia_student";
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {
        $creds = getDbCredentials();
        $this->username = $creds['username'];
        $this->password = $creds['password'];
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            throw new Exception("Connection Error (User DB): " . $exception->getMessage());
        }

        return $this->conn;
    }
}

class Database_Regis
{
    private $host = "localhost:3306";
    private $db = "phichaia_regis";
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {
        $creds = getDbCredentials();
        $this->username = $creds['username'];
        $this->password = $creds['password'];
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            throw new Exception("Connection Error (Regis DB): " . $exception->getMessage());
        }

        return $this->conn;
    }
}

?>