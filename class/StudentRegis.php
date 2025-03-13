<?php
class StudentRegis {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ...existing code...

    public function getM4QuotaStudents() {
        $query = "SELECT 
                        id, 
                        citizenid, 
                        CONCAT_WS('/', date_birth, month_birth, year_birth) AS birthday, 
                        CONCAT(stu_prefix, stu_name, ' ', stu_lastname) AS fullname, 
                        level, 
                        create_at, 
                        typeregis, 
                        old_school, 
                        old_school_province, 
                        now_tel, 
                        parent_tel,
                        gpa_total, 
                        old_school_stuid, 
                        number1,
                        number2,
                        number3,
                        number4,
                        number5,
                        number6, 
                        status 
                    FROM users 
                    WHERE level = '4' 
                    AND typeregis = 'โควต้า' 
                    ORDER BY create_at DESC
                    ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getM4QuotaStudentsConfirm() {
        $query = "SELECT 
                        u.id, 
                        u.citizenid, 
                        CONCAT_WS('/', u.date_birth, u.month_birth, u.year_birth) AS birthday, 
                        CONCAT(u.stu_prefix, u.stu_name, ' ', u.stu_lastname) AS fullname, 
                        u.level, 
                        u.create_at, 
                        u.typeregis, 
                        u.old_school, 
                        u.old_school_province, 
                        u.now_tel, 
                        u.parent_tel,
                        u.gpa_total, 
                        u.old_school_stuid, 
                        u.number1,
                        u.number2,
                        u.number3,
                        u.number4,
                        u.number5,
                        u.number6, 
                        u.status,
                        t.id AS confirm_id,
                        t.numreg,
                        t.no,
                        t.status AS confirm_status,
                        t.create_at AS confirm_create_at,
                        t.update_at AS confirm_update_at
                    FROM users u
                    INNER JOIN tbl_confirm t ON u.numreg = t.numreg
                    WHERE u.level = '4' 
                    AND u.typeregis = 'โควต้า' 
                    ORDER BY t.no ASC
                    ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getM1NomalStudents() {
        $query = "SELECT 
                        id, 
                        citizenid, 
                        CONCAT_WS('/', date_birth, month_birth, year_birth) AS birthday, 
                        CONCAT(stu_prefix, stu_name, ' ', stu_lastname) AS fullname, 
                        level, 
                        create_at, 
                        typeregis, 
                        old_school, 
                        old_school_province, 
                        now_tel, 
                        parent_tel, 
                        status 
                    FROM users 
                    WHERE level = '1' 
                    AND (typeregis = 'ในเขต' OR typeregis = 'นอกเขต')
                    ORDER BY create_at DESC
                    ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getM4NomalStudents() {
        $query = "SELECT 
                        id, 
                        citizenid, 
                        CONCAT_WS('/', date_birth, month_birth, year_birth) AS birthday, 
                        CONCAT(stu_prefix, stu_name, ' ', stu_lastname) AS fullname, 
                        level, 
                        create_at, 
                        typeregis, 
                        old_school, 
                        old_school_province, 
                        now_tel, 
                        parent_tel, 
                        status 
                    FROM users 
                    WHERE level = '4' 
                    AND typeregis = 'รอบทั่วไป'
                    ORDER BY create_at DESC
                    ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentById($id) {
        $query = "SELECT 
                        id, 
                        citizenid, 
                        CONCAT_WS('-', date_birth, month_birth, year_birth) AS birthday, 
                        stu_prefix,
                        stu_name,
                        stu_lastname, 
                        now_tel,
                        gpa_total, 
                        old_school_stuid, 
                        number1,
                        number2,
                        number3,
                        number4,
                        number5,
                        number6, 
                        parent_tel 
                    FROM users 
                    WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentByCitizId($id) {
        $query = "SELECT 
                        *,
                        CONCAT_WS('-', date_birth, month_birth, year_birth) AS birthday
                    FROM users 
                    WHERE citizenid = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStudent($id, $citizenid, $stu_prefix, $stu_name, $stu_lastname, $date_birth, $month_birth, $year_birth, $now_tel, $parent_tel, $gpa_total, $old_school_stuid, $number1, $number2, $number3, $number4, $number5, $number6) {
        $query = "UPDATE users 
                    SET 
                        citizenid = :citizenid, 
                        stu_prefix = :stu_prefix, 
                        stu_name = :stu_name, 
                        stu_lastname = :stu_lastname, 
                        date_birth = :date_birth, 
                        month_birth = :month_birth, 
                        year_birth = :year_birth, 
                        now_tel = :now_tel, 
                        parent_tel = :parent_tel,
                        gpa_total = :gpa_total,
                        old_school_stuid = :old_school_stuid,
                        number1 = :number1,
                        number2 = :number2,
                        number3 = :number3,
                        number4 = :number4,
                        number5 = :number5,
                        number6 = :number6
                    WHERE id = :id";
        
        // Prepare the query
        $stmt = $this->conn->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(':citizenid', $citizenid);
        $stmt->bindParam(':stu_prefix', $stu_prefix);
        $stmt->bindParam(':stu_name', $stu_name);
        $stmt->bindParam(':stu_lastname', $stu_lastname);
        $stmt->bindParam(':date_birth', $date_birth);
        $stmt->bindParam(':month_birth', $month_birth);
        $stmt->bindParam(':year_birth', $year_birth);
        $stmt->bindParam(':now_tel', $now_tel);
        $stmt->bindParam(':parent_tel', $parent_tel);
        $stmt->bindParam(':gpa_total', $gpa_total);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':old_school_stuid', $old_school_stuid);
        $stmt->bindParam(':number1', $number1);
        $stmt->bindParam(':number2', $number2);
        $stmt->bindParam(':number3', $number3);
        $stmt->bindParam(':number4', $number4);
        $stmt->bindParam(':number5', $number5);
        $stmt->bindParam(':number6', $number6);
    
        try {
            // Execute the query
            if ($stmt->execute()) {
                return true; // Successfully updated
            } else {
                // Log the error and return false
                error_log("Update failed: " . implode(", ", $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            // Log any exception
            error_log("PDOException: " . $e->getMessage());
            return false;
        }
    }
    
    public function getStudentByCitizenId($citizen_id) {
        $query = "SELECT 
                        citizenid,
                        typeregis,
                        level, 
                        CONCAT_WS('-', date_birth, month_birth, year_birth) AS birthday, 
                        stu_prefix,
                        stu_name,
                        stu_lastname, 
                        now_tel, 
                        parent_tel 
                    FROM users 
                    WHERE citizenid = :citizen_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':citizen_id', $citizen_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentBySearchInput($search_input) {
        $query = "SELECT 
                        citizenid,
                        typeregis,
                        level, 
                        CONCAT_WS('-', date_birth, month_birth, year_birth) AS birthday, 
                        stu_prefix,
                        stu_name,
                        stu_lastname, 
                        now_tel, 
                        parent_tel,
                        numreg,
                        status
                    FROM users 
                    WHERE citizenid = :search_input OR CONCAT(stu_prefix, stu_name, ' ', stu_lastname) LIKE :search_input_like";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':search_input', $search_input);
        $search_input_like = '%' . $search_input . '%';
        $stmt->bindParam(':search_input_like', $search_input_like);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentBySearchInputConfirm($search_input) {
        $query = "SELECT 
                    u.citizenid,
                    u.typeregis,
                    u.level, 
                    CONCAT_WS('-', u.date_birth, u.month_birth, u.year_birth) AS birthday, 
                    u.stu_prefix,
                    u.stu_name,
                    u.stu_lastname, 
                    u.now_tel, 
                    u.parent_tel,
                    u.numreg,
                    u.status,
                    t.id AS confirm_id,
                    t.no,
                    t.status AS confirm_status,
                    t.create_at,
                    t.update_at
                FROM users u
                INNER JOIN tbl_confirm t ON u.numreg = t.numreg
                WHERE u.citizenid = :search_input 
                   OR CONCAT(u.stu_prefix, u.stu_name, ' ', u.stu_lastname) LIKE :search_input_like";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':search_input', $search_input);
        $search_input_like = '%' . $search_input . '%';
        $stmt->bindParam(':search_input_like', $search_input_like);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function deleteStudent($id) {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        try {
            if ($stmt->execute()) {
                return true; // Successfully deleted
            } else {
                error_log("Delete failed: " . implode(", ", $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            error_log("PDOException: " . $e->getMessage());
            return false;
        }
    }

    public function getDailyRegistrationCounts($startDate, $endDate) {
        $query = "SELECT DATE(create_at) as date, COUNT(*) as count 
                  FROM users 
                  WHERE create_at BETWEEN :start_date AND :end_date 
                  GROUP BY DATE(create_at) 
                  ORDER BY DATE(create_at)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dailyCounts = [];
        foreach ($results as $result) {
            $dailyCounts[$result['date']] = $result['count'];
        }

        return $dailyCounts;
    }

    public function updateConfirmStatus($numreg, $status) {
        $query = "UPDATE tbl_confirm 
                  SET status = :status 
                  WHERE numreg = :numreg";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':numreg', $numreg);

        try {
            if ($stmt->execute()) {
                return true; // Successfully updated
            } else {
                error_log("Update failed: " . implode(", ", $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            error_log("PDOException: " . $e->getMessage());
            return false;
        }
    }

    public function countConfirm($status, $level, $typeregis, $year) {
        $sql = "SELECT COUNT(*) AS total_count 
                FROM users u
                INNER JOIN tbl_confirm t ON u.numreg = t.numreg
                WHERE t.status = :status 
                AND u.level = :level
                AND u.typeregis = :typeregis
                AND u.reg_pee = :year";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':level', $level, PDO::PARAM_INT);
        $stmt->bindParam(':typeregis', $typeregis, PDO::PARAM_STR);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total_count'] ?? 0;
    }
    // ...existing code...
}
?>
