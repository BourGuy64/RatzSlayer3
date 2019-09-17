-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 17, 2019 at 07:11 AM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `RatzSlayer3`
--

-- --------------------------------------------------------

--
-- Table structure for table `character`
--

CREATE TABLE `character` (
  `id` int(11) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `hp` int(11) NOT NULL,
  `attack` int(11) NOT NULL,
  `def` int(11) NOT NULL,
  `agility` int(11) NOT NULL,
  `picture` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fight`
--

CREATE TABLE `fight` (
  `id` int(11) NOT NULL,
  `id_character` int(11) NOT NULL,
  `id_monster` int(11) NOT NULL,
  `winner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `figth_log`
--

CREATE TABLE `figth_log` (
  `id` int(11) NOT NULL,
  `id_fight` int(11) NOT NULL,
  `id_fighter` int(11) NOT NULL,
  `damage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `monster`
--

CREATE TABLE `monster` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `hp` int(11) NOT NULL,
  `attack` int(11) NOT NULL,
  `def` int(11) NOT NULL,
  `agility` int(11) NOT NULL,
  `picture` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `character`
--
ALTER TABLE `character`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fight`
--
ALTER TABLE `fight`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `figth_log`
--
ALTER TABLE `figth_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monster`
--
ALTER TABLE `monster`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `character`
--
ALTER TABLE `character`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fight`
--
ALTER TABLE `fight`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `figth_log`
--
ALTER TABLE `figth_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monster`
--
ALTER TABLE `monster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
