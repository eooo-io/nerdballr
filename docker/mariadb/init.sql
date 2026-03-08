-- FFIS — MariaDB initialisation
-- Runs once when the volume is first created.

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- Ensure the application database uses the correct charset.
ALTER DATABASE `ffis`
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Set a default password for the ffis user.
ALTER USER 'ffis'@'%' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;
