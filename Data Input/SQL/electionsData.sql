-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 27, 2015 at 07:37 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
-- ----------------------------
--  Create Database
-- ----------------------------
CREATE DATABASE IF NOT EXISTS ptonElections;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ptonElections`
--

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE IF NOT EXISTS `districts` (
`id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`) VALUES
(1, '1'),
(2, '2'),
(3, '3'),
(4, '4'),
(5, '5'),
(6, '6'),
(7, '7'),
(8, '8'),
(9, '9'),
(10, '10'),
(11, '11'),
(12, '12'),
(13, '13'),
(14, '14'),
(15, '15'),
(16, '16'),
(17, '17'),
(18, '18'),
(19, '19'),
(20, '20'),
(21, '21'),
(22, '22'),
(23, 'Mail-In'),
(24, 'Provisional');

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

CREATE TABLE IF NOT EXISTS `elections` (
`id` bigint(20) NOT NULL,
  `election_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `election_date`, `location`, `name`) VALUES
(1, '2015-11-03 00:00:00', 'Princeton, NJ', 'Primary');

-- --------------------------------------------------------

--
-- Table structure for table `election_districts`
--

CREATE TABLE IF NOT EXISTS `election_districts` (
`id` bigint(20) NOT NULL,
  `election_id` bigint(20) NOT NULL,
  `district_id` bigint(20) NOT NULL,
  `machine_count` tinyint(4) NOT NULL,
  `reg_voters` bigint(20) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `election_districts`
--

INSERT INTO `election_districts` (`id`, `election_id`, `district_id`, `machine_count`, `reg_voters`) VALUES
(1, 1, 1, 2, 500),
(2, 1, 2, 2, 500),
(3, 1, 3, 2, 500),
(4, 1, 4, 2, 500),
(5, 1, 5, 2, 500),
(6, 1, 6, 2, 500),
(7, 1, 7, 2, 500),
(8, 1, 8, 2, 500),
(9, 1, 9, 2, 500),
(10, 1, 10, 2, 400),
(11, 1, 11, 2, 500),
(12, 1, 12, 2, 400),
(13, 1, 13, 2, 400),
(14, 1, 14, 2, 400),
(15, 1, 15, 2, 400),
(16, 1, 16, 2, 400),
(17, 1, 17, 2, 400),
(18, 1, 18, 2, 400),
(19, 1, 19, 2, 333),
(20, 1, 20, 2, 400),
(21, 1, 21, 2, 399),
(22, 1, 22, 2, 888),
(45, 1, 23, 1, 0),
(46, 1, 24, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `election_district_maps`
--

CREATE TABLE IF NOT EXISTS `election_district_maps` (
`id` bigint(20) NOT NULL,
  `election_district_id` bigint(20) NOT NULL,
  `latitude` decimal(20,8) NOT NULL,
  `longitude` decimal(20,8) NOT NULL,
  `plot_order` bigint(20) NOT NULL,
  `plot_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
`id` bigint(20) NOT NULL,
  `election_id` bigint(20) NOT NULL,
  `question` varchar(255) NOT NULL,
  `question_order` tinyint(4) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `election_id`, `question`, `question_order`) VALUES
(1, 1, 'General Assembly', 1),
(2, 1, 'County Executive', 2),
(3, 1, 'County Clerk', 3),
(4, 1, 'Freeholders', 4),
(5, 1, 'Council', 5),
(6, 1, 'Board of Education', 6);

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE IF NOT EXISTS `responses` (
`id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL,
  `response` varchar(255) NOT NULL,
  `response_order` tinyint(4) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=130 ;

--
-- Dumping data for table `responses`
--

INSERT INTO `responses` (`id`, `question_id`, `response`, `response_order`) VALUES
(101, 1, 'Andrew Zwicker', 1),
(102, 1, 'Jack M. Ciattarelli', 2),
(103, 2, 'Brian M. Hughes', 3),
(104, 2, 'Lisa Richford', 4),
(105, 1, 'Maureen Vella', 5),
(106, 1, 'Donna M. Simon', 6),
(109, 3, 'Paula Sollami Covello', 9),
(110, 3, 'Susan Bagley', 10),
(111, 4, 'Ann M. Cannon', 11),
(112, 4, 'Anthony "Tony" Davis', 12),
(113, 5, 'Heather Howard', 13),
(114, 5, 'Lynn Lu Irving', 14),
(115, 6, 'Elizabeth "Betsy" A. Kalber Baglio', 15),
(116, 6, 'Dafna Kendal', 16),
(117, 6, 'Patrick Sullivan', 17),
(118, 6, 'Robert Dodge', 18),
(126, 4, 'Pasquale "Pat" Colavita, Jr.', 3),
(127, 4, 'Ira Marks', 4),
(128, 4, 'Samuel T. Frisby, Sr.', 5),
(129, 4, 'Jason Lee DeFrancesco', 6);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE IF NOT EXISTS `results` (
`id` bigint(20) NOT NULL,
  `election_district_id` bigint(20) NOT NULL,
  `response_id` bigint(20) NOT NULL,
  `machine_number` tinyint(4) NOT NULL,
  `tally` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `elections`
--
ALTER TABLE `elections`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `election_districts`
--
ALTER TABLE `election_districts`
 ADD PRIMARY KEY (`id`), ADD KEY `election_districts_elections_fk` (`election_id`), ADD KEY `election_districts_districts_fk` (`district_id`);

--
-- Indexes for table `election_district_maps`
--
ALTER TABLE `election_district_maps`
 ADD PRIMARY KEY (`id`), ADD KEY `election_district_maps_election_districts_fk` (`election_district_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
 ADD PRIMARY KEY (`id`), ADD KEY `questions_elections_fk` (`election_id`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
 ADD PRIMARY KEY (`id`), ADD KEY `responses_questions_fk` (`question_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
 ADD PRIMARY KEY (`id`), ADD KEY `results_election_districts_fk` (`election_district_id`), ADD KEY `results_responses_fk` (`response_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `elections`
--
ALTER TABLE `elections`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `election_districts`
--
ALTER TABLE `election_districts`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `election_district_maps`
--
ALTER TABLE `election_district_maps`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=130;
--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `election_districts`
--
ALTER TABLE `election_districts`
ADD CONSTRAINT `election_districts_districts_fk` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `election_districts_elections_fk` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `election_district_maps`
--
ALTER TABLE `election_district_maps`
ADD CONSTRAINT `election_district_maps_election_districts_fk` FOREIGN KEY (`election_district_id`) REFERENCES `election_districts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
ADD CONSTRAINT `questions_elections_fk` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `responses`
--
ALTER TABLE `responses`
ADD CONSTRAINT `responses_questions_fk` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
ADD CONSTRAINT `results_election_districts_fk` FOREIGN KEY (`election_district_id`) REFERENCES `election_districts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `results_responses_fk` FOREIGN KEY (`response_id`) REFERENCES `responses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
