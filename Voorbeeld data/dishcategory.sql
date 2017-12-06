-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 27 nov 2017 om 14:59
-- Serverversie: 10.1.26-MariaDB
-- PHP-versie: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kratonrosbeijer`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dishcategory`
--

CREATE TABLE `dishcategory` (
  `Id` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `TitleDescription` varchar(45) DEFAULT NULL,
  `Description` varchar(200) NOT NULL,
  `ParentCategoryId` int(11) DEFAULT NULL,
  `Position` int(11) NOT NULL,
  `Price` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `dishcategory`
--

INSERT INTO `dishcategory` (`Id`, `Name`, `TitleDescription`, `Description`, `ParentCategoryId`, `Position`, `Price`) VALUES
(1, 'Voorgerecht', NULL, '', NULL, 0, NULL),
(2, 'Warm', NULL, '', 1, 0, NULL),
(4, 'Koud', NULL, '', 1, 1, NULL),
(5, 'Hoofdgerecht', NULL, '', NULL, 1, NULL),
(7, 'Rijsttafel Kraton', NULL, '', 5, 1, '30.00'),
(8, 'Rijsttafel Pasar Kembang', NULL, '', 5, 1, '25.00'),
(9, 'A la carte', '', 'Hoofdgerecht minimaal 2 gerechten', 5, 2, NULL),
(10, 'Nagerecht', NULL, '', NULL, 2, NULL),
(11, 'Ijs', NULL, '', 10, 1, NULL),
(12, 'Drankjes', NULL, '', 10, 2, NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `dishcategory`
--
ALTER TABLE `dishcategory`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `SubCategoryId_idx` (`ParentCategoryId`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `dishcategory`
--
ALTER TABLE `dishcategory`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `dishcategory`
--
ALTER TABLE `dishcategory`
  ADD CONSTRAINT `SubCategoryId` FOREIGN KEY (`ParentCategoryId`) REFERENCES `dishcategory` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
