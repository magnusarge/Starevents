-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: d110670.mysql.zonevs.eu
-- Loomise aeg: Jaan 25, 2023 kell 08:49 EL
-- Serveri versioon: 10.4.26-MariaDB-log
-- PHP versioon: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Andmebaas: `d110670_starevent`
--

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `attendee`
--

CREATE TABLE `attendee` (
  `registration_id` int(11) NOT NULL,
  `registration_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `town` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Andmete tõmmistamine tabelile `attendee`
--

INSERT INTO `attendee` (`registration_id`, `registration_time`, `name`, `email`, `town`, `timestamp`, `comment`) VALUES
(1, '2023-01-24 10:46:05', 'John', 'john@ee.ee', 'Tartu', '2023-01-27 23:00:00', ''),
(17, '2023-01-24 14:00:57', 'Tiit', 'tiit@email.com', 'Tartu', '2023-01-28 05:00:00', ''),
(18, '2023-01-24 20:14:51', 'Peeter PervÃµi', 'peeter1@yandex.ru', 'Tallinn', '2023-01-28 02:00:00', ''),
(19, '2023-01-24 20:15:26', 'Viktor', 'viki@hot.ee', 'Tartu', '2023-01-28 05:00:00', 'Tahaks ka tulla, aga ei j&otilde;ua vist.'),
(20, '2023-01-24 20:16:09', 'CÃ¤thy Vaaderpass Smith', 'catsmith@mail.ee', 'Narva', '2023-01-28 02:00:00', 'V&auml;ga huvitav.'),
(21, '2023-01-24 20:16:52', 'Craig Liszt', 'craig.from.usa@yahoo.com', 'PÃ¤rnu', '2023-01-28 05:00:00', 'I&#039;m in!'),
(22, '2023-01-24 20:17:33', 'Sven StrÃ¶m', 'svenstrom@gmail.com', 'PÃ¤rnu', '2023-01-28 05:00:00', ''),
(23, '2023-01-24 20:18:15', 'Mihkel Maasikas', 'mmaasikas@gmail.com', 'JÃµhvi', '2023-01-28 02:00:00', 'Tulen ka vaatama'),
(24, '2023-01-24 20:19:06', 'Michael Knight', 'michael.knight.official@portland-sites.com', 'JÃµgeva', '2023-01-28 05:00:00', '&#039;ll come over too!'),
(25, '2023-01-24 20:19:35', 'TÃµnis mÃ¤gi', 'tonis@tonis.ee', 'PÃµlva', '2023-01-28 20:00:00', ''),
(26, '2023-01-24 20:20:13', 'Mari Mustikas', 'mustu@gmail.com', 'Valga', '2023-01-28 20:00:00', 'Pane mind ka kirja ;)'),
(27, '2023-01-24 20:20:39', 'Juhan', 'jussolen@mail.ee', 'Tallinn', '2023-01-27 23:00:00', ''),
(28, '2023-01-24 20:21:25', 'Luigi Montanelli', 'luigi.284@yahoo.com', 'Tartu', '2023-01-27 23:00:00', 'Arrivederci!');

--
-- Indeksid tõmmistatud tabelitele
--

--
-- Indeksid tabelile `attendee`
--
ALTER TABLE `attendee`
  ADD PRIMARY KEY (`registration_id`),
  ADD UNIQUE KEY `registration_id` (`registration_id`);

--
-- AUTO_INCREMENT tõmmistatud tabelitele
--

--
-- AUTO_INCREMENT tabelile `attendee`
--
ALTER TABLE `attendee`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
