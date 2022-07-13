-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.3.35-MariaDB-1:10.3.35+maria~focal - mariadb.org binary distribution
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             12.0.0.6468
-- --------------------------------------------------------
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
-- Dumping database structure for db
DROP DATABASE IF EXISTS `db`;
CREATE DATABASE IF NOT EXISTS `db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `db`;
-- Dumping structure for table db.cars
DROP TABLE IF EXISTS `cars`;
CREATE TABLE IF NOT EXISTS `cars` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `make` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
                                      `model` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
                                      `first_registration` varchar(255) NOT NULL,
                                      `transmission` varchar(255) NOT NULL,
                                      `created_at` datetime DEFAULT NULL,
                                      `updated_at` datetime DEFAULT NULL,
                                      PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
-- Dumping data for table db.cars: ~0 rows (approximately)
REPLACE INTO `cars`
    (`id`, `make`, `model`, `first_registration`, `transmission`, `created_at`, `updated_at`)
VALUES
(1, 'mercedes', 's', '2019', 'automatic', NULL, NULL),
(2, 'bmw', '5', '2020', 'automatic', '2022-07-13 14:40:40', NULL),
(3, 'opel', 'corsa', '2021', 'manual', NULL, NULL),
(4, 'audi', 'a8', '2022', 'automatic', NULL, NULL);
/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

