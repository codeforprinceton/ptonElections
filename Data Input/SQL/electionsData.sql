-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 07, 2015 at 04:04 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

-- Database: `ptonElections`
--

-- --------------------------------------------------------

INSERT INTO `responses` (`response_order`, `response`, `question_id`) VALUES
(1, 'Corey Booker', 1),
(2, 'Jeff Bell', 1),
(3, 'Bonnie Watson Coleman', 2),
(4, 'Alieta Eck', 2),
(5, 'Eugene Martin Lavergne', 1),
(6, 'Hank Schroeder', 1),
(7, 'Allen J. Cannon', 2),
(8, 'Jack Freudenheim', 2),
(9, 'John A "Jack" Kemler', 3),
(10, 'David C. Jones', 3),
(11, 'Lucylle RS Walter', 4),
(12, 'John A. Cimino', 4),
(13, 'Bernard "Bernie" P. Miller', 5),
(14, 'Jo Butler', 5),
(15, 'Afsheen Shamsi', 6),
(16, 'Connie Witter', 6),
(17, 'Justin Doran', 6),
(18, 'Fern M. Spruill', 6),
(19, 'YES', 7),
(20, 'NO', 7);

-- --------------------------------------------------------

INSERT INTO `questions` (id, `question_order`, `question`, election_id) VALUES
(1,1, 'U.S. Senate',1),
(2,2, 'Representative 12th dist',1),
(3,3, 'Sheriff',1),
(4,4, 'Freeholders',1),
(5,5, 'Princeton Council',1),
(6,6, 'Princeton BOE',1),
(7,7, 'NJ State Question 1',1);

UPDATE `questions` set `question` = 'U.S. Senate' where id=1;
UPDATE `questions` set `question` = 'Representative 12th dist' where id=2;
UPDATE `questions` set `question` = 'Sheriff' where id=3;
UPDATE `questions` set `question` = 'Freeholders' where id=4;
UPDATE `questions` set `question` = 'Princeton Council' where id=5;
UPDATE `questions` set `question` = 'Princeton BOE' where id=6;
UPDATE `questions` set `question` = 'NJ State Question 1' where id=7;
