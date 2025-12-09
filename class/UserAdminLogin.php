<?php 

class UserAdminLogin {
    private $conn;
    private $table_name = "user_admin";
    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function userNotExists() {
        // user_admin table in phichaia_regis
        $query = "SELECT id FROM {$this->table_name} WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        return $stmt->rowCount() == 0;
    }

    public function verifyPassword() {
        $query = "SELECT id, password, name FROM {$this->table_name} WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
    
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $password = $this->password;
            // Ideally use password_verify if hashed, but seemingly plain text or simple compare based on UserLogin.
            // Following existing pattern for now (simple string compare), or maybe hashed if new.
            // Let's assume simple compare as per UserLogin.php to stay consistent/simple unless specified otherwise.
            $confirmPassword = $row['password'];
            
            if ($password == $confirmPassword) {
                $_SESSION['Admin_login'] = $row['id'];
                $_SESSION['user_admin_name'] = $row['name'];
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function userData($userid) {
        $query = "SELECT * FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $userid);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Map for compatibility with existing views
            $row['Teach_name'] = $row['name'];
            return $row;
        }
        return false;
    }
}
?>
