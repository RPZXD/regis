-- Document Upload System - Database Migration
-- Run this SQL in phpMyAdmin

-- 1. Document Requirements Table (admin configurable per registration type)
CREATE TABLE IF NOT EXISTS `document_requirements` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `registration_type_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `is_required` TINYINT(1) DEFAULT 1,
    `file_types` VARCHAR(100) DEFAULT 'jpg,jpeg,png,pdf',
    `max_size_mb` INT DEFAULT 5,
    `sort_order` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`registration_type_id`) REFERENCES `registration_types`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Student Documents Table (uploaded files)
CREATE TABLE IF NOT EXISTS `student_documents` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `citizenid` VARCHAR(13) NOT NULL,
    `requirement_id` INT NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255),
    `file_size` INT DEFAULT 0,
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `reviewed_by` INT NULL,
    `reviewed_at` TIMESTAMP NULL,
    `reject_reason` TEXT,
    FOREIGN KEY (`requirement_id`) REFERENCES `document_requirements`(`id`) ON DELETE CASCADE,
    INDEX `idx_citizenid` (`citizenid`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default document requirements (examples)
-- You can add more via admin UI
