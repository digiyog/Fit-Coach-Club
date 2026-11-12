-- -------------------------------------------------------------
-- Fit Coach Club - App Configurations Table & Rating Table Update
-- -------------------------------------------------------------

-- 1. Create table if not exists (initial schema)
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
  `splash_video_enabled` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = Video Splash Enabled, 0 = Normal Splash',
  `splash_video_url` TEXT DEFAULT NULL,
  `splash_video_start_date` DATE DEFAULT NULL,
  `splash_video_end_date` DATE DEFAULT NULL,
  `rating_required` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = Rating Popup Required, 0 = Disabled',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Add columns if table was created in an earlier release without them
SET @dbname = DATABASE();
SET @tablename = "app_configs";

-- Add splash_video_enabled
SET @columnname = "splash_video_enabled";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname
  ) > 0,
  "SELECT 1",
  "ALTER TABLE `app_configs` ADD COLUMN `splash_video_enabled` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = Video Splash Enabled, 0 = Normal Splash' AFTER `ios_store_url`"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add splash_video_url
SET @columnname = "splash_video_url";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname
  ) > 0,
  "SELECT 1",
  "ALTER TABLE `app_configs` ADD COLUMN `splash_video_url` TEXT DEFAULT NULL AFTER `splash_video_enabled`"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add splash_video_start_date
SET @columnname = "splash_video_start_date";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname
  ) > 0,
  "SELECT 1",
  "ALTER TABLE `app_configs` ADD COLUMN `splash_video_start_date` DATE DEFAULT NULL AFTER `splash_video_url`"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add splash_video_end_date
SET @columnname = "splash_video_end_date";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname
  ) > 0,
  "SELECT 1",
  "ALTER TABLE `app_configs` ADD COLUMN `splash_video_end_date` DATE DEFAULT NULL AFTER `splash_video_start_date`"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add rating_required
SET @columnname = "rating_required";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname
  ) > 0,
  "SELECT 1",
  "ALTER TABLE `app_configs` ADD COLUMN `rating_required` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = Rating Popup Required, 0 = Disabled' AFTER `splash_video_end_date`"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 3. Now insert default configuration row if not exists
INSERT INTO `app_configs` (`id`, `popup_title`, `popup_message`, `popup_status`, `android_latest_version`, `android_force_update`, `android_store_url`, `ios_latest_version`, `ios_force_update`, `ios_store_url`, `splash_video_enabled`, `splash_video_url`, `splash_video_start_date`, `splash_video_end_date`, `rating_required`, `created_at`, `updated_at`)
VALUES (1, '', '', 1, '1.0.0', 0, '', '1.0.0', 0, '', 0, '', NULL, NULL, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 4. Update ratings column to support string-compatible decimal values (e.g. 1.0 to 5.0)
ALTER TABLE `ratings` MODIFY COLUMN `rating` DECIMAL(3,1) DEFAULT 1.0 NULL;
