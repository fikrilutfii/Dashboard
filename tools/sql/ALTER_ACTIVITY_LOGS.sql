ALTER TABLE `activity_logs`
ADD COLUMN `subject_type` VARCHAR(255) NULL AFTER `description`,
ADD COLUMN `subject_id` BIGINT UNSIGNED NULL AFTER `subject_type`,
ADD COLUMN `properties` JSON NULL AFTER `subject_id`;
