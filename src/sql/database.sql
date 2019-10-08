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
  `id_monsters` int(11) NOT NULL,
  `winner` int(11) NOT NULL,
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
  `hp` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_fights` (`id_fights`),
  KEY `id_fighter` (`id_fighter`),
  CONSTRAINT `fights_log_ibfk_1` FOREIGN KEY (`id_fights`) REFERENCES `fights` (`id`),
  CONSTRAINT `fights_log_ibfk_2` FOREIGN KEY (`id_fighter`) REFERENCES `monsters` (`id`)
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
