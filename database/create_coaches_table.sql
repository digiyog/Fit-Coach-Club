-- -------------------------------------------------------------
-- Fit Coach Club - Coaches Table SQL for Server Migration
-- Table: coaches
-- -------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `coaches` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `franchise_id` BIGINT(20) UNSIGNED DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `mobile_number` VARCHAR(255) DEFAULT NULL,
  `profile_image` VARCHAR(255) DEFAULT NULL,
  `specialization` VARCHAR(255) DEFAULT NULL,
  `experience_years` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `status` TINYINT(4) NOT NULL DEFAULT 1 COMMENT '1 = Active, 0 = Inactive',
  `created_by` BIGINT(20) UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coaches_franchise_id_index` (`franchise_id`),
  KEY `coaches_created_by_index` (`created_by`),
  KEY `coaches_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
