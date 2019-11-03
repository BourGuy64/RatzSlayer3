SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `fights_log`;
DROP TABLE IF EXISTS `fights`;
DROP TABLE IF EXISTS `characters`;
DROP TABLE IF EXISTS `monsters`;
DROP TABLE IF EXISTS `users`;


CREATE TABLE `characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(1) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `hp` int(11) NOT NULL,
  `attack` int(11) NOT NULL,
  `def` int(11) NOT NULL,
  `agility` int(11) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `monsters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `hp` int(11) NOT NULL,
  `attack` int(11) NOT NULL,
  `def` int(11) NOT NULL,
  `agility` int(11) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `fights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_characters` int(11) NOT NULL,
  `id_characters2` int(11),
  `id_characters3` int(11),
  `id_monsters` int(11) NOT NULL,
  `id_monsters2` int(11),
  `id_monsters3` int(11),
  `winner` varchar(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_monsters` (`id_monsters`),
  KEY `id_characters` (`id_characters`),
  CONSTRAINT `fights_ibfk_1` FOREIGN KEY (`id_monsters`) REFERENCES `monsters` (`id`),
  CONSTRAINT `fights_ibfk_2` FOREIGN KEY (`id_characters`) REFERENCES `characters` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `fights_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_fights` int(11) NOT NULL,
  `id_fighter` int(11) NOT NULL,
  `fighter_type` varchar(1) NOT NULL,
  `round` int(11) NOT NULL,
  `hp` int(11) NOT NULL,
  `damage` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_fights` (`id_fights`),
  KEY `id_fighter` (`id_fighter`),
  CONSTRAINT `fights_log_ibfk_1` FOREIGN KEY (`id_fights`) REFERENCES `fights` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


SET NAMES utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` text CHARACTER SET utf8 NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'admin',	'$2y$10$4jtZb7UT.tNhwHFRVRG9fu.Guf5zZwajJH0M9Kp2uYinE/Wnmy2t6',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00');

INSERT INTO `characters` (`id`, `type`, `lastname`, `firstname`, `weight`, `size`, `hp`, `attack`, `def`, `agility`, `picture`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'c', 'Chan', 'Jackie', 95, 187, 348, 68, 79, 89, 'JackieChan.jpg', '2019-11-03 21:38:47', '2019-11-03 21:38:47', NULL),
(2, 'c', '10', 'Ben', 58, 162, 247, 61, 54, 68, 'Ben10.webp', '2019-11-03 21:40:01', '2019-11-03 21:40:01', NULL),
(3, 'c', 'Lee', 'Bruce', 110, 181, 349, 81, 74, 97, 'BruceLee.jpg', '2019-11-03 21:42:59', '2019-11-03 21:42:59', NULL),
(4, 'c', 'Li', 'Chun', 78, 174, 327, 59, 54, 84, 'ChunLi.jpg', '2019-11-03 21:45:45', '2019-11-03 21:45:45', NULL),
(5, 'c', 'Masters', 'Ken', 112, 179, 367, 71, 67, 61, 'KenMasters.webp', '2019-11-03 21:47:12', '2019-11-03 21:47:12', NULL),
(6, 'c', 'Street', 'Dhalsim', 79, 186, 315, 78, 52, 87, 'DhalsimStreet.png', '2019-11-03 21:49:57', '2019-11-03 21:49:57', NULL),
(7, 'c', 'Street', 'Ryu', 124, 179, 378, 79, 70, 56, 'RyuStreet.png', '2019-11-03 21:51:15', '2019-11-03 21:51:15', NULL),
(8, 'c', 'Hiha', 'Luigi', 48, 124, 274, 48, 41, 59, 'LuigiHiha.jfif', '2019-11-03 21:54:05', '2019-11-03 21:54:05', NULL),
(9, 'c', 'Cena', 'John', 112, 187, 368, 89, 82, 68, 'JohnCena.webp', '2019-11-03 21:56:53', '2019-11-03 21:56:53', NULL),
(10, 'c', 'Show', 'Big', 174, 214, 391, 87, 91, 42, 'BigShow.jpg', '2019-11-03 21:58:59', '2019-11-03 21:58:59', NULL);

INSERT INTO `monsters` (`id`, `type`, `name`, `weight`, `size`, `hp`, `attack`, `def`, `agility`, `picture`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'm', 'Mega Dracaufeu', 185, 238, 384, 79, 84, 61, 'Mega Dracaufeu.png', '2019-11-03 22:01:35', '2019-11-03 22:01:35', NULL),
(2, 'm', 'Florizarre', 89, 110, 321, 74, 69, 34, 'Florizarre.png', '2019-11-03 22:04:13', '2019-11-03 22:04:13', NULL),
(3, 'm', 'Pikachu', 57, 84, 328, 86, 87, 70, 'Pikachu.webp', '2019-11-03 22:06:32', '2019-11-03 22:06:32', NULL),
(4, 'm', 'Rzowski', 78, 114, 301, 48, 57, 49, 'Rzowski.jpg', '2019-11-03 22:07:34', '2019-11-03 22:07:34', NULL),
(5, 'm', 'Leon', 74, 124, 315, 79, 64, 89, 'Leon.JPG', '2019-11-03 22:08:18', '2019-11-03 22:08:18', NULL),
(6, 'm', 'King Kong', 987, 999, 400, 94, 87, 27, 'King Kong.jpg', '2019-11-03 22:11:07', '2019-11-03 22:11:07', NULL),
(7, 'm', 'Sulli', 148, 197, 357, 76, 68, 45, 'Sulli.JPG', '2019-11-03 22:12:42', '2019-11-03 22:12:42', NULL),
(8, 'm', 'Karaku', 145, 248, 368, 74, 68, 68, 'Karaku.jpg', '2019-11-03 22:14:33', '2019-11-03 22:14:33', NULL);
