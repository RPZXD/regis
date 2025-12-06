-- Admin Configuration System Database Schema
-- Run this SQL in phpMyAdmin or MySQL CLI

-- 1. ตาราง settings - ตั้งค่าทั่วไป
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `key_name` VARCHAR(100) UNIQUE NOT NULL,
    `value` TEXT,
    `description` VARCHAR(255),
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ตาราง menu_config - เปิด/ปิดเมนู
CREATE TABLE IF NOT EXISTS `menu_config` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `menu_key` VARCHAR(50) UNIQUE NOT NULL,
    `menu_name` VARCHAR(100) NOT NULL,
    `menu_url` VARCHAR(255),
    `is_enabled` TINYINT(1) DEFAULT 1,
    `use_schedule` TINYINT(1) DEFAULT 0,
    `start_datetime` DATETIME NULL,
    `end_datetime` DATETIME NULL,
    `icon` VARCHAR(50) DEFAULT 'fa-circle',
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. ตาราง grade_levels - ระดับชั้น
CREATE TABLE IF NOT EXISTS `grade_levels` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `code` VARCHAR(10) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. ตาราง registration_types - ประเภทการสมัคร
CREATE TABLE IF NOT EXISTS `registration_types` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `grade_level_id` INT NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `is_active` TINYINT(1) DEFAULT 1,
    `use_schedule` TINYINT(1) DEFAULT 0,
    `start_datetime` DATETIME NULL,
    `end_datetime` DATETIME NULL,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`grade_level_id`) REFERENCES `grade_levels`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. ตาราง study_plans - แผนการเรียน
CREATE TABLE IF NOT EXISTS `study_plans` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `registration_type_id` INT NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `description` TEXT,
    `quota` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`registration_type_id`) REFERENCES `registration_types`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. ตาราง form_fields - กำหนดฟิลด์ที่ต้องกรอก
CREATE TABLE IF NOT EXISTS `form_fields` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `study_plan_id` INT NULL,
    `registration_type_id` INT NULL,
    `field_key` VARCHAR(50) NOT NULL,
    `field_label` VARCHAR(100) NOT NULL,
    `field_type` VARCHAR(20) NOT NULL DEFAULT 'text',
    `placeholder` VARCHAR(255),
    `is_required` TINYINT(1) DEFAULT 1,
    `options` JSON NULL,
    `validation_rules` VARCHAR(255),
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`study_plan_id`) REFERENCES `study_plans`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`registration_type_id`) REFERENCES `registration_types`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Insert Default Data
-- ========================================

-- Default Settings
INSERT INTO `settings` (`key_name`, `value`, `description`) VALUES
('academic_year', '2568', 'ปีการศึกษาที่รับสมัคร'),
('school_name', 'โรงเรียนพิชัยรัตนาคาร', 'ชื่อโรงเรียน'),
('school_phone', '055-123456', 'เบอร์โทรโรงเรียน'),
('registration_open', '1', 'เปิดระบบรับสมัคร (1=เปิด, 0=ปิด)')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

-- Default Menu Config
INSERT INTO `menu_config` (`menu_key`, `menu_name`, `menu_url`, `icon`, `sort_order`) VALUES
('register', 'สมัครเรียน', 'regis.php', 'fa-user-plus', 1),
('check_reg', 'เช็คการสมัคร', 'checkreg.php', 'fa-search', 2),
('print_form', 'พิมพ์ใบสมัคร', 'print.php', 'fa-print', 3),
('upload', 'อัพโหลดหลักฐาน', 'upload.php', 'fa-upload', 4),
('check_status', 'ตรวจสอบสถานะ', 'check_uploads.php', 'fa-tasks', 5),
('exam_card', 'พิมพ์บัตรสอบ', 'print_reg.php', 'fa-id-card', 6),
('confirm', 'รายงานตัว', 'confirm.php', 'fa-check-circle', 7),
('announce', 'ประกาศผล', 'annouce.php', 'fa-bullhorn', 8)
ON DUPLICATE KEY UPDATE `menu_name` = VALUES(`menu_name`);

-- Default Grade Levels
INSERT INTO `grade_levels` (`code`, `name`, `sort_order`) VALUES
('m1', 'มัธยมศึกษาปีที่ 1', 1),
('m4', 'มัธยมศึกษาปีที่ 4', 2)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Default Registration Types for M.1
INSERT INTO `registration_types` (`grade_level_id`, `code`, `name`, `sort_order`) VALUES
(1, 'special', 'ห้องเรียนพิเศษ', 1),
(1, 'general', 'รอบทั่วไป', 2),
(1, 'talent', 'ความสามารถพิเศษ', 3);

-- Default Registration Types for M.4
INSERT INTO `registration_types` (`grade_level_id`, `code`, `name`, `sort_order`) VALUES
(2, 'special', 'ห้องเรียนพิเศษ', 1),
(2, 'general', 'รอบทั่วไป', 2),
(2, 'quota', 'โควต้า ม.3 เดิม', 3),
(2, 'talent', 'ความสามารถพิเศษ', 4);
