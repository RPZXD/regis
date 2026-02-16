<?php
/**
 * Database Migration Script
 * Purpose: Add 'user_id' column to 'student_study_plans' and back-fill it.
 * Run this ONCE on the production server.
 */

require_once __DIR__ . '/config/Database.php';

try {
    $connectDB = new Database_Regis();
    $db = $connectDB->getConnection();

    if (!$db) {
        throw new Exception("Could not connect to database.");
    }

    echo "Starting migration...<br>";

    // 1. Check if column already exists
    $checkColumn = $db->query("SHOW COLUMNS FROM student_study_plans LIKE 'user_id'");
    if ($checkColumn->rowCount() == 0) {
        echo "Adding 'user_id' column to student_study_plans...<br>";
        $db->exec("ALTER TABLE student_study_plans ADD COLUMN user_id INT(11) AFTER id");
        $db->exec("ALTER TABLE student_study_plans ADD INDEX (user_id)");
        echo "Column added successfully.<br>";
    } else {
        echo "Column 'user_id' already exists. Proceeding to back-fill...<br>";
    }

    // 2. Back-fill user_id from users table
    // We match by citizenid. For existing data, we assume it belongs to the registration
    // that was active when the plan was created. To be safe, we match the LATEST 
    // registration for that citizenid if multiple exist, or just the single one.
    echo "Back-filling user_id values...<br>";

    $updateSql = "UPDATE student_study_plans ssp 
                  SET ssp.user_id = (
                      SELECT u.id 
                      FROM users u 
                      WHERE u.citizenid = ssp.citizenid 
                      ORDER BY u.id DESC 
                      LIMIT 1
                  )
                  WHERE ssp.user_id IS NULL OR ssp.user_id = 0";

    $affected = $db->exec($updateSql);
    echo "Migration completed. Affected rows: $affected<br>";
    echo "<b>IMPORTANT: Please delete this file (migrate_production_v2.php) immediately for security.</b>";

} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage();
}
?>