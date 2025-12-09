<?php
require_once __DIR__ . '/../config/Database.php';

try {
    $database = new Database_Regis();
    $db = $database->getConnection();

    // 1. Create study_plan_fees table
    $sql = "CREATE TABLE IF NOT EXISTS study_plan_fees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        plan_id INT NOT NULL,
        category ENUM('maintenance', 'support') NOT NULL COMMENT 'ประเภท: เงินบำรุงการศึกษา, ค่าใช้จ่ายสนับสนุน',
        item_name VARCHAR(255) NOT NULL,
        term1_amount DECIMAL(10, 2) DEFAULT 0.00,
        term2_amount DECIMAL(10, 2) DEFAULT 0.00,
        sort_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX (plan_id),
        INDEX (category)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $db->exec($sql);
    echo "Table 'study_plan_fees' created/checked.\n";

    // 2. Add final_plan_id to users
    $check = $db->prepare("SHOW COLUMNS FROM users LIKE 'final_plan_id'");
    $check->execute();
    
    if ($check->rowCount() == 0) {
        $sql = "ALTER TABLE users ADD COLUMN final_plan_id INT DEFAULT NULL COMMENT 'แผนการเรียนที่ได้รับคัดเลือก' AFTER exam_date";
        $db->exec($sql);
        echo "Column 'final_plan_id' added to 'users'.\n";
    } else {
        echo "Column 'final_plan_id' already exists.\n";
    }

    echo "Database setup completed successfully.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
