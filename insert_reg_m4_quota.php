<?php
require_once 'config/Database.php'; // เชื่อมต่อกับฐานข้อมูล

header('Content-Type: application/json');

// สร้างการเชื่อมต่อฐานข้อมูล
$databaseRegis = new Database_Regis();
$db = $databaseRegis->getConnection();

try {
    // ตั้งค่าภาษาเป็น UTF-8
    $db->exec("set names utf8");

    // รับค่าการตั้งค่าปีการศึกษา
    $settings_stmt = $db->prepare("SELECT year FROM setting");
    $settings_stmt->execute();
    $settings = $settings_stmt->fetch(PDO::FETCH_ASSOC);
    $pee = $settings['year'];

    // รับข้อมูลจากฟอร์ม
    $data = [
        'citizenid' => isset($_POST['citizenid']) ? strip_tags(str_replace('-', '', $_POST['citizenid'])) : '',
        'date_birth' => $_POST['date_birth'],
        'month_birth' => $_POST['month_birth'],
        'year_birth' => $_POST['year_birth'],
        'stu_prefix' => $_POST['stu_prefix'],
        'stu_name' => $_POST['stu_name'],
        'stu_lastname' => $_POST['stu_lastname'],
        'typeregis' => $_POST['typeregis'],
        'stu_sex' => $_POST['stu_sex'],
        'stu_blood_group' => $_POST['stu_blood_group'],
        'stu_religion' => $_POST['stu_religion'],
        'stu_ethnicity' => $_POST['stu_ethnicity'],
        'stu_nationality' => $_POST['stu_nationality'],
        'old_school' => $_POST['old_school'],
        'old_school_stuid' => $_POST['old_school_stuid'],
        'old_school_province' => $_POST['old_school_province'],
        'old_school_district' => $_POST['old_school_district'],
        'now_addr' => $_POST['now_addr'],
        'now_moo' => $_POST['now_moo'],
        'now_soy' => $_POST['now_soy'],
        'now_street' => $_POST['now_street'],
        'now_tel' => $_POST['now_tel'],
        'now_province' => $_POST['now_province'],
        'now_district' => $_POST['now_district'],
        'now_subdistrict' => $_POST['now_subdistrict'],
        'now_post' => $_POST['now_post'],
        'old_addr' => $_POST['old_addr'],
        'old_moo' => $_POST['old_moo'],
        'old_soy' => $_POST['old_soy'],
        'old_street' => $_POST['old_street'],
        'old_tel' => $_POST['old_tel'],
        'old_province' => $_POST['old_province'],
        'old_district' => $_POST['old_district'],
        'old_subdistrict' => $_POST['old_subdistrict'],
        'old_post' => $_POST['old_post'],
        'dad_prefix' => $_POST['dad_prefix'],
        'dad_name' => $_POST['dad_name'],
        'dad_lastname' => $_POST['dad_lastname'],
        'dad_job' => $_POST['dad_job'],
        'dad_tel' => $_POST['dad_tel'],
        'mom_prefix' => $_POST['mom_prefix'],
        'mom_name' => $_POST['mom_name'],
        'mom_lastname' => $_POST['mom_lastname'],
        'mom_job' => $_POST['mom_job'],
        'mom_tel' => $_POST['mom_tel'],
        'parent_prefix' => $_POST['parent_prefix'],
        'parent_name' => $_POST['parent_name'],
        'parent_lastname' => $_POST['parent_lastname'],
        'parent_tel' => $_POST['parent_tel'],
        'parent_relation' => $_POST['parent_relation'],
        'gpa_total' =>  $_POST['gpa_total'],
        'number1' => $_POST['number1'],
        'number2' => $_POST['number2'],
        'number3' => $_POST['number3'],
        'number4' => $_POST['number4'],
        'number5' => $_POST['number5'],
        'number6' => $_POST['number6'],
        'level' => '4',
        'roles' => 0,
        'reg_pee' => $pee,
        'create_at' => date('Y-m-d H:i:s'),
    ];

    // ฟังก์ชันสำหรับแปลง code เป็นชื่อ
    function getNameByCode($db, $table, $code) {
        $stmt = $db->prepare("SELECT name_th FROM $table WHERE code = :code");
        $stmt->execute([':code' => $code]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['name_th'] : '';
    }

    // แปลงค่าต่างๆ เป็นชื่อแทน
    $data['old_school_province'] = getNameByCode($db, 'province', $data['old_school_province']);
    $data['old_school_district'] = getNameByCode($db, 'district', $data['old_school_district']);
    $data['old_province'] = getNameByCode($db, 'province', $data['old_province']);
    $data['old_district'] = getNameByCode($db, 'district', $data['old_district']);
    $data['old_subdistrict'] = getNameByCode($db, 'subdistrict', $data['old_subdistrict']);
    $data['now_province'] = getNameByCode($db, 'province', $data['now_province']);
    $data['now_district'] = getNameByCode($db, 'district', $data['now_district']);
    $data['now_subdistrict'] = getNameByCode($db, 'subdistrict', $data['now_subdistrict']);

    // ตรวจสอบว่า citizenid ซ้ำหรือไม่
    $check_stmt = $db->prepare("SELECT citizenid FROM users WHERE citizenid = :citizenid");
    $check_stmt->bindParam(':citizenid', $data['citizenid']);
    $check_stmt->execute();
    $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        echo json_encode(['success' => false, 'message' => 'หมายเลขบัตรประชาชนนี้ได้ถูกใช้ไปแล้ว']);
        exit;
    }

    // บันทึกข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO users (
                citizenid, date_birth, month_birth, year_birth, stu_prefix, stu_name, stu_lastname, 
                level, roles, reg_pee, create_at, typeregis, stu_sex, stu_blood_group, stu_religion, 
                stu_ethnicity, stu_nationality, old_school, old_school_stuid, old_school_province, old_school_district, 
                now_addr, now_moo, now_soy, now_street, now_tel, now_province, now_district, 
                now_subdistrict, now_post, old_addr, old_moo, old_soy, old_street, old_tel, 
                old_province, old_district, old_subdistrict, old_post, dad_prefix, dad_name, 
                dad_lastname, dad_job, dad_tel, mom_prefix, mom_name, mom_lastname, mom_job, 
                mom_tel, parent_prefix, parent_name, parent_lastname, parent_tel, parent_relation, 
                gpa_total, number1, number2, number3, number4, number5, number6
            ) VALUES (
                :citizenid, :date_birth, :month_birth, :year_birth, :stu_prefix, :stu_name, :stu_lastname, 
                :level, :roles, :reg_pee, :create_at, :typeregis, :stu_sex, :stu_blood_group, :stu_religion, 
                :stu_ethnicity, :stu_nationality, :old_school, :old_school_stuid, :old_school_province, :old_school_district, 
                :now_addr, :now_moo, :now_soy, :now_street, :now_tel, :now_province, :now_district, 
                :now_subdistrict, :now_post, :old_addr, :old_moo, :old_soy, :old_street, :old_tel, 
                :old_province, :old_district, :old_subdistrict, :old_post, :dad_prefix, :dad_name, 
                :dad_lastname, :dad_job, :dad_tel, :mom_prefix, :mom_name, :mom_lastname, :mom_job, 
                :mom_tel, :parent_prefix, :parent_name, :parent_lastname, :parent_tel, :parent_relation, 
                :gpa_total, :number1, :number2, :number3, :number4, :number5, :number6
            )";

    $stmt = $db->prepare($sql);
    $stmt->execute($data);

    echo json_encode(['success' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
