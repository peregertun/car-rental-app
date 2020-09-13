-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Värd: localhost:3306
-- Tid vid skapande: 20 jan 2020 kl 19:29
-- Serverversion: 5.7.26
-- PHP-version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databas: `CarRental`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `Cars`
--

CREATE TABLE `Cars` (
  `carNumber` int(10) NOT NULL,
  `carLicenseNumber` varchar(15) DEFAULT NULL,
  `carBrand` varchar(15) DEFAULT NULL,
  `carYear` varchar(15) DEFAULT NULL,
  `carColor` varchar(15) DEFAULT NULL,
  `carPrice` varchar(10) DEFAULT NULL,
  `isVacant` varchar(10) DEFAULT '1',
  `rentedBy` varchar(50) DEFAULT NULL,
  `pickUpTime` varchar(25) DEFAULT NULL,
  `returnTime` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `Cars`
--

INSERT INTO `Cars` (`carNumber`, `carLicenseNumber`, `carBrand`, `carYear`, `carColor`, `carPrice`, `isVacant`, `rentedBy`, `pickUpTime`, `returnTime`) VALUES
(32, '1234', 'Lamborghini', '1982', 'Black', '1000', '1', '156', '2020-01-19 14:58:12', '2020-01-19 14:58:31'),
(35, 'His', 'Ferrari', '1234', 'Black', '100', '1', '159', '2020-01-19 15:11:57', '2020-01-19 15:16:16');

-- --------------------------------------------------------

--
-- Tabellstruktur `Colors`
--

CREATE TABLE `Colors` (
  `Colors` varchar(50) NOT NULL,
  `Brands` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `Colors`
--

INSERT INTO `Colors` (`Colors`, `Brands`) VALUES
('Black', 'Lamborghini'),
('Red', 'Ferrari'),
('Champagne', 'Tesla'),
('White', 'Audi'),
('Gray', 'Volvo'),
('Brown', 'Kia');

-- --------------------------------------------------------

--
-- Tabellstruktur `Customers`
--

CREATE TABLE `Customers` (
  `customerNumber` int(11) NOT NULL,
  `customerName` varchar(256) DEFAULT NULL,
  `customerSocialSecurityNumber` int(10) DEFAULT NULL,
  `customerAddress` varchar(200) DEFAULT NULL,
  `customerPostCode` varchar(100) DEFAULT NULL,
  `customerPhoneNumber` varchar(20) DEFAULT NULL,
  `isRenting` int(5) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `Customers`
--

INSERT INTO `Customers` (`customerNumber`, `customerName`, `customerSocialSecurityNumber`, `customerAddress`, `customerPostCode`, `customerPhoneNumber`, `isRenting`) VALUES
(159, 'Mary Jane', 12345, 'Gata 1', '123 45', '0701234567', 0),
(164, 'Namn Namnsson', 12345, 'Gata 1', '123 45', '0701234567', 0);

-- --------------------------------------------------------

--
-- Tabellstruktur `History`
--

CREATE TABLE `History` (
  `bookingNumber` int(11) NOT NULL,
  `carNumber` varchar(20) NOT NULL,
  `rentedByCustomer` varchar(100) NOT NULL,
  `pickUpTime` varchar(30) NOT NULL,
  `returnTime` varchar(30) DEFAULT NULL,
  `daysRented` varchar(20) DEFAULT NULL,
  `carPrice` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `History`
--

INSERT INTO `History` (`bookingNumber`, `carNumber`, `rentedByCustomer`, `pickUpTime`, `returnTime`, `daysRented`, `carPrice`) VALUES
(1, '35', '159', '2020-01-19 15:11:57', '2020-01-19 15:16:16', '1', '100');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `Cars`
--
ALTER TABLE `Cars`
  ADD PRIMARY KEY (`carNumber`),
  ADD UNIQUE KEY `pickUpTime` (`pickUpTime`);

--
-- Index för tabell `Customers`
--
ALTER TABLE `Customers`
  ADD PRIMARY KEY (`customerNumber`) USING BTREE;

--
-- Index för tabell `History`
--
ALTER TABLE `History`
  ADD PRIMARY KEY (`bookingNumber`),
  ADD KEY `customerNumber` (`pickUpTime`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `Cars`
--
ALTER TABLE `Cars`
  MODIFY `carNumber` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT för tabell `Customers`
--
ALTER TABLE `Customers`
  MODIFY `customerNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT för tabell `History`
--
ALTER TABLE `History`
  MODIFY `bookingNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
