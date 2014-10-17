-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 14 okt 2014 om 10:47
-- Serverversie: 5.5.38-0ubuntu0.14.04.1
-- PHP-versie: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `ipe`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `authRole`
--

CREATE TABLE IF NOT EXISTS `authRole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `autoAdd` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userId` (`userId`),
  UNIQUE KEY `name` (`name`),
  KEY `autoAdd` (`autoAdd`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

--
-- Gegevens worden uitgevoerd voor tabel `authRole`
--

INSERT INTO `authRole` (`id`, `deleted`, `autoAdd`, `name`, `userId`) VALUES
(1, 0, 0, 'Admins', 1),
(2, 0, 0, 'Everyone', NULL),
(23, 1, 0, 'wesley', 24),
(24, 0, 1, 'Intermesh BV', NULL),
(26, 0, 0, 'test', 29),
(27, 0, 0, 'jan', 30),
(28, 1, 0, 'test1', NULL),
(29, 0, 0, 'asdsadsad', NULL),
(30, 0, 0, 'jodsfsd', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `authToken`
--

CREATE TABLE IF NOT EXISTS `authToken` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `series` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expiresAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`,`series`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Gegevens worden uitgevoerd voor tabel `authToken`
--

INSERT INTO `authToken` (`id`, `userId`, `series`, `token`, `expiresAt`) VALUES
(2, 1, '2EusO)6afgKFVZL''j"ckt', '#al4YqNwBZvUrFe(2KRP', '2014-10-20 15:02:07'),
(3, 1, '3kObjUV1g2%Y69nwLSMCI', 'pDxJ8Nm!)e1QWhCK6Edf', '2014-10-20 15:06:35'),
(4, 1, '4XEIdlKwBZ(vahybN%ckV', 'rMXk8dUGj9#%e(pD)4u5', '2014-10-21 09:32:47');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `authUser`
--

CREATE TABLE IF NOT EXISTS `authUser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `digest` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `username_2` (`username`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

--
-- Gegevens worden uitgevoerd voor tabel `authUser`
--

INSERT INTO `authUser` (`id`, `deleted`, `enabled`, `username`, `password`, `digest`, `createdAt`, `modifiedAt`) VALUES
(1, 0, 1, 'admin', '$1$DbSzAYcF$oc9bUIm.SBRjCD24ZcKg//', '508fd3bc6f1ecfedaa475586ce0b4f2f', '2014-07-21 14:01:17', '2014-08-05 15:16:05'),
(24, 1, 1, 'wesley', '$1$.Y7rTm9b$zZ913a7rb9XdW4I7.zkgF/', '31d7db10fe2ec0082ee9dcc0919e4ab5', '2014-08-12 11:18:26', '2014-09-02 13:50:59'),
(29, 0, 1, 'test', '$1$lFaityIe$3Z2iVYv3idx8vFwcavn.X.', 'e8a1f6d56a2af8f8a8253ef9d7ef4234', '2014-09-02 11:55:46', '2014-09-29 14:13:16'),
(30, 0, 1, 'jan', '$1$xdAqag.k$ZA2fc24Y1fFJhSIxEK.nz0', 'b850a3d0b07b72070144c04fd4e8322a', '2014-09-02 13:06:44', '2014-09-02 13:06:44');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `authUserRole`
--

CREATE TABLE IF NOT EXISTS `authUserRole` (
  `userId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  PRIMARY KEY (`userId`,`roleId`),
  KEY `roleId` (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden uitgevoerd voor tabel `authUserRole`
--

INSERT INTO `authUserRole` (`userId`, `roleId`) VALUES
(1, 1),
(1, 2),
(24, 2),
(29, 2),
(30, 2),
(1, 23),
(24, 23),
(29, 23),
(1, 24),
(24, 24),
(30, 24),
(29, 26),
(30, 27),
(29, 28),
(30, 28),
(30, 30);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContact`
--

CREATE TABLE IF NOT EXISTS `contactsContact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `userId` int(11) DEFAULT NULL,
  `ownerUserId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL,
  `prefixes` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `firstName` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `middleName` varchar(55) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `lastName` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `suffixes` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gender` enum('M','F') COLLATE utf8_unicode_ci DEFAULT NULL,
  `photoFilePath` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `notes` text COLLATE utf8_unicode_ci,
  `isCompany` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `IBAN` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `registrationNumber` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `companyContactId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ownerUserId` (`ownerUserId`),
  KEY `deleted` (`deleted`),
  KEY `userId` (`userId`),
  KEY `companyContactId` (`companyContactId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=47 ;

--
-- Gegevens worden uitgevoerd voor tabel `contactsContact`
--

INSERT INTO `contactsContact` (`id`, `deleted`, `userId`, `ownerUserId`, `createdAt`, `modifiedAt`, `prefixes`, `firstName`, `middleName`, `lastName`, `suffixes`, `gender`, `photoFilePath`, `notes`, `isCompany`, `name`, `IBAN`, `registrationNumber`, `companyContactId`) VALUES
(4, 0, 1, 1, '2014-07-28 09:35:30', '2014-09-22 09:33:48', '', 'Merijn', '', 'Schering', '', 'M', 'facebook.jpg', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est ja1\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.fsdfdsfds', 0, 'Merijn Schering', '', '', 41),
(5, 0, NULL, 24, '2014-08-12 11:19:15', '2014-09-22 09:44:50', '', 'Wesley', '', 'Smits', '', 'M', 'Pasfoto 2014.jpg', '', 0, 'Wesley Smits', '', '', 41),
(6, 0, NULL, 1, '2014-08-14 12:07:03', '2014-09-22 07:33:17', '', 'Anke', '', 'Rietdijk', '', 'F', '1everdieping.png', 'jo joajdjcnfjdjfjfjbxhjdjhxh', 0, 'Anke Rietdijk', '', '', NULL),
(7, 0, NULL, 24, '2014-08-14 14:51:19', '2014-09-22 09:44:13', '', 'Linda', '', 'huijs', '', 'F', 'IMG-20140302-WA0005.jpg', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 0, 'Linda huijs', '', '', 41),
(13, 0, 29, 1, '2014-09-02 11:55:46', '2014-09-22 08:25:27', '', 'Test', '', 'User', '', NULL, 'Pasfoto Merijn.JPG', 'Jaja', 0, 'Test User', '', '', 42),
(14, 1, 30, 1, '2014-09-02 13:06:44', '2014-09-04 15:06:11', '', 'Jan', '', 'Jansen', '', NULL, '', NULL, 0, NULL, '', '', NULL),
(38, 1, NULL, 1, '2014-09-04 12:04:03', '2014-09-04 15:06:09', '', 'dsfds', '', '', '', NULL, '', NULL, 0, NULL, '', '', NULL),
(39, 1, NULL, 1, '2014-09-04 13:51:46', '2014-09-04 15:06:17', '', 'test', '', '', '', NULL, '', NULL, 0, NULL, '', '', NULL),
(40, 1, NULL, 1, '2014-09-04 13:55:40', '2014-09-04 15:06:14', '', 'Jan', '', 'Jansen', '', NULL, '', NULL, 0, NULL, '', '', NULL),
(41, 0, NULL, 1, '2014-09-22 07:09:25', '2014-09-25 14:16:37', '', '', '', '', '', NULL, 'Group-Office Icon 512.png', 'fgdfgdfv\ndsvdskvnksdv', 1, 'Intermesh BV', '', '', NULL),
(42, 1, NULL, 1, '2014-09-22 08:25:27', '2014-09-23 14:58:38', '', '', '', '', '', NULL, '', NULL, 1, 'Test BV', '', '', NULL),
(43, 0, NULL, 1, '2014-09-22 08:58:48', '2014-10-13 12:28:52', '', '', '', '', '', NULL, '', NULL, 0, 'Jopie BV', '', '', NULL),
(44, 0, NULL, 1, '2014-09-25 13:41:58', '2014-09-25 14:32:11', '', 'Jan', 'de', 'Vries', '', NULL, 'Pasfoto 2014.jpg', NULL, 0, 'Jan de Vries', '', '', NULL),
(45, 0, NULL, 1, '2014-09-26 12:05:29', '2014-09-26 12:05:29', '', 'retg', '', '', '', NULL, '', NULL, 0, 'retg', '', '', NULL),
(46, 0, NULL, 1, '2014-10-14 08:42:07', '2014-10-14 08:42:07', '', 'Jantje', '', 'Beton', '', NULL, '', NULL, 0, 'Jantje Beton', '', '', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactAddress`
--

CREATE TABLE IF NOT EXISTS `contactsContactAddress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactId` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `zipCode` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `state` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country` char(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contactId` (`contactId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Gegevens worden uitgevoerd voor tabel `contactsContactAddress`
--

INSERT INTO `contactsContactAddress` (`id`, `contactId`, `type`, `street`, `zipCode`, `city`, `state`, `country`) VALUES
(1, 4, 'work', 'Zuid Willemsvaart 35', '5211SB', '''s-Hertogenbosch', 'Noord-Brabant', 'NL'),
(2, 4, 'home', 'Munteltuinen 50', '5212PM', '''s-Hertogenbosch', '', 'NL'),
(3, 4, 'other', 'Hesselsstraat 97', '5213XC', '''s-Hertogenbosch', 'Noord-Brabant', 'NL'),
(4, 41, 'work', 'Zuid Willemsvaart 35', '5211 SB', '''s-Hertogenbosch', 'Noord-Brabant', 'NL');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactCustomFields`
--

CREATE TABLE IF NOT EXISTS `contactsContactCustomFields` (
  `id` int(11) NOT NULL,
  `Speelsterkte dubbel` double DEFAULT '9',
  `Lid sinds` date DEFAULT NULL,
  `test` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Test',
  `test1` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Test',
  `test2` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'test',
  `zaterdagInvaller` tinyint(1) NOT NULL DEFAULT '0',
  `Test veld` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'test',
  `nummer` double DEFAULT NULL,
  `Tekst area` text COLLATE utf8_unicode_ci,
  `Ja of nee` varchar(9) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sdfsfs` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dasdsa` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Speelsterkte enkel` double DEFAULT '9',
  `Bondsnummer` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zondagInvaller` tinyint(1) NOT NULL DEFAULT '0',
  `test3` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'test',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden uitgevoerd voor tabel `contactsContactCustomFields`
--

INSERT INTO `contactsContactCustomFields` (`id`, `Speelsterkte dubbel`, `Lid sinds`, `test`, `test1`, `test2`, `zaterdagInvaller`, `Test veld`, `nummer`, `Tekst area`, `Ja of nee`, `sdfsfs`, `dasdsa`, `Speelsterkte enkel`, `Bondsnummer`, `zondagInvaller`, `test3`) VALUES
(4, 7.2, '2014-08-03', 'Test', 'Test', 'test', 1, 'test', 0.15, 'Tyugjhvhbjnj knmnjgjbjnkjkjuvnbmhihknk\n\n\nBjhjhkknk', 'Ja', '', '', 9, '123456789', 1, 'test'),
(5, 9, NULL, 'Test', 'Test', 'test', 1, 'test', NULL, NULL, '', '', '', 9, '123213', 0, 'test'),
(6, 9, '2014-08-20', 'Test', 'Test', 'test', 1, 'test', NULL, NULL, 'Ja', '', '', 9, '124337', 0, 'test'),
(7, 7, '2010-01-02', 'Test', 'Test', 'test', 1, 'test', NULL, 'Test\n\n\njkgj', 'Ja', '', '', 9, '123456789', 0, 'test'),
(8, 9, NULL, 'Test', 'Test', 'test', 1, 'test', NULL, NULL, '', '', '', 9, '', 0, 'test'),
(10, 9, NULL, 'Test', 'Test', 'test', 1, 'test', NULL, NULL, '', '', '', 9, '', 0, 'test'),
(13, 9, '2014-09-01', 'Test', 'Test', 'test', 1, 'test', NULL, NULL, '', '', '', 9, '23423423', 0, 'test'),
(38, 9, NULL, 'Test', 'Test', 'test', 1, 'test', NULL, NULL, '', '', '', 9, 'fghfd', 0, 'test'),
(39, 7, '2014-09-09', 'Test', 'Test', 'test', 1, 'test', NULL, NULL, '', '', '', 7, 'test', 0, 'test'),
(40, 9, NULL, 'Test', 'Test', 'test', 1, 'test', NULL, NULL, '', '', '', 9, '12321321321', 0, 'test'),
(41, 9, NULL, 'Test', 'Test', 'test', 1, 'test', NULL, NULL, '', '', '', 9, '234324', 0, 'test'),
(44, 9, NULL, 'Test', 'Test', 'test', 1, 'test', NULL, NULL, '', '', '', 9, '12345678', 0, 'test'),
(45, 9, NULL, 'Test', 'Test', 'test', 1, 'test', NULL, NULL, '', '', '', 9, 'w2243243', 0, 'test'),
(46, 9, NULL, 'Test', 'Test', 'test', 0, 'test', NULL, NULL, '', '', '', 9, '12321321', 0, 'test');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactDate`
--

CREATE TABLE IF NOT EXISTS `contactsContactDate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactId` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'birthday',
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contactId` (`contactId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Gegevens worden uitgevoerd voor tabel `contactsContactDate`
--

INSERT INTO `contactsContactDate` (`id`, `contactId`, `type`, `date`) VALUES
(1, 7, 'birthday', '1981-06-17'),
(2, 7, 'anniversary', '2007-01-01'),
(3, 4, 'birthday', '1980-09-11'),
(4, 6, 'birthday', '2014-08-07'),
(5, 44, 'anniversary', '2014-09-15'),
(6, 46, 'anniversary', '2014-10-06');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactEmailAddress`
--

CREATE TABLE IF NOT EXISTS `contactsContactEmailAddress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactId` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'work',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contactId` (`contactId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Gegevens worden uitgevoerd voor tabel `contactsContactEmailAddress`
--

INSERT INTO `contactsContactEmailAddress` (`id`, `contactId`, `type`, `email`) VALUES
(2, 4, 'work', 'mschering@intermesh.nl'),
(6, 6, 'work', 'yo@yo'),
(7, 6, 'work', 'test@man.nl'),
(8, 7, 'work', 'linda@intermesh.nl'),
(9, 4, 'other', 'merijn@intermesh.nl'),
(13, 13, 'work', 'test@intermesh.nl'),
(14, 14, 'work', 'jan@intermesh.nl'),
(15, 39, 'work', 'test@intermesh.nl'),
(16, 40, 'work', 'asdasd@sadsa.nl'),
(17, 41, 'work', 'info@intermesh.nl');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactPhone`
--

CREATE TABLE IF NOT EXISTS `contactsContactPhone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactId` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'work,voice',
  `number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contactId` (`contactId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Gegevens worden uitgevoerd voor tabel `contactsContactPhone`
--

INSERT INTO `contactsContactPhone` (`id`, `contactId`, `type`, `number`) VALUES
(2, 4, 'work,voice', '0619864268'),
(3, 6, 'work,voice', '435342532532'),
(4, 7, 'work,voice', '0641436697'),
(5, 4, 'work,voice', '0736445508');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactRole`
--

CREATE TABLE IF NOT EXISTS `contactsContactRole` (
  `contactId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `readAccess` tinyint(1) NOT NULL DEFAULT '1',
  `uploadAccess` tinyint(1) NOT NULL DEFAULT '0',
  `editAccess` tinyint(1) NOT NULL DEFAULT '0',
  `deleteAccess` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contactId`,`roleId`),
  KEY `roleId` (`roleId`),
  KEY `read` (`readAccess`,`editAccess`,`deleteAccess`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden uitgevoerd voor tabel `contactsContactRole`
--

INSERT INTO `contactsContactRole` (`contactId`, `roleId`, `readAccess`, `uploadAccess`, `editAccess`, `deleteAccess`) VALUES
(4, 1, 1, 1, 1, 1),
(4, 2, 1, 1, 0, 0),
(4, 23, 1, 1, 0, 0),
(5, 23, 1, 1, 1, 1),
(6, 1, 1, 1, 1, 1),
(7, 2, 1, 1, 1, 1),
(7, 23, 1, 1, 1, 1),
(13, 1, 1, 1, 1, 1),
(13, 24, 1, 1, 1, 1),
(13, 26, 1, 1, 1, 0),
(14, 1, 1, 1, 1, 1),
(14, 24, 1, 1, 1, 1),
(14, 27, 1, 1, 1, 0),
(38, 1, 1, 1, 1, 1),
(38, 24, 1, 1, 1, 1),
(39, 1, 1, 1, 1, 1),
(39, 24, 1, 1, 1, 1),
(40, 1, 1, 1, 1, 1),
(40, 24, 1, 1, 1, 1),
(41, 1, 1, 1, 1, 1),
(41, 24, 1, 1, 1, 1),
(42, 1, 1, 1, 1, 1),
(42, 24, 1, 1, 1, 1),
(43, 1, 1, 1, 1, 1),
(43, 24, 1, 1, 1, 1),
(44, 1, 1, 1, 1, 1),
(44, 2, 1, 1, 0, 0),
(44, 23, 1, 1, 0, 0),
(44, 24, 1, 1, 1, 1),
(45, 1, 1, 1, 1, 1),
(45, 24, 1, 1, 1, 1),
(46, 1, 1, 1, 1, 1),
(46, 24, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactTag`
--

CREATE TABLE IF NOT EXISTS `contactsContactTag` (
  `contactId` int(11) NOT NULL,
  `tagId` int(11) NOT NULL,
  PRIMARY KEY (`contactId`,`tagId`),
  KEY `tagId` (`tagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden uitgevoerd voor tabel `contactsContactTag`
--

INSERT INTO `contactsContactTag` (`contactId`, `tagId`) VALUES
(4, 1),
(4, 2),
(4, 3),
(7, 4),
(13, 4),
(6, 6);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `coreSession`
--

CREATE TABLE IF NOT EXISTS `coreSession` (
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden uitgevoerd voor tabel `coreSession`
--

INSERT INTO `coreSession` (`id`, `userId`, `createdAt`, `modifiedAt`, `data`) VALUES
('ogdsare7463oj0mbdgg8crfq52', 1, '2014-10-14 08:10:52', '2014-10-14 08:37:38', 'userId|i:1;');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `customFieldsField`
--

CREATE TABLE IF NOT EXISTS `customFieldsField` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldSetId` int(11) NOT NULL,
  `sortOrder` int(11) NOT NULL DEFAULT '0',
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `databaseName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `placeholder` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `defaultValue` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `_data` text COLLATE utf8_unicode_ci,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `filterable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `databaseName` (`databaseName`),
  KEY `fieldSetId` (`fieldSetId`),
  KEY `deleted` (`deleted`),
  KEY `sortOrder` (`sortOrder`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=40 ;

--
-- Gegevens worden uitgevoerd voor tabel `customFieldsField`
--

INSERT INTO `customFieldsField` (`id`, `fieldSetId`, `sortOrder`, `type`, `name`, `databaseName`, `placeholder`, `required`, `defaultValue`, `_data`, `deleted`, `filterable`) VALUES
(25, 10, 8, 'number', 'Speelsterkte dubbel', 'Speelsterkte dubbel', 'De placeholder...', 0, '9', '{"options":[{"value":"9"},{"value":"8"},{"value":"7"},{"value":"6"},{"value":"5"},{"value":"4"},{"value":"3"},{"value":"2"},{"value":"1"}],"maxLength":50}', 0, 1),
(26, 10, 7, 'date', 'Lid sinds', 'Lid sinds', 'De placeholder...', 0, '', '{"options":[]}', 0, 1),
(27, 10, 0, 'text', 'Test', 'test1', '', 0, 'Test', '{"maxLength":50}', 1, 0),
(28, 10, 0, 'text', 'Test', 'test2', '', 0, 'test', '{"maxLength":50}', 1, 0),
(29, 10, 10, 'checkbox', 'Beschikbaar als invaller op Zaterdag', 'zaterdagInvaller', '', 0, '0', '{"options":[]}', 0, 1),
(30, 11, 5, 'text', 'Test veld', 'Test veld', 'Plaatshouder', 0, 'test', '{"maxLength":50,"options":[]}', 0, 1),
(31, 11, 4, 'number', 'Een nummer', 'nummer', '', 0, '', NULL, 0, 0),
(32, 11, 6, 'textarea', 'Tekst area', 'Tekst area', '', 0, '', '{"height":100,"options":[]}', 0, 0),
(33, 11, 7, 'select', 'Ja of nee', 'Ja of nee', 'Kies er een', 0, '', '{"options":[{"value":"Ja"},{"value":"Nee"},{"value":"Weet niet"}]}', 0, 0),
(34, 10, 0, 'text', 'sdfdsf', 'sdfsfs', '', 0, '', '{"options":[],"maxLength":50}', 1, 0),
(35, 10, 0, 'text', 'asdsad', 'dasdsa', '', 0, '', '{"options":[],"maxLength":50}', 1, 0),
(36, 10, 9, 'number', 'Speelsterkte enkel', 'Speelsterkte enkel', '', 0, '9', '{"options":[],"maxLength":50}', 0, 1),
(37, 10, 6, 'text', 'Bondsnummer', 'Bondsnummer', 'Vul in aub...', 1, '', '{"options":[],"maxLength":50}', 0, 1),
(38, 10, 11, 'checkbox', 'Beschikbaar als invaller op Zondag', 'zondagInvaller', '', 0, '0', '{"options":[]}', 0, 1),
(39, 10, 0, 'text', 'test', 'test3', '', 0, 'test', '{"options":[],"maxLength":50}', 1, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `customFieldsFieldSet`
--

CREATE TABLE IF NOT EXISTS `customFieldsFieldSet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sortOrder` int(11) NOT NULL DEFAULT '0',
  `modelName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `model` (`modelName`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Gegevens worden uitgevoerd voor tabel `customFieldsFieldSet`
--

INSERT INTO `customFieldsFieldSet` (`id`, `sortOrder`, `modelName`, `name`, `deleted`) VALUES
(10, 2, 'Intermesh\\Modules\\Contacts\\Model\\ContactCustomFields', 'Tennis', 0),
(11, 2, 'Intermesh\\Modules\\Contacts\\Model\\ContactCustomFields', 'Intermesh BV', 1),
(12, 5, 'Intermesh\\Modules\\Contacts\\Model\\ContactCustomFields', 'Test', 1),
(13, 3, 'Intermesh\\Modules\\Contacts\\Model\\ContactCustomFields', 'tretre', 1),
(14, 0, 'Intermesh\\Modules\\Contacts\\Model\\ContactCustomFields', 'dsfasfsa', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dropboxAccount`
--

CREATE TABLE IF NOT EXISTS `dropboxAccount` (
  `ownerUserId` int(11) NOT NULL,
  `accessToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `requestToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deltaCursor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropboxUserId` int(11) NOT NULL,
  PRIMARY KEY (`ownerUserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden uitgevoerd voor tabel `dropboxAccount`
--

INSERT INTO `dropboxAccount` (`ownerUserId`, `accessToken`, `requestToken`, `deltaCursor`, `dropboxUserId`) VALUES
(1, 'PYIaYn_LRkYAAAAAAAANJmzC54sW_dBRFaYsTconpdEd0r1qvlT0MgV_1xkS_xcJ', NULL, 'AAE_7NP_xZvoZhAlzrU5Xd0S7VL8NgUrj4ERiGgTqM0uXbvUdXM6dyav1m1wCpgn8ZbMYrGHe8UAKWmfiKm1v7qaaRVkTnsPRyr08W_oL55K2Q', 8227424);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dropboxAccountFolder`
--

CREATE TABLE IF NOT EXISTS `dropboxAccountFolder` (
  `accountId` int(11) NOT NULL,
  `folderId` int(11) NOT NULL,
  PRIMARY KEY (`accountId`,`folderId`),
  KEY `folderId` (`folderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `filesFile`
--

CREATE TABLE IF NOT EXISTS `filesFile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `ownerUserId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL,
  `parentId` int(11) DEFAULT NULL,
  `isFolder` tinyint(1) NOT NULL DEFAULT '0',
  `readOnly` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` bigint(20) NOT NULL DEFAULT '0',
  `contentType` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `modelName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modelId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parentId` (`parentId`,`isFolder`,`name`),
  KEY `ownerUserId` (`ownerUserId`,`parentId`),
  KEY `folderId` (`parentId`),
  KEY `isFolder` (`isFolder`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Gegevens worden uitgevoerd voor tabel `filesFile`
--

INSERT INTO `filesFile` (`id`, `deleted`, `ownerUserId`, `createdAt`, `modifiedAt`, `parentId`, `isFolder`, `readOnly`, `name`, `size`, `contentType`, `modelName`, `modelId`) VALUES
(19, 0, 1, '2014-10-03 13:37:50', '2014-10-03 13:37:50', NULL, 1, 0, 'Contacts', 0, NULL, 'Intermesh\\Modules\\Contacts\\Model\\Contact', 4),
(20, 0, 1, '2014-10-03 13:37:50', '2014-10-03 13:37:50', 19, 1, 0, 'Merijn Schering', 0, NULL, 'Intermesh\\Modules\\Contacts\\Model\\Contact', 4),
(21, 0, 1, '2014-10-03 13:37:50', '2014-10-03 13:37:50', 20, 0, 0, 'Sales module import.txt', 569, 'text/plain; charset=utf-8', 'Intermesh\\Modules\\Contacts\\Model\\Contact', 4),
(23, 0, 1, '2014-10-03 13:53:13', '2014-10-03 15:53:08', 20, 0, 0, 'Test2.txt', 24, 'text/plain; charset=us-ascii', 'Intermesh\\Modules\\Contacts\\Model\\Contact', 4),
(24, 0, 1, '2014-10-03 14:15:57', '2014-10-03 14:15:57', 19, 1, 0, 'Test', 0, NULL, 'Intermesh\\Modules\\Contacts\\Model\\Contact', 4),
(25, 0, 1, '2014-10-03 14:17:28', '2014-10-03 14:17:28', 20, 1, 0, 'Test', 0, NULL, 'Intermesh\\Modules\\Contacts\\Model\\Contact', 4),
(26, 1, 1, '2014-10-03 14:31:43', '2014-10-03 14:32:24', 20, 1, 0, 'Bladiebla', 0, NULL, 'Intermesh\\Modules\\Contacts\\Model\\Contact', 4),
(27, 0, 1, '2014-10-03 14:32:24', '2014-10-03 14:32:24', 20, 1, 0, 'Bladiebla2', 0, NULL, 'Intermesh\\Modules\\Contacts\\Model\\Contact', 4),
(28, 0, 1, '2014-10-03 14:34:01', '2014-10-03 14:34:01', 20, 0, 0, 'Diversen 2014-09-29.pdf', 307401, 'application/pdf; charset=binary', 'Intermesh\\Modules\\Contacts\\Model\\Contact', 4);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `imapAccount`
--

CREATE TABLE IF NOT EXISTS `imapAccount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerUserId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `port` int(11) NOT NULL DEFAULT '993',
  `encrytion` enum('ssl','tls') COLLATE utf8_unicode_ci DEFAULT 'ssl',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `syncedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ownerUserId` (`ownerUserId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `imapAccount`
--

INSERT INTO `imapAccount` (`id`, `ownerUserId`, `createdAt`, `modifiedAt`, `host`, `port`, `encrytion`, `username`, `password`, `syncedAt`) VALUES
(2, 1, '2014-09-30 00:00:00', '2014-10-02 15:03:08', 'imap.group-office.com', 993, 'ssl', 'test@intermesh.nl', 'T3stusr!', '2014-10-02 12:59:00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `imapAttachment`
--

CREATE TABLE IF NOT EXISTS `imapAttachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `messageId` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contentType` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contentId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inline` tinyint(1) NOT NULL DEFAULT '0',
  `size` bigint(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `messageId` (`messageId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=85 ;

--
-- Gegevens worden uitgevoerd voor tabel `imapAttachment`
--

INSERT INTO `imapAttachment` (`id`, `messageId`, `filename`, `contentType`, `contentId`, `inline`, `size`) VALUES
(1, 78, 'intermesh_handtekening.gif', 'image/gif', '<1412143189.542b9855076c5@timedesk.spdns.de>', 1, 6850),
(2, 78, 'intermesh_handtekening (1).gif', 'image/gif', '<1412143189.542b985508588@timedesk.spdns.de>', 1, 6850),
(3, 78, 'intermesh_handtekening (1) (1).gif', 'image/gif', '<1412143189.542b985508d6a@timedesk.spdns.de>', 1, 6850),
(4, 79, 'I2013-003672.pdf', 'application/pdf', NULL, 0, 93565),
(5, 80, 'I2014-004354.pdf', 'application/pdf', NULL, 0, 93569),
(6, 82, 'intermesh_handtekening.gif', 'image/gif', '<713d9971a126e2ac9cdca78f7b198f94@intermesh.group-office.com>', 1, 6850),
(7, 82, 'I2014-004189.pdf', 'application/pdf', NULL, 0, 93518),
(8, 102, 'image001.png', 'image/png', '<image001.png@01CE6C15.829307F0>', 1, 7079),
(9, 102, 'image002.png', 'image/png', '<image002.png@01CE6C15.829307F0>', 1, 5237),
(10, 102, 'image003.png', 'image/png', '<image003.png@01CE6C15.829307F0>', 1, 5163),
(11, 102, 'image004.png', 'image/png', '<image004.png@01CE6C15.829307F0>', 1, 6312),
(12, 103, '0B1417D2-3376-4AC1-A159-633979044F44[42].png', 'image/png', '<86985AFF-5C73-47DD-B354-4AF1A340BD4F>', 1, 7079),
(13, 103, '52EDF28A-FC61-449A-B332-3A115126CF40[42].png', 'image/png', '<872CE2C8-CDB8-48E2-9E7C-3FA52B8D4DC2>', 1, 5237),
(14, 103, 'BE83C186-5E00-4A4C-9AC0-8045C5BD0F77[42].png', 'image/png', '<DDAFF7A9-E1E9-43EA-8ACE-88A75047622F>', 1, 5163),
(15, 103, '8EE51366-B6A3-41AD-AC4E-79277C098DDD[42].png', 'image/png', '<3CC16138-69B5-40C3-85E4-66CA094BFFFB>', 1, 6312),
(16, 103, 'intermesh_handtekening.gif', 'image/gif', '<b3bfdcee04615ecf9d7ac2c75ae4c3a5@intermesh.group-office.com>', 0, 6850),
(17, 103, 'image001.png', 'image/png', '<27331d605c8d8d2853fcb71017cb9caf@intermesh.group-office.com>', 0, 7079),
(18, 103, 'image002.png', 'image/png', '<0526bd378965ebad900831bea3f5cbb6@intermesh.group-office.com>', 0, 5237),
(19, 103, 'image003.png', 'image/png', '<8a3b7a7e4e94089e5eece2eac23b9901@intermesh.group-office.com>', 0, 5163),
(20, 103, 'image004.png', 'image/png', '<d4456ac85208203e68a54627e9c2a2f2@intermesh.group-office.com>', 0, 6312),
(21, 104, 'order_F0000_2014_0031_6603.pdf', 'application/pdf', NULL, 0, 5574),
(22, 85, 'ITP LOGO - 21-01-2013 (5).png', 'image/png', '<767aa08b3435a0b11ee9bd8eeb167992@itp.inner-circles.co.uk>', 1, 9419),
(23, 85, 'logosmall.fw (3).png', 'image/png', '<1ac2c03e1b14b35aedb5bcf32e979137@itp.inner-circles.co.uk>', 1, 87225),
(24, 85, 'linkedinmail.fw (3).png', 'image/png', '<d78bcef655cfa00009b3bf006f862695@itp.inner-circles.co.uk>', 1, 55557),
(25, 85, 'twittermail.fw (3).png', 'image/png', '<6e9222c6f61524abf70c49769a9c04d4@itp.inner-circles.co.uk>', 1, 51377),
(26, 85, 'g+mail.fw (2).png', 'image/png', '<c1c1b1cc0d036690f71370e012a7accb@itp.inner-circles.co.uk>', 1, 55393),
(27, 85, 'youtubemail.fw (4).png', 'image/png', '<0a95457b7a348a784dbe21a8c1fe3069@itp.inner-circles.co.uk>', 1, 58478),
(28, 85, 'linicon.fw (4).png', 'image/png', '<913f20a2f69b42e79c3da2d4ac24f4cf@itp.inner-circles.co.uk>', 1, 49329),
(29, 85, 'intermesh_handtekening.gif', 'image/gif', '<0e4380ea6213fddaf010a8332d2c2c82@itp.inner-circles.co.uk>', 1, 6850),
(30, 85, 'twitter-bird-light-bgs.png', 'image/png', '<3971e9389612c03413b65a1be3d16ff9@itp.inner-circles.co.uk>', 1, 565),
(31, 85, 'linkedinmail.fw (3) (1).png', 'image/png', '<ccc5da64ed52d3eaec89ce68eaa86e20@itp.inner-circles.co.uk>', 1, 55557),
(32, 85, 'twittermail.fw (3) (1).png', 'image/png', '<e886f79b148b905df99bce28841bb610@itp.inner-circles.co.uk>', 1, 51377),
(33, 85, 'g+mail.fw (2) (1).png', 'image/png', '<0f5a69dec598e1fa375b52884f5eac92@itp.inner-circles.co.uk>', 1, 55393),
(34, 85, 'youtubemail.fw (4) (1).png', 'image/png', '<ac00292ae5f06269eaf1a39b028cdefd@itp.inner-circles.co.uk>', 1, 58478),
(35, 85, 'linicon.fw (4) (1).png', 'image/png', '<4853069decff196bf1ff49b0938a9a1e@itp.inner-circles.co.uk>', 1, 49329),
(36, 86, 'I2014-004404.pdf', 'application/pdf', NULL, 0, 93506),
(37, 87, 'intermesh_handtekening.gif', 'image/gif', '<de5c29cea20a77ef425f9386ae47f987@intermesh.group-office.com>', 1, 6850),
(38, 87, 'sqltool.zip', 'application/zip', NULL, 0, 14796),
(39, 88, 'ITP LOGO - 21-01-2013 (5).png', 'image/png', '<289b2eeb01f46bc73ab590fe865bb5b9@itp.inner-circles.co.uk>', 1, 9419),
(40, 88, 'Mockups.rar', 'application/x-rar', NULL, 0, 11609683),
(41, 89, 'ITP LOGO - 21-01-2013 (5).png', 'image/png', '<485e53663d1386f4ba627294dfea3f84@itp.inner-circles.co.uk>', 1, 9419),
(42, 89, 'intermesh_handtekening.gif', 'image/gif', '<740053061793793d25071017ab19d011@itp.inner-circles.co.uk>', 1, 6850),
(43, 89, 'logosmall.fw (3).png', 'image/png', '<adbe2e0791c5eeeea7e418879cef1502@itp.inner-circles.co.uk>', 1, 87225),
(44, 89, 'linkedinmail.fw (3).png', 'image/png', '<874e733fd58eeeef630dc9445f75ce5b@itp.inner-circles.co.uk>', 1, 55557),
(45, 89, 'twittermail.fw (3).png', 'image/png', '<625787d83ca511ff4e4e33d1ca1ee549@itp.inner-circles.co.uk>', 1, 51377),
(46, 89, 'g+mail.fw (2).png', 'image/png', '<e93020b6b4414686f4ff5200b2c6b94e@itp.inner-circles.co.uk>', 1, 55393),
(47, 89, 'youtubemail.fw (4).png', 'image/png', '<369c31d28caacae564d7f8bf4e949eea@itp.inner-circles.co.uk>', 1, 58478),
(48, 89, 'linicon.fw (4).png', 'image/png', '<ac0ab3477bd809109c40b7058a280743@itp.inner-circles.co.uk>', 1, 49329),
(49, 89, 'linkedinmail.fw (3) (1).png', 'image/png', '<d65710b83b58a1733f8aa0879a8199bb@itp.inner-circles.co.uk>', 1, 55557),
(50, 89, 'twittermail.fw (3) (1).png', 'image/png', '<1a0e354112d550e60bf05243a991155b@itp.inner-circles.co.uk>', 1, 51377),
(51, 89, 'g+mail.fw (2) (1).png', 'image/png', '<f4ca34283508b91bb0dea3d4b40c0b83@itp.inner-circles.co.uk>', 1, 55393),
(52, 89, 'youtubemail.fw (4) (1).png', 'image/png', '<616813deb642d85aa756c785554a8c32@itp.inner-circles.co.uk>', 1, 58478),
(53, 89, 'linicon.fw (4) (1).png', 'image/png', '<6d8d7de2420f3f402ed6217ded623e9c@itp.inner-circles.co.uk>', 1, 49329),
(54, 90, 'O2014-004163.pdf', 'application/pdf', NULL, 0, 93536),
(55, 91, 'intermesh_handtekening.gif', 'image/gif', '<4e5b45b05619fbe12ddfde99ab5594b5@intermesh.group-office.com>', 1, 6850),
(56, 91, 'Voorbeeld Exact Online factuur uit Group-Office.pdf', 'application/pdf', NULL, 0, 87773),
(57, 92, 'intermesh_handtekening.gif', 'image/gif', '<23f501e6b4c1ce2c2ff88009f02b80aa@intermesh.group-office.com>', 1, 6850),
(58, 92, 'ITP LOGO - 21-01-2013 (5).png', 'image/png', '<62ba625e6b4a1b98952d66bb744ef2fb@intermesh.group-office.com>', 1, 9419),
(59, 92, 'ITP LOGO - 21-01-2013 (5) (1).png', 'image/png', '<388c082f9f45e88d9090d23afe83eb91@intermesh.group-office.com>', 1, 9419),
(60, 92, 'logosmall.fw (3).png', 'image/png', '<4cd618ccf62ea6463d78e0c7fc84a503@intermesh.group-office.com>', 1, 87225),
(61, 92, 'linkedinmail.fw (3).png', 'image/png', '<2f6053e0d8d2512d134a498381bcf955@intermesh.group-office.com>', 1, 55557),
(62, 92, 'twittermail.fw (3).png', 'image/png', '<c9aa9d175a5e9d1788246c3a2616ecb8@intermesh.group-office.com>', 1, 51377),
(63, 92, 'g+mail.fw (2).png', 'image/png', '<de237425b5967362b09d170f8f6545d3@intermesh.group-office.com>', 1, 55393),
(64, 92, 'youtubemail.fw (4).png', 'image/png', '<6293c61782e12920939fdd7cd82d8c6a@intermesh.group-office.com>', 1, 58478),
(65, 92, 'linkedinmail.fw (3) (1).png', 'image/png', '<f4cd9db8672268a234f85ebb62d8ca67@intermesh.group-office.com>', 1, 55557),
(66, 92, 'twittermail.fw (3) (1).png', 'image/png', '<5b22aeb5b0035e863eeabac9a4b4d1b5@intermesh.group-office.com>', 1, 51377),
(67, 92, 'g+mail.fw (2) (1).png', 'image/png', '<fd197ae8140bbe7fce45f9ae6187c4e6@intermesh.group-office.com>', 1, 55393),
(68, 92, 'youtubemail.fw (4) (1).png', 'image/png', '<e3ce133542851e6d56c8f06c137542f1@intermesh.group-office.com>', 1, 58478),
(69, 92, 'linicon.fw (4) (1).png', 'image/png', '<73d2691f2c262f9d6f6c4f35a407616a@intermesh.group-office.com>', 1, 49329),
(70, 93, 'ITP LOGO - 21-01-2013 (5).png', 'image/png', '<019083f35cf4f3f43dbc56104f3e241a@itp.inner-circles.co.uk>', 1, 9419),
(71, 93, 'sales.tar.bz2', 'application/x-bzip-compressed-tar', NULL, 0, 164623),
(72, 95, 'I2014-004405.pdf', 'application/pdf', NULL, 0, 93661),
(73, 97, 'invite.ics', 'text/calendar', NULL, 0, 586),
(74, 99, 'image005.gif', 'image/gif', '<image005.gif@01CFDDCE.B52424C0>', 1, 6850),
(75, 99, 'image006.jpg', 'image/jpeg', '<image006.jpg@01CFDDCE.B5275910>', 1, 2795),
(76, 99, 'image007.jpg', 'image/jpeg', '<image007.jpg@01CFDDCE.B5275910>', 1, 913),
(77, 99, 'image008.jpg', 'image/jpeg', '<image008.jpg@01CFDDCE.B5275910>', 1, 967),
(78, 99, 'image009.jpg', 'image/jpeg', '<image009.jpg@01CFDDCE.B5275910>', 1, 1005),
(79, 100, 'invite.ics', 'text/calendar', NULL, 0, 594),
(80, 106, 'Gewoon Anders najaar 2014.pdf', 'application/pdf', NULL, 0, 518495),
(81, 109, 'intermesh_handtekening.gif', 'image/gif', '<66163e07b6cb4d390ec2d39cc1b71a81@intermesh.group-office.com>', 1, 6850),
(82, 110, 'intermesh_handtekening.gif', 'image/gif', '<3f5f4335adc442292596fbe4306d60e4@intermesh.group-office.com>', 1, 6850),
(83, 111, 'intermesh_handtekening.gif', 'image/gif', '<ec7011b3304ffa96018a0551e171ee48@intermesh.group-office.com>', 1, 6850),
(84, 111, 'intermesh_handtekening (1).gif', 'image/gif', '<70dc5022b9313cdff2ed9f962ccf9961@intermesh.group-office.com>', 1, 6850);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `imapMessage`
--

CREATE TABLE IF NOT EXISTS `imapMessage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `threadId` int(11) DEFAULT NULL,
  `ownerUserId` int(11) NOT NULL,
  `messageId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `to` text COLLATE utf8_unicode_ci,
  `cc` text COLLATE utf8_unicode_ci,
  `body` text COLLATE utf8_unicode_ci,
  `contentType` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text/html',
  PRIMARY KEY (`id`),
  UNIQUE KEY `messageId` (`messageId`),
  KEY `owner` (`ownerUserId`),
  KEY `threadId` (`threadId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=112 ;

--
-- Gegevens worden uitgevoerd voor tabel `imapMessage`
--

INSERT INTO `imapMessage` (`id`, `threadId`, `ownerUserId`, `messageId`, `date`, `subject`, `from`, `to`, `cc`, `body`, `contentType`) VALUES
(106, 106, 1, '<880b64361a1e408639f9426cba3cb3c6@interlokaal.group-office.eu>', '2014-09-25 17:30:00', 'Training voor allochtone mantelzorgers', '"Cheung, Yuk Sie" <y.cheung@inter-lokaal.nl>', '"HIL, Admin" <admin@inter-lokaal.nl>, "Rooij, Jan de" <jander@inter-lokaal.nl>, "Florissen, Swim" <s.florissen@inter-lokaal.nl>, "Kampe, Ien ten" <i.tenkampe@inter-lokaal.nl>, "Randwijk, Brigitte" <b.randwijk@inter-lokaal.nl>, "Geelen, Martine" <m.geelen@inter-lokaal.nl>, "Schreurs, Judith" <j.schreurs@inter-lokaal.nl>, "Compiet, Rietje" <r.compiet@inter-lokaal.nl>, "Gerwen, Erni van" <e.vangerwen@inter-lokaal.nl>, "Eikelberg, Gerry" <g.eikelberg@inter-lokaal.nl>, "Bolek, Hatice" <h.bolek@inter-lokaal.nl>, "Cloosterman, Marion" <m.cloosterman@inter-lokaal.nl>, "Veeken, Nathalie van der" <n.vanderveeken@inter-lokaal.nl>, "Menting, Ria" <r.menting@inter-lokaal.nl>, "Mouthaan, Ties" <t.mouthaan@inter-lokaal.nl>, "Bongers, Germa" <g.bongers@inter-lokaal.nl>, "Verspeek, Jacqueline" <j.verspeek@inter-lokaal.nl>, "Vries, Tanja de" <t.devries@inter-lokaal.nl>, "Gosen, Jos" <j.gosen@inter-lokaal.nl>, "Freriks, Peter" <p.freriks@inter-lokaal.nl>, Büyükkaya, Ahmet <a.buyukkaya@inter-lokaal.nl>, "Haas, Corrie de" <c.dehaas@inter-lokaal.nl>, "Osman, Hamse" <h.osman@inter-lokaal.nl>, "Boumkass, Nordin" <n.boumkass@inter-lokaal.nl>, "Cheung, Yuk Sie" <y.cheung@inter-lokaal.nl>, "begeleiding, individuele" <individuelebegeleiding@inter-lokaal.nl>, "HIL, Support" <support@inter-lokaal.nl>, "Willems, Frans" <f.willems@inter-lokaal.nl>, "Kreffer, Ruud" <r.kreffer@inter-lokaal.nl>, "HIL, Beheerder" <beheerder@inter-lokaal.nl>, Brok, Donné <d.brok@inter-lokaal.nl>, "Martha, Mila" <a.martha@inter-lokaal.nl>, "Kahraman , Hava" <h.kahraman@inter-lokaal.nl>, "work, EE at" <ee-atwork@inter-lokaal.nl>, "Beheer, Intermesh" <test@intermesh.nl>, "Mahamed, Keen" <k.mahamed@inter-lokaal.nl>, "Engelaer, Bart" <b.engelaer@inter-lokaal.nl>, "Derksen, Anita" <a.derksen@inter-lokaal.nl>, "Idle, Ayan" <a.idle@inter-lokaal.nl>, "Muhumed, Abdulhakim Ali" <a.muhumed@inter-lokaal.nl>, "Hoogland, Bea" <b.hoogland@inter-lokaal.nl>, "Litjens, Burgi" <b.litjens@inter-lokaal.nl>, "Ouhdifa, Badia" <b.ouhdifa@inter-lokaal.nl>, "Postma, Bea" <b.postma@inter-lokaal.nl>, "Berenbroek, Corry" <c.berenbroek@inter-lokaal.nl>, "America, Derinda" <d.america@inter-lokaal.nl>, "El Khayati , Fatima" <f.elkhayati@inter-lokaal.nl>, "Martens, Frans" <f.martens@inter-lokaal.nl>, "Zouay, Faysal" <f.zouay@inter-lokaal.nl>, "Acuner-Kasar, Gunay" <g.acuner@inter-lokaal.nl>, "Hooghof, Geert" <g.hooghof@inter-lokaal.nl>, "Broek, Gertjan van den" <g.vandenbroek@inter-lokaal.nl>, "Bdewee, Heyam" <h.bdewee@inter-lokaal.nl>, "Westmaas, Henk" <h.westmaas@inter-lokaal.nl>, "Cakar, Jessie" <j.cakar@inter-lokaal.nl>, "El Hachioui, Jamal" <j.elhachioui@inter-lokaal.nl>, "Terpstra, Jettie" <j.terpstra@inter-lokaal.nl>, "Bashir, Mohamed" <m.bashir@inter-lokaal.nl>, "Ramadan, Mona" <m.ramadan@inter-lokaal.nl>, "Goktas, Nazli" <n.goktas@inter-lokaal.nl>, "Hoogma, Philip" <p.hoogma@inter-lokaal.nl>, "Rijthoven, Philip van" <p.vanrijthoven@inter-lokaal.nl>, "Jesic, Slobodan" <s.jesic@inter-lokaal.nl>, "Saber, Samira" <s.saber@inter-lokaal.nl>, "Boomen, Shanta van den" <s.vandenboomen@inter-lokaal.nl>, "Arts, Thijs" <t.arts@inter-lokaal.nl>, "Hendriks, William" <w.hendriks@inter-lokaal.nl>, "Lubbers, Wilma" <w.lubbers@inter-lokaal.nl>, "Meerkerk, Wim van" <w.vanmeerkerk@inter-lokaal.nl>, "Oijen, Wim van" <w.vanoijen@inter-lokaal.nl>, "Cosgun, Zeynep" <z.cosgun@inter-lokaal.nl>, "Battal, Ayhan" <a.battal@inter-lokaal.nl>, "Scharloo, Tjeu" <t.scharloo@inter-lokaal.nl>, "ART, ART" <art@inter-lokaal.nl>, "HIL, Belasting" <belasting@inter-lokaal.nl>, "HIL, Bestuur" <bestuur@inter-lokaal.nl>, "HIL, Conferentie" <conferentie@inter-lokaal.nl>, "Inter-Lokaal, info Het" <info@inter-lokaal.nl>, "HIL, SHV-WMD" <schuldhulpverlening-wmd@inter-lokaal.nl>, "Klaas, Sinter" <sint@inter-lokaal.nl>, "Abdullah, Reban" <r.abdullah@inter-lokaal.nl>, "Bolek, Kemal" <k.bolek@inter-lokaal.nl>, "Grootboek en Co, werkcorporatie" <grootboekenco@inter-lokaal.nl>, "Heuvel, Monique van den" <m.vandenheuvel@inter-lokaal.nl>, "HIL, expertise centrum" <expertisecentrum@inter-lokaal.nl>, "Mars, Robert" <r.mars@inter-lokaal.nl>, "Wellen, Karin" <k.wellen@inter-lokaal.nl>, "Hokstam, Selma" <s.hokstam@inter-lokaal.nl>, "Het Inter-Lokaal, OR" <or@inter-lokaal.nl>, "Ourahou, Mimi" <m.ourahou@inter-lokaal.nl>, "Karkour, Zafer" <z.karkour@inter-lokaal.nl>, "Khaldi, Asmae" <a.khaldi@inter-lokaal.nl>, "Fransisca, Mylicent" <m.fransisca@inter-lokaal.nl>, "Mellema, Julie" <j.mellema@inter-lokaal.nl>, "Geurts, Petra" <p.geurts@inter-lokaal.nl>, "Waal, Wijndel de" <w.dewaal@inter-lokaal.nl>, "Beijk, Vincent" <v.beijk@inter-lokaal.nl>, "El Bazi, Ihsane" <i.elbazi@inter-lokaal.nl>, "Mango, Mateus" <m.mango@inter-lokaal.nl>, "Sharif-Hashem, Luula" <l.sharif@inter-lokaal.nl>, "Mushnikova, Rositsa" <r.mushnikova@inter-lokaal.nl>, "Wanetie, Sandra" <s.wanetie@inter-lokaal.nl>, "Maas, Max" <m.maas@inter-lokaal.nl>, "El Faghloumi, Naima" <n.elfaghloumi@inter-lokaal.nl>, "Fazel, Tamana" <t.fazel@inter-lokaal.nl>, "Bellari, Hajar" <h.bellari@inter-lokaal.nl>, "Houdt, Rob  van" <r.vanhoudt@inter-lokaal.nl>, "Salsbach, Tasheyna" <t.salsbach@inter-lokaal.nl>, "Silva, Nataly" <n.silva@inter-lokaal.nl>, "Mfuamba, Cassandra" <c.mfuamba@inter-lokaal.nl>, "Alghaddo, Faten" <f.alghaddo@inter-lokaal.nl>, "Ali, Fatma" <f.ali@inter-lokaal.nl>, "Bakhira, Yasmine" <y.bakhira@inter-lokaal.nl>, "Stojkovska, Sanja" <s.stojkovska@inter-lokaal.nl>, "Zijl, Jacintha van" <j.vanzijl@inter-lokaal.nl>, "Stienen, Eric" <e.stienen@inter-lokaal.nl>, "Zitter, Nehemia" <n.zitter@inter-lokaal.nl>, "Wilkens - Ebbeng, Sonja" <s.ebbeng@inter-lokaal.nl>, "Tejic, Milojka" <m.tejic@inter-lokaal.nl>, "Beer, Mark de" <m.debeer@inter-lokaal.nl>, "Baart, Djessica" <d.baart@inter-lokaal.nl>, "Saya, Martha" <m.saya@inter-lokaal.nl>, "Witsenhuijsen, Simone" <s.witsenhuijsen@inter-lokaal.nl>, "Tigow, Ahmed" <a.tigow@inter-lokaal.nl>, "Brons, Edu" <e.brons@inter-lokaal.nl>, "Knuiman, Sylvia" <s.knuiman@inter-lokaal.nl>, "Kwak, Tim" <t.kwak@inter-lokaal.nl>, "Sprock, Shakira" <s.sprock@inter-lokaal.nl>, "Kaatman, Sabien" <s.kaatman@inter-lokaal.nl>, "Aarbodem, Lisa" <l.aarbodem@inter-lokaal.nl>, "Arts, Anouk" <a.arts@inter-lokaal.nl>, "Jaafer, Ali" <a.jaafer@inter-lokaal.nl>, "Schiks, Fenna" <f.schiks@inter-lokaal.nl>, "Ngugi, Kihoro" <k.ngugi@inter-lokaal.nl>, "Leuteren, Manon van" <m.vanleuteren@inter-lokaal.nl>, "Belhaj-Haddou, Kaoutar" <k.belhaj@inter-lokaal.nl>, "2Move, Time" <time2move@inter-lokaal.nl>, "Martha, John" <j.martha@inter-lokaal.nl>, "Smits, Kirstin" <k.smits@inter-lokaal.nl>, "Nes, Jifke van" <j.vannes@inter-lokaal.nl>, "Hekking, Sarah" <s.hekking@inter-lokaal.nl>, "Het Inter-lokaal, Vacature" <vacature@inter-lokaal.nl>, "Frickus, Bo" <b.frickus@inter-lokaal.nl>, "Charifi, Mustapha" <m.charifi@inter-lokaal.nl>', '', '\r\nIn oktober gaat er een training van start, een samenwerking tussen Indigo en Het Inter-lokaal. Deze training is bedoeld voor mantelzorgers van allochtone afkomst die zorg dragen voor naasten (familie) met psychische problemen, of met een lichamelijke of verstandelijke beperking. \r\n\r\nBinnen veel allochtone groepen rust op ziektes of beperkingen een taboe, waardoor mantelzorgers hun zorgen niet kunnen delen. \r\nTijdens deze training willen we dit taboe bespreekbaar maken en aandacht besteden aan zelfzorg, waardoor de mantelzorg minder zwaar\r\nvalt. Tijdens de bijeenkomsten is rukte voor beweging en ontspanning. \r\n\r\nDe training is gratis en bestaat uit vier bijeenkomsten. Een bijeenkomst duurt twee uur. \r\n\r\nIn het Willemskwartier wordt de training vanaf (o.v.b.)  7 oktober aangeboden van 13.00 tot 15.00 uur in ''t Hert, Thijmstraat 40.\r\n\r\nIn Dukenburg is de training op donderdagmiddag van 13.00 tot 15.00 uur in Huisartsenpraktijk de Schakel, Meijhorst 60-07. Start: 30 oktober.\r\n\r\nMijn vraag aan jullie is of jullie mensen kennen met kinderen, partners of andere naasten met een psychische of lichamelijke \r\naandoening, beperking of stoornis. Deze mantelzorgers van allochtone afkomst zou ik graag willen uitnodigen aan de training mee te doen. \r\n\r\n De training in het Willemskwartier wordt door Irm Staarink en Yuk Sie Cheung gegeven. Die in Dukenburg door Hatice Bölek en Sea Bouman. \r\n\r\nBijgaand heb ik de folder bijgevoegd, waarin meer informatie staat over deze training.\r\nOp dinsdag 30 oktober om 12.00u wordt in ''t Hert tijdens het Inloopuur van het SWT een presentatie gegeven over deze training. Iedereen is van harte welkom.\r\n\r\nMochten jullie potentiële deelnemers weten, willen jullie dat mij laten weten?\r\n\r\n\r\nMet vriendelijke groet,\r\n\r\nYuk Sie Cheung\r\n\r\nBudgetcoach | 2e oude Heselaan 386 Nijmegen | \r\nGezondheidsmakelaar Willemskwartier & Kolpingbuurt | Thijmstraat 40 Nijmegen | \r\n\r\n06 - 4265 8046 | y.cheung@inter-lokaal.nl\r\n\r\n\r\n\r\n \r\n\r\n\r\n\r\n \r\n\r\n\r\n\r\nHet Inter-lokaal\r\n\r\n\r\n\r\nLocatie Centrum/West: 2e Oude Heselaan 386, Nijmegen, 024-3222227\r\n\r\n\r\n\r\nLocatie Dukenburg: Zwanenveld 73-18, Nijmegen, 024-3448557\r\n\r\n\r\n\r\nStaf en directie: St. Jorisstraat 72, Nijmegen, 024-3650790\r\n\r\n\r\n\r\n--------------------------------------------------------------------------------------\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nAlle informatie in dit e-mailbericht is onder voorbehoud. Het\r\nInter-lokaal is op geen enkele wijze aansprakelijk voor vergissingen\r\nof onjuistheden in dit bericht.\r\n\r\n\r\n\r\n', 'text/plain'),
(107, 107, 1, '<efa45c74e4cc024f1710513bc35f020b@smtp19.ymlpsrv.net>', '2014-09-26 06:42:00', 'Unieke actie € 10,00 korting', 'Park and Fly | Totalcareparking <info@totalcareparking.nl>', 'test@intermesh.nl', '', '\r\n\r\n<table width="100%" border="0" cellspacing="0" cellpadding="0">\r\n<tr>\r\n<td align="center" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#000000;background-color:#ebebeb;">\r\n<table width="600" border="0" cellspacing="0" cellpadding="0">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#000000;background-color:#ebebeb;">\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; margin-top: 0px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#000000;background-color:#ebebeb;"><div style="text-align: right;"><strong>Park and Fly B.V., nieuwsbrief september 2014</strong></div></td>\r\n</tr>\r\n</table>\r\n<table width="600" border="0" cellspacing="0" cellpadding="0" style="border: 10px solid #fbaf5a; margin-top: 10px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;">\r\n<table width="100%" cellspacing="0" cellpadding="0">\r\n<tr>\r\n<td width="30%" align="left" valign="top" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;">\r\n<table width="100%" cellspacing="0" cellpadding="18" style="border-width: 10px; margin-bottom: 0px;">\r\n<tr>\r\n<td align="left" valign="top" style="padding:18px;height:843px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;">\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 5px; margin-top: 5px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;">\r\n<div style="TEXT-ALIGN: left"><strong> <a href="http://t.ymlp204.net/qysalauhwsyacayjaraemjmb/click.php" style="font-size: 12pt; font-family: verdana, geneva, arial, helvetica, sans-serif; font-weight: normal; color: #003562;"><img border=0 width="173" height="79" style="BORDER-TOP: 0pt; BORDER-RIGHT: 0pt; BORDER-BOTTOM: 0pt; BORDER-LEFT: 0pt" src="http://img2.ymlp204.net/bijoux2132_PFweb_3.jpg"></a></strong></div>\r\n</td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 5px; margin-top: 5px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;"><div style="text-align: left;"><strong> </strong></div>\r\n<div style="text-align: center;"><strong>IN DEZE NIEUWSBRIEF</strong></div>\r\n<div style="text-align: left;"> </div>\r\n<div style="text-align: left;">\r\n<table cellpadding="1" border="0" style="width: 150px;" align="center">\r\n<tr>\r\n<td style="padding:1px;text-align:center;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;"><b>Unieke actie</b></td>\r\n</tr>\r\n<tr>\r\n<td style="padding:1px;text-align:center;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;"><span style="font-weight: bold; text-align: center;">&euro; 10,00 korting</span></td>\r\n</tr>\r\n<tr>\r\n<td style="padding:1px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;"></td>\r\n</tr>\r\n</table>\r\n<strong><br></strong></div></td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 5px; margin-top: 5px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;">\r\n<div style="text-align: center;"><strong> </strong></div>\r\n</td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 5px; margin-top: 5px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;"></td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 10px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;">\r\n<div align="center"><img src="http://img2.ymlp204.net/bijoux2132_DSC0214_1.jpg" style="border: 0pt;" height="127" width="170"></div>\r\n</td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 10px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;">\r\n<div align="center"><img src="http://img2.ymlp204.net/bijoux2132_DSC9926_1.jpg" style="border: 0pt;" height="127" width="170"></div>\r\n</td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 10px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;">\r\n<div align="center"><img src="http://img2.ymlp204.net/bijoux2132_DSC9932_1.jpg" style="border: 0pt;" height="127" width="170"></div>\r\n</td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 10px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;">\r\n<div align="center"><img src="http://img2.ymlp204.net/bijoux2132_AllinParkingSchipholbewakingscameras_1.jpg" style="border: 0pt;" height="127" width="170"></div>\r\n</td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 5px; margin-top: 5px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;">\r\n<div style="TEXT-ALIGN: center"><strong> </strong></div>\r\n</td>\r\n</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n</td>\r\n<td width="70%" align="left" valign="top" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003562;background-color:#fbaf5a;">\r\n<table width="100%" cellspacing="0" cellpadding="15" style="margin-bottom: 0px; border: 1px solid #fbaf5a; margin-top: 0pt;">\r\n<tr>\r\n<td align="left" valign="top" style="padding:15px;height:841px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003370;background-color:#ffffff;">\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 0px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:14pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:bold;color:#1d1363;background-color:#ffffff;"><div style="text-align: left;"> </div>\r\n<div style="text-align: left;"><span style="font-size: 12pt;">Unieke actie: &euro; 10,00 korting</span></div>\r\n<div style="text-align: left;"><span style="font-size: 12pt;"><br></span></div></td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 0px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:14pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:bold;color:#1d1363;background-color:#ffffff;"><div><span style="color: #003370; font-size: 13px; font-weight: normal;"><span style="font-size: 10pt;">Actie: &euro; 10,00 korting op iedereen die reserveert voor november 2014</span></span><span style="color: #003370; font-size: 13px; font-weight: normal;">.</span></div></td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 0px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:14pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:bold;color:#1d1363;background-color:#ffffff;"><div><span style="color: #003370; font-size: 13px; font-weight: normal;">Boek uw parkeerplaats via onderstaande link en de &euro;10,00 korting wordt automatisch verwerkt.</span></div>\r\n<div><span style="color: #003370; font-size: 13px; font-weight: normal;"><br></span></div></td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 0px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:14pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:bold;color:#1d1363;background-color:#ffffff;"><div><span style="text-decoration: underline;"><span style="font-weight: normal;">Link: </span><a href="http://t.ymlp204.net/qyuanauhwsyapayjadaemjmb/click.php" style="font-size:12pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#0000ff;">klik hier voor &euro; 10,00 korting</a></span></div>\r\n<div><span style="color: #3366ff;"><span style="font-weight: normal;"><span style="color: #000080;"><span style="font-size: 10pt;">geldig als u reserveert voor 1 november 2014</span></span></span></span></div>\r\n<div><span style="color: #3366ff;"><span style="font-weight: normal;"><span style="color: #000080;"><span style="font-size: 10pt;"><br></span></span></span></span></div></td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 5px; margin-top: 5px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003370;background-color:#ffffff;"><div><span style="font-size: 10pt;">Wij zijn voortdurend bezig met het verbeteren en optimaliseren van onze dienstverlening. Een belangrijk onderdeel van de dienstverlening is onze website. </span></div>\r\n<div> </div>\r\n<div><span style="font-size: 10pt;">Neemt u gerust een kijkje: </span><a href="http://t.ymlp204.net/qyeakauhwsyazayjakaemjmb/click.php" style="font-size:12pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#0000ff;"><span style="font-size: 10pt;">www.totalcareparking.nl</span></a><span style="font-size: 10pt;"> en laat ons weten wat u ervan vindt. U kunt hiervoor het </span><a href="http://t.ymlp204.net/qymazauhwsyazayjapaemjmb/click.php" style="font-size:12pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#0000ff;"><span style="font-size: 10pt;">contactformulier</span></a><span style="font-size: 10pt;"> gebruiken. Wij stellen uw mening zeer op prijs.</span></div>\r\n<div> </div></td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 10px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003370;background-color:#ffffff;"><div align="center"><img src="http://img2.ymlp204.net/bijoux2132_DSC0070nieuwsbriefdef_1.jpg" width="380" height="276" style="border: 0pt none;"></div></td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 5px; margin-top: 5px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#003370;background-color:#ffffff;">\r\n<div> </div>\r\n<div>We verheugen ons erop u op onze locatie te ontmoeten.</div>\r\n<div> </div>\r\n<div>Met vriendelijke groet,</div>\r\n<div> </div>\r\n<div>Team Park and Fly B.V.</div>\r\n<div>tel: +31 20 65 37 544</div>\r\n</td>\r\n</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n<table width="100%" cellspacing="0" cellpadding="0" style="border-width: 0px; margin-bottom: 0px; margin-top: 0px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#002056;background-color:#fbaf5a;">\r\n<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 5px; margin-top: 5px;">\r\n<tr>\r\n<td align="left" style="padding:0px;font-size:10pt;font-family:verdana, geneva, arial, helvetica, sans-serif;font-weight:normal;color:#002056;background-color:#fbaf5a;">\r\n<div style="TEXT-ALIGN: center"><span><strong><em>Park and Fly B.V., Veenderveld 10, 2371 TV Roelofarendsveen</em></strong></span></div>\r\n</td>\r\n</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n<div align=center style="padding-top:10px;padding-bottom:10px;font-family:Verdana;font-size:8pt;color:#000000;background-color:#EBEBEB"><hr noshade color=#000000 width=50% size=1>\r\n<a href="http://ymlp204.net/ugejuumqgsgemjmbgeuebgguqywje" style="color:#000000;">Uitschrijven / Gegevens wijzigen</a>\r\n<br>\r\n<a href=http://ymlp204.net/m/ style="color:#000000;">Powered door YMLP</a>\r\n</div>\r\n<img alt=" " height="1" src="http://t.ymlp204.net/vemjmbopjujjqnhjg/footer.gif" width="1" border="0">\r\n\r\n\r\n\r\n', 'text/html'),
(108, 108, 1, '<e2bacf4830f22d11a071fc0940f0bdf6@hetgoed.kringloopplanner.nl>', '2014-09-29 05:22:00', 'Afspraak ophaaldienst', 'de Kringloopplanner <info@hetgoed.nl>', '"test@intermesh.nl" <test@intermesh.nl>', '', 'CONTACTGEGEVENS: <br />\r\nNaam: Test Persoon<br />\r\nAdres: Citroenvlinderstraat 1<br />Postcode: 6533SV<br />Plaats: Nijmegen<br />Tel.nr.: 01928478124678<br />  <br />\r\n<br />\r\nAF TE HALEN GOEDEREN: <br />\r\n - Stoel (riet), aantal: 1<br />  <br />\r\n<br />\r\nAFHAALTIJD: <br />\r\n29-12-2014 in de ochtend.  <br />\r\n<br />\r\nGebruik de volgende link om de afspraak definitief te bevestigen: <br />\r\n<br />\r\n<a href="http://hetgoed.kringloopplanner.nl/modules/rittenbesteller/handle_mail_confirm.php?entry_id=710626&code=eb7ff799901d681ca27f5fe543b23107&settings_id=0">Klik hier om uw afspraak te bevestigen</a>\r\n\r\n\r\n', 'text/html'),
(109, 109, 1, '<2d551374afbf64cc89d675c052f5e45a@intermesh.group-office.com>', '2014-10-02 12:59:00', 'Lorem ipsum', 'Test account <test@intermesh.nl>', 'Merijn Schering <mschering@intermesh.nl>', '', '\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nDear Mr / Ms,<br><br>Lorem ipsum<br><br>Best regards,<br>Met vriendelijke groet,<br><br><br>Merijn Schering<font face="arial" size="2"><br></font><br>  <img src="cid:66163e07b6cb4d390ec2d39cc1b71a81@intermesh.group-office.com" style="margin-bottom: 10px;" alt="Intermesh" border="0"><font style="line-height: 20px; font-family: georgia; color: rgb(102, 102, 102); font-size: 12px;"><br><span style="font-weight: bold;">T</span> +31 (0) 73 6445508<br><span style="font-weight: bold;">W</span> <a target="_blank" class="blue" href="http://www.group-office.com">http://www.group-office.com</a> and <a target="_blank" class="blue" href="http://www.intermesh.nl">http://www.intermesh.nl</a><br><span style="font-weight: bold;">KvK</span> 17284308 </font>          \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', 'text/html'),
(110, 110, 1, '<65284127e63b56d9397a31811a393b2d@intermesh.group-office.com>', '2014-10-02 12:59:00', 'Re: Lorem ipsum', 'Merijn Schering (Intermesh) <mschering@intermesh.nl>', 'Test account <test@intermesh.nl>', '', '\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nGaat dit over fransje?<br><br><br><br>Best regards,<br>Met vriendelijke groet,<br><br><br>Merijn Schering<font face="arial" size="2"><br></font><br>  <img src="cid:3f5f4335adc442292596fbe4306d60e4@intermesh.group-office.com" style="margin-bottom: 10px;" alt="Intermesh" border="0"><font style="line-height: 20px; font-family: georgia; color: rgb(102, 102, 102); font-size: 12px;"><br><span style="font-weight: bold;">T</span> +31 (0) 73 6445508<br><span style="font-weight: bold;">W</span> <a target="_blank" class="blue" href="http://www.group-office.com">http://www.group-office.com</a> and <a target="_blank" class="blue" href="http://www.intermesh.nl">http://www.intermesh.nl</a><br><span style="font-weight: bold;">KvK</span> 17284308 </font>          \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n<br><br>At Thursday, 02-10-2014 on 16:59 Test account wrote:<br><blockquote style="border:0;border-left: 2px solid #22437f; padding:0px; margin:0px; padding-left:5px; margin-left: 5px; ">\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nDear Mr / Ms,<br><br>Lorem ipsum<br><br>Best regards,<br>Met vriendelijke groet,<br><br><br>Merijn Schering<font face="arial" size="2"><br></font><br>  <img src="cid:8b2c1f29705c918c6ee65dee1c72bd97@intermesh.group-office.com" style="margin-bottom: 10px;" alt="Intermesh" border="0"><font style="line-height: 20px; font-family: georgia; color: rgb(102, 102, 102); font-size: 12px;"><br><span style="font-weight: bold;">T</span> +31 (0) 73 6445508<br><span style="font-weight: bold;">W</span> <a target="_blank" class="blue" href="http://www.group-office.com">http://www.group-office.com</a> and <a target="_blank" class="blue" href="http://www.intermesh.nl">http://www.intermesh.nl</a><br><span style="font-weight: bold;">KvK</span> 17284308 </font>          \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</blockquote>\r\n', 'text/html'),
(111, 110, 1, '<9033246ced11e98c963f7b78b90c5587@intermesh.group-office.com>', '2014-10-02 13:02:00', 'Re: Lorem ipsum', 'Test account <test@intermesh.nl>', 'Merijn Schering (Intermesh) <mschering@intermesh.nl>', '', '\r\n\r\nNee over Freek!<br><br>Best regards,<br>Met vriendelijke groet,<br><br><br>Merijn Schering<font face="arial" size="2"><br></font><br>  <img src="cid:ec7011b3304ffa96018a0551e171ee48@intermesh.group-office.com" style="margin-bottom: 10px;" alt="Intermesh" border="0"><font style="line-height: 20px; font-family: georgia; color: rgb(102, 102, 102); font-size: 12px;"><br><span style="font-weight: bold;">T</span> +31 (0) 73 6445508<br><span style="font-weight: bold;">W</span> <a target="_blank" class="blue" href="http://www.group-office.com">http://www.group-office.com</a> and <a target="_blank" class="blue" href="http://www.intermesh.nl">http://www.intermesh.nl</a><br><span style="font-weight: bold;">KvK</span> 17284308 </font>          \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n<br><br>At Thursday, 02-10-2014 on 16:59 Merijn Schering (Intermesh) wrote:<br><blockquote style="border:0;border-left: 2px solid #22437f; padding:0px; margin:0px; padding-left:5px; margin-left: 5px; ">\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nGaat dit over fransje?<br><br><br><br>Best regards,<br>Met vriendelijke groet,<br><br><br>Merijn Schering<font face="arial" size="2"><br></font><br>  <img src="cid:21dca55f16d10dc60e806c7bf55e0c04@intermesh.group-office.com" style="margin-bottom: 10px;" alt="Intermesh" border="0"><font style="line-height: 20px; font-family: georgia; color: rgb(102, 102, 102); font-size: 12px;"><br><span style="font-weight: bold;">T</span> +31 (0) 73 6445508<br><span style="font-weight: bold;">W</span> <a target="_blank" class="blue" href="http://www.group-office.com">http://www.group-office.com</a> and <a target="_blank" class="blue" href="http://www.intermesh.nl">http://www.intermesh.nl</a><br><span style="font-weight: bold;">KvK</span> 17284308 </font>          \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n<br><br>At Thursday, 02-10-2014 on 16:59 Test account wrote:<br><blockquote style="border:0;border-left: 2px solid #22437f; padding:0px; margin:0px; padding-left:5px; margin-left: 5px; ">\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nDear Mr / Ms,<br><br>Lorem ipsum<br><br>Best regards,<br>Met vriendelijke groet,<br><br><br>Merijn Schering<font face="arial" size="2"><br></font><br>  <img src="cid:70dc5022b9313cdff2ed9f962ccf9961@intermesh.group-office.com" style="margin-bottom: 10px;" alt="Intermesh" border="0"><font style="line-height: 20px; font-family: georgia; color: rgb(102, 102, 102); font-size: 12px;"><br><span style="font-weight: bold;">T</span> +31 (0) 73 6445508<br><span style="font-weight: bold;">W</span> <a target="_blank" class="blue" href="http://www.group-office.com">http://www.group-office.com</a> and <a target="_blank" class="blue" href="http://www.intermesh.nl">http://www.intermesh.nl</a><br><span style="font-weight: bold;">KvK</span> 17284308 </font>          \r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</blockquote>\r\n</blockquote>\r\n', 'text/html');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `modulesModule`
--

CREATE TABLE IF NOT EXISTS `modulesModule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Gegevens worden uitgevoerd voor tabel `modulesModule`
--

INSERT INTO `modulesModule` (`id`, `name`, `type`, `deleted`) VALUES
(1, 'contacts', 'user', 0),
(4, 'notes', 'user', 0),
(5, 'roles', 'admin', 0),
(6, 'users', 'admin', 0),
(7, 'apibrowser', 'dev', 0),
(8, 'customfields', 'admin', 0),
(9, 'helloworld', 'user', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `modulesModuleRole`
--

CREATE TABLE IF NOT EXISTS `modulesModuleRole` (
  `moduleId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `useAccess` tinyint(1) NOT NULL DEFAULT '0',
  `createAccess` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleId`,`roleId`),
  KEY `roleId` (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden uitgevoerd voor tabel `modulesModuleRole`
--

INSERT INTO `modulesModuleRole` (`moduleId`, `roleId`, `useAccess`, `createAccess`) VALUES
(1, 1, 1, 1),
(1, 2, 1, 1),
(4, 1, 1, 1),
(4, 2, 1, 1),
(5, 1, 1, 1),
(6, 1, 1, 1),
(7, 1, 1, 1),
(8, 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notesNote`
--

CREATE TABLE IF NOT EXISTS `notesNote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerUserId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT 'yellow',
  `sortOrder` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sortOrder` (`sortOrder`),
  KEY `ownerUserId` (`ownerUserId`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Gegevens worden uitgevoerd voor tabel `notesNote`
--

INSERT INTO `notesNote` (`id`, `ownerUserId`, `title`, `content`, `color`, `sortOrder`, `deleted`) VALUES
(1, 1, 'tretreter', 'ertreter', 'yellow', 5, 0),
(2, 1, 'werwerwe', 'ewrewrew', 'yellow', 4, 0),
(3, 29, '6546456', '54754654', 'blue', 1, 0),
(4, 29, 'test', 'test', 'yellow', 1, 0),
(5, 1, 'sdfdsfds', 'dsfdsf', 'yellow', 3, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notesNoteImage`
--

CREATE TABLE IF NOT EXISTS `notesNoteImage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `noteId` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `sortOrder` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `noteId` (`noteId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `notesNoteImage`
--

INSERT INTO `notesNoteImage` (`id`, `noteId`, `path`, `sortOrder`) VALUES
(1, 5, 'Pasfoto 2014.jpg', 0),
(2, 5, 'facebook.jpg', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notesNoteListItem`
--

CREATE TABLE IF NOT EXISTS `notesNoteListItem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `noteId` int(11) NOT NULL,
  `text` text,
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `sortOrder` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `noteId` (`noteId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notesNoteRole`
--

CREATE TABLE IF NOT EXISTS `notesNoteRole` (
  `noteId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `readAccess` tinyint(1) NOT NULL DEFAULT '1',
  `editAccess` tinyint(1) NOT NULL DEFAULT '0',
  `deleteAccess` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`noteId`,`roleId`),
  KEY `roleId` (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `notesNoteRole`
--

INSERT INTO `notesNoteRole` (`noteId`, `roleId`, `readAccess`, `editAccess`, `deleteAccess`) VALUES
(1, 1, 1, 1, 1),
(2, 1, 1, 1, 1),
(3, 26, 1, 1, 1),
(4, 26, 1, 1, 1),
(5, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tagsTag`
--

CREATE TABLE IF NOT EXISTS `tagsTag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Gegevens worden uitgevoerd voor tabel `tagsTag`
--

INSERT INTO `tagsTag` (`id`, `name`) VALUES
(2, 'Een nieuwe tag'),
(5, 'Johan'),
(3, 'Nog een nieuwe tag'),
(1, 'Potential client'),
(6, 'Test'),
(4, 'Vriendin');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `timelineItem`
--

CREATE TABLE IF NOT EXISTS `timelineItem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `ownerUserId` int(11) NOT NULL,
  `modifiedAt` datetime NOT NULL,
  `createdAt` datetime NOT NULL,
  `contactId` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `imapMessageId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ownerUserId` (`ownerUserId`,`contactId`),
  KEY `contactId` (`contactId`),
  KEY `deleted` (`deleted`),
  KEY `imapMessageId` (`imapMessageId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=47 ;

--
-- Gegevens worden uitgevoerd voor tabel `timelineItem`
--

INSERT INTO `timelineItem` (`id`, `deleted`, `ownerUserId`, `modifiedAt`, `createdAt`, `contactId`, `text`, `imapMessageId`) VALUES
(37, 0, 1, '2014-10-02 14:58:16', '2014-09-25 17:30:00', 13, NULL, 106),
(38, 0, 1, '2014-10-02 14:58:16', '2014-09-26 06:42:00', 13, NULL, 107),
(39, 0, 1, '2014-10-02 14:58:16', '2014-09-29 05:22:00', 13, NULL, 108),
(40, 0, 1, '2014-10-02 15:00:05', '2014-10-02 12:59:00', 4, NULL, 109),
(41, 0, 1, '2014-10-02 15:00:05', '2014-10-02 12:59:00', 13, NULL, 109),
(42, 0, 1, '2014-10-02 15:00:08', '2014-10-02 12:59:00', 4, NULL, 110),
(43, 0, 1, '2014-10-02 15:00:08', '2014-10-02 12:59:00', 13, NULL, 110),
(44, 0, 1, '2014-10-03 14:58:41', '2014-10-03 07:40:34', 13, 'lorem ipsum\n\njnzklfjlsadfsd', NULL),
(45, 0, 1, '2014-10-03 10:07:59', '2014-10-03 10:07:59', 4, 'Waar is Fransje?', NULL),
(46, 0, 1, '2014-10-13 12:30:20', '2014-10-13 12:30:20', 4, 'dfggdsfrgdrf', NULL);

--
-- Beperkingen voor gedumpte tabellen
--

--
-- Beperkingen voor tabel `authRole`
--
ALTER TABLE `authRole`
  ADD CONSTRAINT `authRole_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `authUser` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `authToken`
--
ALTER TABLE `authToken`
  ADD CONSTRAINT `authToken_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `authUser` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `authUserRole`
--
ALTER TABLE `authUserRole`
  ADD CONSTRAINT `authUserRole_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `authUser` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `authUserRole_ibfk_2` FOREIGN KEY (`roleId`) REFERENCES `authRole` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `contactsContact`
--
ALTER TABLE `contactsContact`
  ADD CONSTRAINT `contactsContact_ibfk_1` FOREIGN KEY (`ownerUserId`) REFERENCES `authUser` (`id`),
  ADD CONSTRAINT `contactsContact_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `authUser` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contactsContact_ibfk_3` FOREIGN KEY (`companyContactId`) REFERENCES `contactsContact` (`id`) ON DELETE SET NULL;

--
-- Beperkingen voor tabel `contactsContactAddress`
--
ALTER TABLE `contactsContactAddress`
  ADD CONSTRAINT `contactsContactAddress_ibfk_1` FOREIGN KEY (`contactId`) REFERENCES `contactsContact` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `contactsContactDate`
--
ALTER TABLE `contactsContactDate`
  ADD CONSTRAINT `contactsContactDate_ibfk_1` FOREIGN KEY (`contactId`) REFERENCES `contactsContact` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `contactsContactEmailAddress`
--
ALTER TABLE `contactsContactEmailAddress`
  ADD CONSTRAINT `contactsContactEmailAddress_ibfk_1` FOREIGN KEY (`contactId`) REFERENCES `contactsContact` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `contactsContactPhone`
--
ALTER TABLE `contactsContactPhone`
  ADD CONSTRAINT `contactsContactPhone_ibfk_1` FOREIGN KEY (`contactId`) REFERENCES `contactsContact` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `contactsContactRole`
--
ALTER TABLE `contactsContactRole`
  ADD CONSTRAINT `contactsContactRole_ibfk_1` FOREIGN KEY (`roleId`) REFERENCES `authRole` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contactsContactRole_ibfk_2` FOREIGN KEY (`contactId`) REFERENCES `contactsContact` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `contactsContactTag`
--
ALTER TABLE `contactsContactTag`
  ADD CONSTRAINT `contactsContactTag_ibfk_1` FOREIGN KEY (`contactId`) REFERENCES `contactsContact` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contactsContactTag_ibfk_2` FOREIGN KEY (`tagId`) REFERENCES `tagsTag` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `coreSession`
--
ALTER TABLE `coreSession`
  ADD CONSTRAINT `coreSession_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `authUser` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `dropboxAccount`
--
ALTER TABLE `dropboxAccount`
  ADD CONSTRAINT `dropboxAccount_ibfk_1` FOREIGN KEY (`ownerUserId`) REFERENCES `authUser` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `dropboxAccountFolder`
--
ALTER TABLE `dropboxAccountFolder`
  ADD CONSTRAINT `dropboxAccountFolder_ibfk_1` FOREIGN KEY (`folderId`) REFERENCES `filesFile` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dropboxAccountFolder_ibfk_2` FOREIGN KEY (`accountId`) REFERENCES `dropboxAccount` (`ownerUserId`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `modulesModuleRole`
--
ALTER TABLE `modulesModuleRole`
  ADD CONSTRAINT `modulesModuleRole_ibfk_1` FOREIGN KEY (`moduleId`) REFERENCES `modulesModule` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `modulesModuleRole_ibfk_2` FOREIGN KEY (`roleId`) REFERENCES `authRole` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `notesNote`
--
ALTER TABLE `notesNote`
  ADD CONSTRAINT `notesNote_ibfk_1` FOREIGN KEY (`ownerUserId`) REFERENCES `authUser` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `notesNoteImage`
--
ALTER TABLE `notesNoteImage`
  ADD CONSTRAINT `notesNoteImage_ibfk_1` FOREIGN KEY (`noteId`) REFERENCES `notesNote` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `notesNoteListItem`
--
ALTER TABLE `notesNoteListItem`
  ADD CONSTRAINT `notesNoteListItem_ibfk_1` FOREIGN KEY (`noteId`) REFERENCES `notesNote` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `notesNoteRole`
--
ALTER TABLE `notesNoteRole`
  ADD CONSTRAINT `notesNoteRole_ibfk_1` FOREIGN KEY (`noteId`) REFERENCES `notesNote` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notesNoteRole_ibfk_2` FOREIGN KEY (`roleId`) REFERENCES `authRole` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `timelineItem`
--
ALTER TABLE `timelineItem`
  ADD CONSTRAINT `timelineItem_ibfk_1` FOREIGN KEY (`ownerUserId`) REFERENCES `authUser` (`id`),
  ADD CONSTRAINT `timelineItem_ibfk_2` FOREIGN KEY (`contactId`) REFERENCES `contactsContact` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
