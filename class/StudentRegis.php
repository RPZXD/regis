<?php
class StudentRegis
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ...existing code...

    public function getM4QuotaStudents()
    {
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

    public function getM4QuotaStudentsConfirm()
    {
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

    public function getM1NomalStudents()
    {
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
                        number1,
                        number2,
                        number3,
                        number4,
                        number5,
                        number6, 
                        number7, 
                        number8, 
                        number9, 
                        number10, 
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

    public function getStudentsByCriteria($level, $typeName)
    {
        // Prepare Level Criteria (support both '1' and 'm1')
        $levels = [$level];
        if ($level == '1')
            $levels[] = 'm1';
        if ($level == '4')
            $levels[] = 'm4';
        $levelStr = "'" . implode("','", $levels) . "'";

        // Select ALL user columns + calculated fields
        $sql = "SELECT u.*, CONCAT_WS('/', u.date_birth, u.month_birth, u.year_birth) AS birthday, 
            CONCAT(u.stu_prefix, u.stu_name, ' ', u.stu_lastname) AS fullname,
            GROUP_CONCAT(CONCAT(ssp.priority, ':', ssp.plan_id) ORDER BY ssp.priority ASC SEPARATOR ',') as plan_string
            FROM users u
            LEFT JOIN student_study_plans ssp ON u.id = ssp.user_id 
            WHERE u.level IN ($levelStr)";

        // Special case for M.1 General (Zone)
        if (($level == '1' || $level == 'm1') && $typeName == 'รอบทั่วไป') {
            $sql .= " AND (u.typeregis = 'ในเขต' OR u.typeregis = 'นอกเขต')";
        } else {
            $sql .= " AND u.typeregis = :typeName";
        }

        $sql .= " GROUP BY u.id ORDER BY u.create_at DESC";

        $stmt = $this->conn->prepare($sql);
        // $stmt->bindParam(':level', $level); // Removed in favor of IN clause
        if (!(($level == '1' || $level == 'm1') && $typeName == 'รอบทั่วไป')) {
            $stmt->bindParam(':typeName', $typeName);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countStudentsByCriteria($level, $typeName)
    {
        // Prepare Level
        $levels = [$level];
        if ($level == '1')
            $levels[] = 'm1';
        if ($level == '4')
            $levels[] = 'm4';
        $levelStr = "'" . implode("','", $levels) . "'";

        $sql = "SELECT COUNT(*) as total,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending
                FROM users 
                WHERE level IN ($levelStr)";

        if (($level == '1' || $level == 'm1') && $typeName == 'รอบทั่วไป') {
            $sql .= " AND (typeregis = 'ในเขต' OR typeregis = 'นอกเขต')";
        } else {
            $sql .= " AND typeregis = :typeName";
        }

        $stmt = $this->conn->prepare($sql);
        if (!(($level == '1' || $level == 'm1') && $typeName == 'รอบทั่วไป')) {
            $stmt->bindParam(':typeName', $typeName);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDailyRegistrations($days = 7)
    {
        $sql = "SELECT DATE_FORMAT(create_at, '%Y-%m-%d') as reg_date, COUNT(*) as count 
                FROM users 
                WHERE create_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                GROUP BY DATE_FORMAT(create_at, '%Y-%m-%d')
                ORDER BY reg_date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Returns array like [['reg_date'=>'...', 'count'=>...], ...]
    }

    public function getStudentsWithDocuments($level, $typeName)
    {
        // Prepare Level Criteria
        $levels = [$level];
        if ($level == '1')
            $levels[] = 'm1';
        if ($level == '4')
            $levels[] = 'm4';
        $levelStr = "'" . implode("','", $levels) . "'";

        $sql = "SELECT 
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
                    u.status,
                    -- document1
                    COALESCE(MAX(CASE WHEN t.name = 'document1' THEN t.path END), '') AS upload_path1,
                    COALESCE(MAX(CASE WHEN t.name = 'document1' THEN t.error_detail END), '') AS error_detail1,
                    COALESCE(MAX(CASE WHEN t.name = 'document1' THEN t.status END), '') AS status1,
                    -- document2
                    COALESCE(MAX(CASE WHEN t.name = 'document2' THEN t.path END), '') AS upload_path2,
                    COALESCE(MAX(CASE WHEN t.name = 'document2' THEN t.error_detail END), '') AS error_detail2,
                    COALESCE(MAX(CASE WHEN t.name = 'document2' THEN t.status END), '') AS status2,
                    -- document3
                    COALESCE(MAX(CASE WHEN t.name = 'document3' THEN t.path END), '') AS upload_path3,
                    COALESCE(MAX(CASE WHEN t.name = 'document3' THEN t.error_detail END), '') AS error_detail3,
                    COALESCE(MAX(CASE WHEN t.name = 'document3' THEN t.status END), '') AS status3,
                    -- document4
                    COALESCE(MAX(CASE WHEN t.name = 'document4' THEN t.path END), '') AS upload_path4,
                    COALESCE(MAX(CASE WHEN t.name = 'document4' THEN t.error_detail END), '') AS error_detail4,
                    COALESCE(MAX(CASE WHEN t.name = 'document4' THEN t.status END), '') AS status4,
                    -- document5
                    COALESCE(MAX(CASE WHEN t.name = 'document5' THEN t.path END), '') AS upload_path5,
                    COALESCE(MAX(CASE WHEN t.name = 'document5' THEN t.error_detail END), '') AS error_detail5,
                    COALESCE(MAX(CASE WHEN t.name = 'document5' THEN t.status END), '') AS status5,
                    -- document6
                    COALESCE(MAX(CASE WHEN t.name = 'document6' THEN t.path END), '') AS upload_path6,
                    COALESCE(MAX(CASE WHEN t.name = 'document6' THEN t.error_detail END), '') AS error_detail6,
                    COALESCE(MAX(CASE WHEN t.name = 'document6' THEN t.status END), '') AS status6,
                    -- document7
                    COALESCE(MAX(CASE WHEN t.name = 'document7' THEN t.path END), '') AS upload_path7,
                    COALESCE(MAX(CASE WHEN t.name = 'document7' THEN t.error_detail END), '') AS error_detail7,
                    COALESCE(MAX(CASE WHEN t.name = 'document7' THEN t.status END), '') AS status7,
                    -- document8
                    COALESCE(MAX(CASE WHEN t.name = 'document8' THEN t.path END), '') AS upload_path8,
                    COALESCE(MAX(CASE WHEN t.name = 'document8' THEN t.error_detail END), '') AS error_detail8,
                    COALESCE(MAX(CASE WHEN t.name = 'document8' THEN t.status END), '') AS status8
                FROM users u
                LEFT JOIN tbl_uploads t ON u.citizenid = t.citizenid
                WHERE u.level IN ($levelStr)";

        // Special case for M.1 General (Zone)
        if (($level == '1' || $level == 'm1') && $typeName == 'รอบทั่วไป') {
            $sql .= " AND (u.typeregis = 'ในเขต' OR u.typeregis = 'นอกเขต')";
        } else {
            $sql .= " AND u.typeregis = :typeName";
        }

        $sql .= " GROUP BY u.id ORDER BY u.create_at ASC";

        $stmt = $this->conn->prepare($sql);
        // $stmt->bindParam(':level', $level); // Removed
        if (!(($level == '1' || $level == 'm1') && $typeName == 'รอบทั่วไป')) {
            $stmt->bindParam(':typeName', $typeName);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getM1NomalStudents_Check()
    {
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
                        u.status,
                        -- document1
                        COALESCE(MAX(CASE WHEN t.name = 'document1' THEN t.path END), '') AS upload_path1,
                        COALESCE(MAX(CASE WHEN t.name = 'document1' THEN t.error_detail END), '') AS error_detail1,
                        COALESCE(MAX(CASE WHEN t.name = 'document1' THEN t.status END), '') AS status1,
                        -- document2
                        COALESCE(MAX(CASE WHEN t.name = 'document2' THEN t.path END), '') AS upload_path2,
                        COALESCE(MAX(CASE WHEN t.name = 'document2' THEN t.error_detail END), '') AS error_detail2,
                        COALESCE(MAX(CASE WHEN t.name = 'document2' THEN t.status END), '') AS status2,
                        -- document3
                        COALESCE(MAX(CASE WHEN t.name = 'document3' THEN t.path END), '') AS upload_path3,
                        COALESCE(MAX(CASE WHEN t.name = 'document3' THEN t.error_detail END), '') AS error_detail3,
                        COALESCE(MAX(CASE WHEN t.name = 'document3' THEN t.status END), '') AS status3,
                        -- document4
                        COALESCE(MAX(CASE WHEN t.name = 'document4' THEN t.path END), '') AS upload_path4,
                        COALESCE(MAX(CASE WHEN t.name = 'document4' THEN t.error_detail END), '') AS error_detail4,
                        COALESCE(MAX(CASE WHEN t.name = 'document4' THEN t.status END), '') AS status4,
                        -- document5
                        COALESCE(MAX(CASE WHEN t.name = 'document5' THEN t.path END), '') AS upload_path5,
                        COALESCE(MAX(CASE WHEN t.name = 'document5' THEN t.error_detail END), '') AS error_detail5,
                        COALESCE(MAX(CASE WHEN t.name = 'document5' THEN t.status END), '') AS status5,
                        -- document6
                        COALESCE(MAX(CASE WHEN t.name = 'document6' THEN t.path END), '') AS upload_path6,
                        COALESCE(MAX(CASE WHEN t.name = 'document6' THEN t.error_detail END), '') AS error_detail6,
                        COALESCE(MAX(CASE WHEN t.name = 'document6' THEN t.status END), '') AS status6,
                        -- document7
                        COALESCE(MAX(CASE WHEN t.name = 'document7' THEN t.path END), '') AS upload_path7,
                        COALESCE(MAX(CASE WHEN t.name = 'document7' THEN t.error_detail END), '') AS error_detail7,
                        COALESCE(MAX(CASE WHEN t.name = 'document7' THEN t.status END), '') AS status7,
                        -- document8
                        COALESCE(MAX(CASE WHEN t.name = 'document8' THEN t.path END), '') AS upload_path8,
                        COALESCE(MAX(CASE WHEN t.name = 'document8' THEN t.error_detail END), '') AS error_detail8,
                        COALESCE(MAX(CASE WHEN t.name = 'document8' THEN t.status END), '') AS status8,
                        -- document9
                        COALESCE(MAX(CASE WHEN t.name = 'document9' THEN t.path END), '') AS upload_path9,
                        COALESCE(MAX(CASE WHEN t.name = 'document9' THEN t.error_detail END), '') AS error_detail9,
                        COALESCE(MAX(CASE WHEN t.name = 'document9' THEN t.status END), '') AS status9
                    FROM users u
                    LEFT JOIN tbl_uploads t ON u.citizenid = t.citizenid
                    WHERE u.level = '1' 
                        AND (u.typeregis = 'ในเขต' OR u.typeregis = 'นอกเขต')
                    GROUP BY u.id
                    ORDER BY u.create_at ASC


                    ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getM1NomalStudents_Pass($date)
    {
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
                        status,
                        update_at
                    FROM users u
                    WHERE level = '1' 
                        AND (typeregis = 'ในเขต' OR typeregis = 'นอกเขต')
                        AND status = 1
                        AND DATE(update_at) = :date
                    ORDER BY update_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getM4NomalStudents_Pass($date)
    {
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
                        status,
                        update_at
                    FROM users u
                    WHERE level = '4' 
                        AND typeregis = 'รอบทั่วไป'
                        AND status = 1
                        AND DATE(update_at) = :date
                    ORDER BY update_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getM4NomalStudents_Check()
    {
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
                        u.status,
                        -- document1
                        COALESCE(MAX(CASE WHEN t.name = 'document1' THEN t.path END), '') AS upload_path1,
                        COALESCE(MAX(CASE WHEN t.name = 'document1' THEN t.error_detail END), '') AS error_detail1,
                        COALESCE(MAX(CASE WHEN t.name = 'document1' THEN t.status END), '') AS status1,
                        -- document2
                        COALESCE(MAX(CASE WHEN t.name = 'document2' THEN t.path END), '') AS upload_path2,
                        COALESCE(MAX(CASE WHEN t.name = 'document2' THEN t.error_detail END), '') AS error_detail2,
                        COALESCE(MAX(CASE WHEN t.name = 'document2' THEN t.status END), '') AS status2,
                        -- document3
                        COALESCE(MAX(CASE WHEN t.name = 'document3' THEN t.path END), '') AS upload_path3,
                        COALESCE(MAX(CASE WHEN t.name = 'document3' THEN t.error_detail END), '') AS error_detail3,
                        COALESCE(MAX(CASE WHEN t.name = 'document3' THEN t.status END), '') AS status3,
                        -- document4
                        COALESCE(MAX(CASE WHEN t.name = 'document4' THEN t.path END), '') AS upload_path4,
                        COALESCE(MAX(CASE WHEN t.name = 'document4' THEN t.error_detail END), '') AS error_detail4,
                        COALESCE(MAX(CASE WHEN t.name = 'document4' THEN t.status END), '') AS status4,
                        -- document5
                        COALESCE(MAX(CASE WHEN t.name = 'document5' THEN t.path END), '') AS upload_path5,
                        COALESCE(MAX(CASE WHEN t.name = 'document5' THEN t.error_detail END), '') AS error_detail5,
                        COALESCE(MAX(CASE WHEN t.name = 'document5' THEN t.status END), '') AS status5,
                        -- document6
                        COALESCE(MAX(CASE WHEN t.name = 'document6' THEN t.path END), '') AS upload_path6,
                        COALESCE(MAX(CASE WHEN t.name = 'document6' THEN t.error_detail END), '') AS error_detail6,
                        COALESCE(MAX(CASE WHEN t.name = 'document6' THEN t.status END), '') AS status6,
                        -- document7
                        COALESCE(MAX(CASE WHEN t.name = 'document7' THEN t.path END), '') AS upload_path7,
                        COALESCE(MAX(CASE WHEN t.name = 'document7' THEN t.error_detail END), '') AS error_detail7,
                        COALESCE(MAX(CASE WHEN t.name = 'document7' THEN t.status END), '') AS status7,
                        -- document8
                        COALESCE(MAX(CASE WHEN t.name = 'document8' THEN t.path END), '') AS upload_path8,
                        COALESCE(MAX(CASE WHEN t.name = 'document8' THEN t.error_detail END), '') AS error_detail8,
                        COALESCE(MAX(CASE WHEN t.name = 'document8' THEN t.status END), '') AS status8
                    FROM users u
                    LEFT JOIN tbl_uploads t ON u.citizenid = t.citizenid
                    WHERE u.level = '4' 
                        AND u.typeregis = 'รอบทั่วไป'
                    GROUP BY u.id
                    ORDER BY u.create_at ASC
                    ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getM4NomalStudents()
    {
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
                        number1,
                        number2,
                        number3,
                        number4,
                        number5,
                        number6,
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

    public function getStudentById($id)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Fetch plans linked to this specific registration ID
            $planQuery = "SELECT plan_id, priority FROM student_study_plans WHERE user_id = :userId ORDER BY priority ASC";
            $planStmt = $this->conn->prepare($planQuery);
            $planStmt->bindParam(':userId', $row['id']);
            $planStmt->execute();
            $plans = $planStmt->fetchAll(PDO::FETCH_ASSOC);

            // Map plans to numberX keys
            for ($i = 1; $i <= 10; $i++) {
                $row['number' . $i] = '';
            }
            foreach ($plans as $plan) {
                if ($plan['priority'] >= 1 && $plan['priority'] <= 10) {
                    $row['number' . $plan['priority']] = $plan['plan_id'];
                }
            }

            // Format birthday for frontend if needed (it was using CONCAT previously)
            if ($row['date_birth'] && $row['month_birth'] && $row['year_birth']) {
                $row['birthday'] = $row['date_birth'] . '-' . $row['month_birth'] . '-' . $row['year_birth'];
            }
        }
        return $row;
    }


    public function getStudentByCitizId($id)
    {
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

    public function updateStudent($id, $data, $plans = [])
    {
        try {
            $this->conn->beginTransaction();

            // Define all possible updatable fields
            $allFields = [
                'citizenid',
                'numreg',
                'stu_prefix',
                'stu_name',
                'stu_lastname',
                'stu_sex',
                'stu_blood_group',
                'stu_religion',
                'stu_ethnicity',
                'stu_nationality',
                'date_birth',
                'month_birth',
                'year_birth',
                'now_tel',
                'old_school',
                'old_school_province',
                'old_school_district',
                'old_school_stuid',
                'now_addr',
                'now_moo',
                'now_soy',
                'now_street',
                'now_subdistrict',
                'now_district',
                'now_province',
                'now_post',
                'dad_prefix',
                'dad_name',
                'dad_lastname',
                'dad_job',
                'dad_tel',
                'mom_prefix',
                'mom_name',
                'mom_lastname',
                'mom_job',
                'mom_tel',
                'parent_prefix',
                'parent_name',
                'parent_lastname',
                'parent_relation',
                'parent_job',
                'parent_tel',
                'gpa_total',
                'grade_science',
                'grade_math',
                'grade_english',
                'seat_number',
                'exam_room',
                'exam_date',
                'typeregis'
            ];

            // Build dynamic query with only fields that exist in $data
            $setClauses = [];
            $params = [];

            foreach ($allFields as $field) {
                if (array_key_exists($field, $data)) {
                    $setClauses[] = "$field = :$field";
                    $val = $data[$field];

                    // Convert empty strings to NULL for numeric/decimal fields
                    $numericFields = ['gpa_total', 'grade_science', 'grade_math', 'grade_english', 'old_school_stuid'];
                    if (in_array($field, $numericFields) && $val === '') {
                        $val = null;
                    }

                    $params[":$field"] = $val;
                }
            }

            if (empty($setClauses)) {
                throw new Exception("No valid fields to update");
            }

            $query = "UPDATE users SET " . implode(", ", $setClauses) . " WHERE id = :id";
            $params[':id'] = $id;

            $stmt = $this->conn->prepare($query);

            foreach ($params as $param => $val) {
                $stmt->bindValue($param, $val);
            }

            if (!$stmt->execute()) {
                throw new Exception("Update users failed");
            }

            // Update Plans
            if (!empty($plans)) {
                $delQuery = "DELETE FROM student_study_plans WHERE user_id = :userId";
                $delStmt = $this->conn->prepare($delQuery);
                $delStmt->bindValue(':userId', $id);
                $delStmt->execute();

                $insQuery = "INSERT INTO student_study_plans (user_id, citizenid, plan_id, priority) VALUES (:userId, :citizenid, :plan_id, :priority)";
                $insStmt = $this->conn->prepare($insQuery);

                foreach ($plans as $priority => $plan_id) {
                    if (!empty($plan_id)) {
                        $insStmt->bindValue(':userId', $id);
                        $insStmt->bindValue(':citizenid', $data['citizenid']);
                        $insStmt->bindValue(':plan_id', $plan_id);
                        $insStmt->bindValue(':priority', $priority);
                        $insStmt->execute();
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            // Return error message for debugging
            return $e->getMessage();
        }
    }
    public function updateStudentM1($id, $citizenid, $typeregis, $stu_prefix, $stu_name, $stu_lastname, $date_birth, $month_birth, $year_birth, $now_tel, $parent_tel, $gpa_total, $number1, $number2, $number3, $number4, $number5, $number6, $number7, $number8, $number9, $number10)
    {
        $query = "UPDATE users 
                    SET 
                        citizenid = :citizenid, 
                        typeregis = :typeregis, 
                        stu_prefix = :stu_prefix, 
                        stu_name = :stu_name, 
                        stu_lastname = :stu_lastname, 
                        date_birth = :date_birth, 
                        month_birth = :month_birth, 
                        year_birth = :year_birth, 
                        now_tel = :now_tel, 
                        parent_tel = :parent_tel,
                        gpa_total = :gpa_total,
                        number1 = :number1,
                        number2 = :number2,
                        number3 = :number3,
                        number4 = :number4,
                        number5 = :number5,
                        number6 = :number6,
                        number7 = :number7,
                        number8 = :number8,
                        number9 = :number9,
                        number10 = :number10
                    WHERE id = :id";

        // Prepare the query
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':citizenid', $citizenid);
        $stmt->bindParam(':typeregis', $typeregis);
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
        $stmt->bindParam(':number1', $number1);
        $stmt->bindParam(':number2', $number2);
        $stmt->bindParam(':number3', $number3);
        $stmt->bindParam(':number4', $number4);
        $stmt->bindParam(':number5', $number5);
        $stmt->bindParam(':number6', $number6);
        $stmt->bindParam(':number7', $number7);
        $stmt->bindParam(':number8', $number8);
        $stmt->bindParam(':number9', $number9);
        $stmt->bindParam(':number10', $number10);

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
    public function updateStudentM4($id, $citizenid, $typeregis, $stu_prefix, $stu_name, $stu_lastname, $date_birth, $month_birth, $year_birth, $now_tel, $parent_tel, $gpa_total, $number1, $number2, $number3, $number4, $number5, $number6)
    {
        $query = "UPDATE users 
                    SET 
                        citizenid = :citizenid, 
                        typeregis = :typeregis, 
                        stu_prefix = :stu_prefix, 
                        stu_name = :stu_name, 
                        stu_lastname = :stu_lastname, 
                        date_birth = :date_birth, 
                        month_birth = :month_birth, 
                        year_birth = :year_birth, 
                        now_tel = :now_tel, 
                        parent_tel = :parent_tel,
                        gpa_total = :gpa_total,
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
        $stmt->bindParam(':typeregis', $typeregis);
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

    public function getStudentByCitizenId($citizen_id)
    {
        $query = "SELECT 
                        id,
                        citizenid,
                        typeregis,
                        level, 
                        CONCAT_WS('-', date_birth, month_birth, year_birth) AS birthday, 
                        stu_prefix,
                        stu_name,
                        stu_lastname, 
                        now_tel, 
                        parent_tel,
                        final_plan_id,
                        status
                    FROM users 
                    WHERE citizenid = :citizen_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':citizen_id', $citizen_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentBySearchInput($search_input)
    {
        $query = "SELECT 
                        users.id,
                        users.citizenid,
                        users.typeregis,
                        users.level, 
                        CONCAT_WS('-', users.date_birth, users.month_birth, users.year_birth) AS birthday, 
                        users.stu_prefix,
                        users.stu_name,
                        users.stu_lastname, 
                        users.now_tel, 
                        users.parent_tel,
                        users.numreg,
                        users.status
                    FROM users
                    WHERE 
                        users.citizenid = :search_input 
                        OR CONCAT(users.stu_prefix, users.stu_name, ' ', users.stu_lastname) LIKE :search_input_like
                    ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':search_input', $search_input);
        $search_input_like = '%' . $search_input . '%';
        $stmt->bindParam(':search_input_like', $search_input_like);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentBySearchInputM1($search_input)
    {
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
                    WHERE level = 1 AND citizenid = :search_input OR CONCAT(stu_prefix, stu_name, ' ', stu_lastname) LIKE :search_input_like";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':search_input', $search_input);
        $search_input_like = '%' . $search_input . '%';
        $stmt->bindParam(':search_input_like', $search_input_like);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentBySearchInputM4($search_input)
    {
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
                    WHERE level = 4 AND citizenid = :search_input OR CONCAT(stu_prefix, stu_name, ' ', stu_lastname) LIKE :search_input_like";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':search_input', $search_input);
        $search_input_like = '%' . $search_input . '%';
        $stmt->bindParam(':search_input_like', $search_input_like);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentBySearchInputConfirm($search_input)
    {
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


    public function deleteStudent($id)
    {
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

    public function getDailyRegistrationCounts($startDate, $endDate)
    {
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

    public function updateConfirmStatus($numreg, $status)
    {
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
    public function updateStatus($id, $status)
    {
        $query = "UPDATE users 
                  SET status = :status,
                      update_at = NOW() 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);

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

    public function countConfirm($status, $level, $typeregis, $year)
    {
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
    public function countRegis($level, $typeregis, $year)
    {
        $sql = "SELECT COUNT(*) AS total_count 
                FROM users
                WHERE level = :level
                AND typeregis = :typeregis
                AND reg_pee = :year";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':level', $level, PDO::PARAM_INT);
        $stmt->bindParam(':typeregis', $typeregis, PDO::PARAM_STR);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total_count'] ?? 0;
    }

    public function getStudentPlans($citizenid)
    {
        $query = "SELECT * FROM student_study_plans WHERE citizenid = :citizenid ORDER BY priority ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':citizenid', $citizenid);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getStudentsPassed($level, $typeName, $date = null)
    {
        // Prepare Level Criteria
        $levels = [$level];
        if ($level == '1')
            $levels[] = 'm1';
        if ($level == '4')
            $levels[] = 'm4';
        $levelStr = "'" . implode("','", $levels) . "'";

        $sql = "SELECT 
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
                    status,
                    update_at,
                    final_plan_id
                FROM users 
                WHERE level IN ($levelStr) AND status = 1";

        // Special case for M.1 General (Zone)
        if (($level == '1' || $level == 'm1') && $typeName == 'รอบทั่วไป') {
            $sql .= " AND (typeregis = 'ในเขต' OR typeregis = 'นอกเขต')";
        } else {
            $sql .= " AND typeregis = :typeName";
        }

        if ($date) {
            $sql .= " AND DATE(update_at) = :date";
        }

        $sql .= " ORDER BY update_at ASC";

        $stmt = $this->conn->prepare($sql);
        // $stmt->bindParam(':level', $level); // Removed
        if (!(($level == '1' || $level == 'm1') && $typeName == 'รอบทั่วไป')) {
            $stmt->bindParam(':typeName', $typeName);
        }
        if ($date) {
            $stmt->bindParam(':date', $date);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>