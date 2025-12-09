-- Migration: Add Schedule Periods to registration_types
-- Run this SQL in phpMyAdmin or MySQL CLI

-- Registration period (สมัคร)
ALTER TABLE `registration_types` ADD COLUMN `register_start` DATETIME NULL AFTER `end_datetime`;
ALTER TABLE `registration_types` ADD COLUMN `register_end` DATETIME NULL AFTER `register_start`;

-- Print form period (พิมพ์ใบสมัคร)
ALTER TABLE `registration_types` ADD COLUMN `print_form_start` DATETIME NULL AFTER `register_end`;
ALTER TABLE `registration_types` ADD COLUMN `print_form_end` DATETIME NULL AFTER `print_form_start`;

-- Upload documents period (อัพโหลดหลักฐาน)
ALTER TABLE `registration_types` ADD COLUMN `upload_start` DATETIME NULL AFTER `print_form_end`;
ALTER TABLE `registration_types` ADD COLUMN `upload_end` DATETIME NULL AFTER `upload_start`;

-- Print exam card period (พิมพ์บัตรสอบ)
ALTER TABLE `registration_types` ADD COLUMN `exam_card_start` DATETIME NULL AFTER `upload_end`;
ALTER TABLE `registration_types` ADD COLUMN `exam_card_end` DATETIME NULL AFTER `exam_card_start`;

-- Report/Check-in period (รายงานตัว)
ALTER TABLE `registration_types` ADD COLUMN `report_start` DATETIME NULL AFTER `exam_card_end`;
ALTER TABLE `registration_types` ADD COLUMN `report_end` DATETIME NULL AFTER `report_start`;

-- Announce results period (ประกาศผล)
ALTER TABLE `registration_types` ADD COLUMN `announce_start` DATETIME NULL AFTER `report_end`;
ALTER TABLE `registration_types` ADD COLUMN `announce_end` DATETIME NULL AFTER `announce_start`;
