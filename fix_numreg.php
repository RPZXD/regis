<?php
require_once 'config/Database.php';

// Reuse the generation logic
function generateRegNumber($db, $level, $year, $typeId)
{
    $yearShort = substr($year, -2);
    $levelNum = str_replace('m', '', $level);
    $typeNum = str_pad($typeId, 2, '0', STR_PAD_LEFT);
    $prefix = $yearShort . $levelNum . $typeNum;

    $sql = "SELECT MAX(CAST(SUBSTRING_INDEX(numreg, '-', -1) AS UNSIGNED)) as max_seq 
            FROM users 
            WHERE numreg LIKE ? AND reg_pee = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$prefix . '-%', $year]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $nextSeq = ($result['max_seq'] ?? 0) + 1;
    return $prefix . '-' . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
}

try {
    $dbClass = new Database_Regis();
    $db = $dbClass->getConnection();

    // 1. Get Type Mapping
    $mapping = [];
    $stmt = $db->query("SELECT rt.id, rt.name, gl.code FROM registration_types rt JOIN grade_levels gl ON rt.grade_level_id = gl.id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $key = $row['name'] . '|' . strtolower($row['code']);
        $mapping[$key] = $row['id'];
    }

    // 2. Find users with missing numbers
    $sql = "SELECT id, citizenid, reg_pee, level, typeregis FROM users 
            WHERE numreg = '00000' OR numreg = '' OR numreg IS NULL";
    $stmt = $db->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($users) . " users with missing numbers.\n<br>";

    foreach ($users as $user) {
        $key = $user['typeregis'] . '|' . strtolower($user['level']);
        if (isset($mapping[$key])) {
            $typeId = $mapping[$key];
            $newNum = generateRegNumber($db, $user['level'], $user['reg_pee'], $typeId);

            $update = $db->prepare("UPDATE users SET numreg = ? WHERE id = ?");
            $update->execute([$newNum, $user['id']]);

            echo "Updated ID {$user['id']}: {$user['typeregis']} (ม.{$user['level']}) -> {$newNum}\n<br>";
        } else {
            echo "Skipping ID {$user['id']}: Unknown type '{$user['typeregis']}' for level '{$user['level']}'\n<br>";
        }
    }

    echo "Done!\n<br>";
    echo "<b>IMPORTANT: Please delete this file (fix_numreg.php) after use.</b>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
