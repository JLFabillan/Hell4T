-- ============================================================
--  HelportNew.sql
--  Helport Database Initialization Script
--  Run this in phpMyAdmin or MySQL Workbench to set up
--  the Helport database with a default Admin account.
-- ============================================================

-- 1. Create and select the database
CREATE DATABASE IF NOT EXISTS `helport_db`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `helport_db`;

-- 2. Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id`         INT          NOT NULL AUTO_INCREMENT,
    `username`   VARCHAR(50)  NOT NULL,
    `password`   VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- 3. Default Admin account
--    Password : Admin  (bcrypt hashed — verified by password_verify())
INSERT INTO `users` (`username`, `password`)
VALUES (
    'Admin',
    '$2y$10$P.ZhwKTsknOh12RO9pvHpuN42LImD1ZZ9gmbtynM6R0J2Bubjm4zu'
)
ON DUPLICATE KEY UPDATE `username` = `username`;   -- safe to re-run
