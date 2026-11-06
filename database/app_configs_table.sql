-- -------------------------------------------------------------
-- Fit Coach Club - App Configurations Table & Rating Table Update
-- -------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `app_configs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `popup_title` VARCHAR(255) DEFAULT NULL,
  `popup_message` TEXT DEFAULT NULL,
  `popup_status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = ON, 0 = OFF',
  `android_latest_version` VARCHAR(50) NOT NULL DEFAULT '1.0.0',
  `android_force_update` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = Force Update, 0 = Optional',
  `android_store_url` TEXT DEFAULT NULL,
  `ios_latest_version` VARCHAR(50) NOT NULL DEFAULT '1.0.0',
  `ios_force_update` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = Force Update, 0 = Optional',
  `ios_store_url` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default configuration row if not exists
INSERT INTO `app_configs` (`id`, `popup_title`, `popup_message`, `popup_status`, `android_latest_version`, `android_force_update`, `android_store_url`, `ios_latest_version`, `ios_force_update`, `ios_store_url`, `created_at`, `updated_at`)
VALUES (1, '', '', 1, '1.0.0', 0, '', '1.0.0', 0, '', NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Update ratings column to support string-compatible decimal values (e.g. 1.0 to 5.0)
ALTER TABLE `ratings` MODIFY COLUMN `rating` DECIMAL(3,1) DEFAULT 1.0 NULL;

