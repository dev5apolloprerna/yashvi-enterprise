-- =============================================================
-- Yashvi Enterprise - Blog Management System
-- Full Database Schema + Seed Data
-- Import this directly via phpMyAdmin, or run migrations instead
-- (see README.md). Either approach works — use ONE of them.
-- =============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `yashvi_enterprise` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `yashvi_enterprise`;

-- -------------------------------------------------------------
-- Table: users  (admin / authors login here)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin login:
--   Email:    admin@yashvienterprise.com
--   Password: Admin@123   (hash below is bcrypt of "Admin@123")
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`)
VALUES (1, 'Yashvi Admin', 'admin@yashvienterprise.com', NOW(),
        '$2y$10$7KWpclsMpqDMwrVLc3WM3OgwVTikyogSXhwi77rB3ruDMogwWlt1O',
        NULL, NOW(), NOW());

-- -------------------------------------------------------------
-- Table: blogs
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `short_description` VARCHAR(500) NULL DEFAULT NULL,
  `description` LONGTEXT NOT NULL,
  `status` ENUM('published','draft') NOT NULL DEFAULT 'published',
  `views` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogs_slug_unique` (`slug`),
  KEY `blogs_status_created_at_index` (`status`, `created_at`),
  CONSTRAINT `blogs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample demo blog posts
INSERT INTO `blogs` (`user_id`, `title`, `slug`, `image`, `short_description`, `description`, `status`, `views`, `created_at`, `updated_at`)
VALUES
(1, 'Welcome to Yashvi Enterprise Blog', 'welcome-to-yashvi-enterprise-blog', NULL,
 'A quick introduction to what you can expect from our blog.',
 '<p>Welcome to the official blog of <strong>Yashvi Enterprise</strong>. Here we will share company updates, industry insights, and helpful articles for our customers.</p>',
 'published', 0, NOW(), NOW()),

(1, 'Why Quality Matters in Everything We Do', 'why-quality-matters-in-everything-we-do', NULL,
 'A look at our commitment to quality and customer satisfaction.',
 '<p>At Yashvi Enterprise, quality is not an afterthought — it is built into every step of our process. In this article we explore how our team ensures the best outcomes for every client.</p>',
 'published', 0, NOW(), NOW()),

(1, '5 Tips for Growing Your Business in 2026', '5-tips-for-growing-your-business-in-2026', NULL,
 'Practical, actionable tips to help your business grow this year.',
 '<p>Growing a business takes strategy and consistency. Here are five practical tips that have helped our clients scale successfully this year.</p>',
 'published', 0, NOW(), NOW());

-- -------------------------------------------------------------
-- Laravel housekeeping tables (needed if you import this SQL
-- instead of running migrations)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
