-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 27 nov 2017 om 14:58
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
-- Tabelstructuur voor tabel `dish`
--

CREATE TABLE `dish` (
  `Id` int(11) NOT NULL,
  `Category` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `Price` decimal(5,2) NOT NULL,
  `Position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `dish`
--

INSERT INTO `dish` (`Id`, `Category`, `Name`, `Description`, `Price`, `Position`) VALUES
(1, 2, 'Pannekoek', 'Weet ik veel man', '6.00', 0),
(2, 2, 'Leven', 'Zuigt', '999.00', 1),
(3, 4, 'Maar', 'Waarom', '10.00', 1),
(4, 4, 'Kroepoek', 'Lekker hoor', '1.00', 2),
(5, 7, 'Het ', 'Leven kan niet zo door', '0.00', 1),
(6, 8, 'Zo ', 'Zuigend', '0.00', 2),
(7, 7, 'Life', 'Is Shit', '0.00', 3),
(8, 8, 'Zooo', 'Leuk...', '0.00', 4),
(9, 9, 'Leven', 'Leven', '999.00', 1),
(10, 9, 'But', 'Why?', '999.99', 2),
(11, 11, 'Vanille ijs', 'wooooooooo', '4.00', 1),
(12, 11, 'YESSS', 'WAAAROOOMMMM', '7.00', 2),
(13, 12, 'Limonade', 'HAHA', '2.00', 3),
(14, 12, 'Drinken', 'Alcohol', '19.00', 4),
(15, 7, 'I', 'Don\'t Understand It', '0.00', 5);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `dish`
--
ALTER TABLE `dish`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Category_idx` (`Category`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `dish`
--
ALTER TABLE `dish`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `dish`
--
ALTER TABLE `dish`
  ADD CONSTRAINT `DishCategory` FOREIGN KEY (`Category`) REFERENCES `dishcategory` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
