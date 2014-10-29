-- phpMyAdmin SQL Dump
-- version 4.2.6deb1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Gegenereerd op: 29 okt 2014 om 15:10
-- Serverversie: 5.5.40-0ubuntu1
-- PHP-versie: 5.5.12-2ubuntu4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `go7`
--
CREATE DATABASE IF NOT EXISTS `go7` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `go7`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `announcementsAnnouncement`
--

DROP TABLE IF EXISTS `announcementsAnnouncement`;
CREATE TABLE IF NOT EXISTS `announcementsAnnouncement` (
`id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `ownerUserId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `imagePath` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `authRole`
--

DROP TABLE IF EXISTS `authRole`;
CREATE TABLE IF NOT EXISTS `authRole` (
`id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `autoAdd` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userId` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1079 ;

--
-- Gegevens worden geëxporteerd voor tabel `authRole`
--

INSERT INTO `authRole` (`id`, `deleted`, `autoAdd`, `name`, `userId`) VALUES
(1, 0, 0, 'Admins', 1),
(2, 0, 0, 'Everyone', NULL),
(24, 0, 1, 'Intermesh BV', NULL),
(28, 1, 0, 'test1', NULL),
(29, 0, 0, 'asdsadsad', NULL),
(30, 0, 0, 'jodsfsd', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `authToken`
--

DROP TABLE IF EXISTS `authToken`;
CREATE TABLE IF NOT EXISTS `authToken` (
`id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `series` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expiresAt` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `authUser`
--

DROP TABLE IF EXISTS `authUser`;
CREATE TABLE IF NOT EXISTS `authUser` (
`id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `digest` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1079 ;

--
-- Gegevens worden geëxporteerd voor tabel `authUser`
--

INSERT INTO `authUser` (`id`, `deleted`, `enabled`, `username`, `password`, `digest`, `createdAt`, `modifiedAt`) VALUES
(1, 0, 1, 'admin', '$1$DbSzAYcF$oc9bUIm.SBRjCD24ZcKg//', '508fd3bc6f1ecfedaa475586ce0b4f2f', '2014-07-21 14:01:17', '2014-08-05 15:16:05');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `authUserRole`
--

DROP TABLE IF EXISTS `authUserRole`;
CREATE TABLE IF NOT EXISTS `authUserRole` (
  `userId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `authUserRole`
--

INSERT INTO `authUserRole` (`userId`, `roleId`) VALUES
(1, 1),
(1, 2),
(1, 24);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContact`
--

DROP TABLE IF EXISTS `contactsContact`;
CREATE TABLE IF NOT EXISTS `contactsContact` (
`id` int(11) NOT NULL,
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
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `IBAN` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `registrationNumber` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `companyContactId` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1576 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactAddress`
--

DROP TABLE IF EXISTS `contactsContactAddress`;
CREATE TABLE IF NOT EXISTS `contactsContactAddress` (
`id` int(11) NOT NULL,
  `contactId` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `zipCode` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `state` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country` char(2) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactCustomFields`
--

DROP TABLE IF EXISTS `contactsContactCustomFields`;
CREATE TABLE IF NOT EXISTS `contactsContactCustomFields` (
  `id` int(11) NOT NULL,
  `Speelsterkte dubbel` double DEFAULT '9',
  `Lid sinds` date DEFAULT NULL,
  `zaterdagInvaller` tinyint(1) NOT NULL DEFAULT '0',
  `Speelsterkte enkel` double DEFAULT '9',
  `Bondsnummer` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `zondagInvaller` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactDate`
--

DROP TABLE IF EXISTS `contactsContactDate`;
CREATE TABLE IF NOT EXISTS `contactsContactDate` (
`id` int(11) NOT NULL,
  `contactId` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'birthday',
  `date` date NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1530 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactEmailAddress`
--

DROP TABLE IF EXISTS `contactsContactEmailAddress`;
CREATE TABLE IF NOT EXISTS `contactsContactEmailAddress` (
`id` int(11) NOT NULL,
  `contactId` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'work',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1466 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactPhone`
--

DROP TABLE IF EXISTS `contactsContactPhone`;
CREATE TABLE IF NOT EXISTS `contactsContactPhone` (
`id` int(11) NOT NULL,
  `contactId` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'work,voice',
  `number` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1995 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactRole`
--

DROP TABLE IF EXISTS `contactsContactRole`;
CREATE TABLE IF NOT EXISTS `contactsContactRole` (
  `contactId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `readAccess` tinyint(1) NOT NULL DEFAULT '1',
  `uploadAccess` tinyint(1) NOT NULL DEFAULT '0',
  `editAccess` tinyint(1) NOT NULL DEFAULT '0',
  `deleteAccess` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contactsContactTag`
--

DROP TABLE IF EXISTS `contactsContactTag`;
CREATE TABLE IF NOT EXISTS `contactsContactTag` (
  `contactId` int(11) NOT NULL,
  `tagId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `coreSession`
--

DROP TABLE IF EXISTS `coreSession`;
CREATE TABLE IF NOT EXISTS `coreSession` (
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `customFieldsField`
--

DROP TABLE IF EXISTS `customFieldsField`;
CREATE TABLE IF NOT EXISTS `customFieldsField` (
`id` int(11) NOT NULL,
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
  `filterable` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=40 ;

--
-- Gegevens worden geëxporteerd voor tabel `customFieldsField`
--

INSERT INTO `customFieldsField` (`id`, `fieldSetId`, `sortOrder`, `type`, `name`, `databaseName`, `placeholder`, `required`, `defaultValue`, `_data`, `deleted`, `filterable`) VALUES
(25, 10, 8, 'number', 'Speelsterkte dubbel', 'Speelsterkte dubbel', 'De placeholder...', 0, '9', '{"options":[{"value":"9"},{"value":"8"},{"value":"7"},{"value":"6"},{"value":"5"},{"value":"4"},{"value":"3"},{"value":"2"},{"value":"1"}],"maxLength":50}', 0, 1),
(26, 10, 7, 'date', 'Lid sinds', 'Lid sinds', 'De placeholder...', 0, '', '{"options":[]}', 0, 0),
(29, 10, 10, 'checkbox', 'Beschikbaar als invaller op Zaterdag', 'zaterdagInvaller', '', 0, '0', '{"options":[]}', 0, 1),
(36, 10, 9, 'number', 'Speelsterkte enkel', 'Speelsterkte enkel', '', 0, '9', '{"options":[],"maxLength":50}', 0, 1),
(37, 10, 6, 'text', 'Bondsnummer', 'Bondsnummer', 'Vul in aub...', 1, '', '{"options":[{"value":"asdsd"},{"value":""},{"value":""}],"maxLength":50}', 0, 1),
(38, 10, 11, 'checkbox', 'Beschikbaar als invaller op Zondag', 'zondagInvaller', '', 0, '0', '{"options":[]}', 0, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `customFieldsFieldSet`
--

DROP TABLE IF EXISTS `customFieldsFieldSet`;
CREATE TABLE IF NOT EXISTS `customFieldsFieldSet` (
`id` int(11) NOT NULL,
  `sortOrder` int(11) NOT NULL DEFAULT '0',
  `modelName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Gegevens worden geëxporteerd voor tabel `customFieldsFieldSet`
--

INSERT INTO `customFieldsFieldSet` (`id`, `sortOrder`, `modelName`, `name`, `deleted`) VALUES
(10, 2, 'Intermesh\\Modules\\Contacts\\Model\\ContactCustomFields', 'Tennis', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dropboxAccount`
--

DROP TABLE IF EXISTS `dropboxAccount`;
CREATE TABLE IF NOT EXISTS `dropboxAccount` (
  `ownerUserId` int(11) NOT NULL,
  `accessToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `requestToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deltaCursor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropboxUserId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dropboxAccountFolder`
--

DROP TABLE IF EXISTS `dropboxAccountFolder`;
CREATE TABLE IF NOT EXISTS `dropboxAccountFolder` (
  `accountId` int(11) NOT NULL,
  `folderId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `filesFile`
--

DROP TABLE IF EXISTS `filesFile`;
CREATE TABLE IF NOT EXISTS `filesFile` (
`id` int(11) NOT NULL,
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
  `modelId` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `imapAccount`
--

DROP TABLE IF EXISTS `imapAccount`;
CREATE TABLE IF NOT EXISTS `imapAccount` (
`id` int(11) NOT NULL,
  `ownerUserId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `port` int(11) NOT NULL DEFAULT '993',
  `encrytion` enum('ssl','tls') COLLATE utf8_unicode_ci DEFAULT 'ssl',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `syncedAt` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `imapAttachment`
--

DROP TABLE IF EXISTS `imapAttachment`;
CREATE TABLE IF NOT EXISTS `imapAttachment` (
`id` int(11) NOT NULL,
  `messageId` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contentType` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contentId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inline` tinyint(1) NOT NULL DEFAULT '0',
  `size` bigint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `imapMessage`
--

DROP TABLE IF EXISTS `imapMessage`;
CREATE TABLE IF NOT EXISTS `imapMessage` (
`id` int(11) NOT NULL,
  `threadId` int(11) DEFAULT NULL,
  `ownerUserId` int(11) NOT NULL,
  `messageId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `to` text COLLATE utf8_unicode_ci,
  `cc` text COLLATE utf8_unicode_ci,
  `body` text COLLATE utf8_unicode_ci,
  `contentType` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text/html'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=112 ;

--
-- Gegevens worden geëxporteerd voor tabel `imapMessage`
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

DROP TABLE IF EXISTS `modulesModule`;
CREATE TABLE IF NOT EXISTS `modulesModule` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Gegevens worden geëxporteerd voor tabel `modulesModule`
--

INSERT INTO `modulesModule` (`id`, `name`, `type`, `deleted`) VALUES
(1, 'contacts', 'user', 0),
(4, 'notes', 'user', 0),
(5, 'roles', 'admin', 0),
(6, 'users', 'admin', 0),
(7, 'apibrowser', 'dev', 0),
(8, 'customfields', 'admin', 0),
(9, 'helloworld', 'user', 0),
(10, 'announcements', 'user', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `modulesModuleRole`
--

DROP TABLE IF EXISTS `modulesModuleRole`;
CREATE TABLE IF NOT EXISTS `modulesModuleRole` (
  `moduleId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `useAccess` tinyint(1) NOT NULL DEFAULT '0',
  `createAccess` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `modulesModuleRole`
--

INSERT INTO `modulesModuleRole` (`moduleId`, `roleId`, `useAccess`, `createAccess`) VALUES
(1, 1, 1, 1),
(1, 2, 1, 0),
(4, 1, 1, 1),
(5, 1, 1, 1),
(6, 1, 1, 1),
(7, 1, 1, 1),
(8, 1, 1, 1),
(9, 1, 1, 1),
(10, 1, 1, 1),
(10, 2, 1, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notesNote`
--

DROP TABLE IF EXISTS `notesNote`;
CREATE TABLE IF NOT EXISTS `notesNote` (
`id` int(11) NOT NULL,
  `ownerUserId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT 'yellow',
  `sortOrder` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notesNoteImage`
--

DROP TABLE IF EXISTS `notesNoteImage`;
CREATE TABLE IF NOT EXISTS `notesNoteImage` (
`id` int(11) NOT NULL,
  `noteId` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `sortOrder` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notesNoteListItem`
--

DROP TABLE IF EXISTS `notesNoteListItem`;
CREATE TABLE IF NOT EXISTS `notesNoteListItem` (
`id` int(11) NOT NULL,
  `noteId` int(11) NOT NULL,
  `text` text,
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `sortOrder` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `notesNoteRole`
--

DROP TABLE IF EXISTS `notesNoteRole`;
CREATE TABLE IF NOT EXISTS `notesNoteRole` (
  `noteId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `readAccess` tinyint(1) NOT NULL DEFAULT '1',
  `editAccess` tinyint(1) NOT NULL DEFAULT '0',
  `deleteAccess` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tagsTag`
--

DROP TABLE IF EXISTS `tagsTag`;
CREATE TABLE IF NOT EXISTS `tagsTag` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `timelineItem`
--

DROP TABLE IF EXISTS `timelineItem`;
CREATE TABLE IF NOT EXISTS `timelineItem` (
`id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `ownerUserId` int(11) NOT NULL,
  `modifiedAt` datetime NOT NULL,
  `createdAt` datetime NOT NULL,
  `contactId` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `imapMessageId` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `announcementsAnnouncement`
--
ALTER TABLE `announcementsAnnouncement`
 ADD PRIMARY KEY (`id`), ADD KEY `ownerUserId` (`ownerUserId`), ADD KEY `deleted` (`deleted`);

--
-- Indexen voor tabel `authRole`
--
ALTER TABLE `authRole`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `userId` (`userId`), ADD UNIQUE KEY `name` (`name`), ADD KEY `autoAdd` (`autoAdd`), ADD KEY `deleted` (`deleted`);

--
-- Indexen voor tabel `authToken`
--
ALTER TABLE `authToken`
 ADD PRIMARY KEY (`id`), ADD KEY `userId` (`userId`,`series`);

--
-- Indexen voor tabel `authUser`
--
ALTER TABLE `authUser`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `username_2` (`username`), ADD KEY `deleted` (`deleted`);

--
-- Indexen voor tabel `authUserRole`
--
ALTER TABLE `authUserRole`
 ADD PRIMARY KEY (`userId`,`roleId`), ADD KEY `roleId` (`roleId`);

--
-- Indexen voor tabel `contactsContact`
--
ALTER TABLE `contactsContact`
 ADD PRIMARY KEY (`id`), ADD KEY `ownerUserId` (`ownerUserId`), ADD KEY `deleted` (`deleted`), ADD KEY `userId` (`userId`), ADD KEY `companyContactId` (`companyContactId`);

--
-- Indexen voor tabel `contactsContactAddress`
--
ALTER TABLE `contactsContactAddress`
 ADD PRIMARY KEY (`id`), ADD KEY `contactId` (`contactId`);

--
-- Indexen voor tabel `contactsContactCustomFields`
--
ALTER TABLE `contactsContactCustomFields`
 ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `contactsContactDate`
--
ALTER TABLE `contactsContactDate`
 ADD PRIMARY KEY (`id`), ADD KEY `contactId` (`contactId`);

--
-- Indexen voor tabel `contactsContactEmailAddress`
--
ALTER TABLE `contactsContactEmailAddress`
 ADD PRIMARY KEY (`id`), ADD KEY `contactId` (`contactId`);

--
-- Indexen voor tabel `contactsContactPhone`
--
ALTER TABLE `contactsContactPhone`
 ADD PRIMARY KEY (`id`), ADD KEY `contactId` (`contactId`);

--
-- Indexen voor tabel `contactsContactRole`
--
ALTER TABLE `contactsContactRole`
 ADD PRIMARY KEY (`contactId`,`roleId`), ADD KEY `roleId` (`roleId`), ADD KEY `read` (`readAccess`,`editAccess`,`deleteAccess`);

--
-- Indexen voor tabel `contactsContactTag`
--
ALTER TABLE `contactsContactTag`
 ADD PRIMARY KEY (`contactId`,`tagId`), ADD KEY `tagId` (`tagId`);

--
-- Indexen voor tabel `coreSession`
--
ALTER TABLE `coreSession`
 ADD PRIMARY KEY (`id`), ADD KEY `userId` (`userId`);

--
-- Indexen voor tabel `customFieldsField`
--
ALTER TABLE `customFieldsField`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `databaseName` (`databaseName`), ADD KEY `fieldSetId` (`fieldSetId`), ADD KEY `deleted` (`deleted`), ADD KEY `sortOrder` (`sortOrder`);

--
-- Indexen voor tabel `customFieldsFieldSet`
--
ALTER TABLE `customFieldsFieldSet`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`), ADD KEY `model` (`modelName`), ADD KEY `deleted` (`deleted`);

--
-- Indexen voor tabel `dropboxAccount`
--
ALTER TABLE `dropboxAccount`
 ADD PRIMARY KEY (`ownerUserId`);

--
-- Indexen voor tabel `dropboxAccountFolder`
--
ALTER TABLE `dropboxAccountFolder`
 ADD PRIMARY KEY (`accountId`,`folderId`), ADD KEY `folderId` (`folderId`);

--
-- Indexen voor tabel `filesFile`
--
ALTER TABLE `filesFile`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `parentId` (`parentId`,`isFolder`,`name`), ADD KEY `ownerUserId` (`ownerUserId`,`parentId`), ADD KEY `folderId` (`parentId`), ADD KEY `isFolder` (`isFolder`), ADD KEY `deleted` (`deleted`);

--
-- Indexen voor tabel `imapAccount`
--
ALTER TABLE `imapAccount`
 ADD PRIMARY KEY (`id`), ADD KEY `ownerUserId` (`ownerUserId`);

--
-- Indexen voor tabel `imapAttachment`
--
ALTER TABLE `imapAttachment`
 ADD PRIMARY KEY (`id`), ADD KEY `messageId` (`messageId`);

--
-- Indexen voor tabel `imapMessage`
--
ALTER TABLE `imapMessage`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `messageId` (`messageId`), ADD KEY `owner` (`ownerUserId`), ADD KEY `threadId` (`threadId`);

--
-- Indexen voor tabel `modulesModule`
--
ALTER TABLE `modulesModule`
 ADD PRIMARY KEY (`id`), ADD KEY `deleted` (`deleted`);

--
-- Indexen voor tabel `modulesModuleRole`
--
ALTER TABLE `modulesModuleRole`
 ADD PRIMARY KEY (`moduleId`,`roleId`), ADD KEY `roleId` (`roleId`);

--
-- Indexen voor tabel `notesNote`
--
ALTER TABLE `notesNote`
 ADD PRIMARY KEY (`id`), ADD KEY `sortOrder` (`sortOrder`), ADD KEY `ownerUserId` (`ownerUserId`), ADD KEY `deleted` (`deleted`);

--
-- Indexen voor tabel `notesNoteImage`
--
ALTER TABLE `notesNoteImage`
 ADD PRIMARY KEY (`id`), ADD KEY `noteId` (`noteId`);

--
-- Indexen voor tabel `notesNoteListItem`
--
ALTER TABLE `notesNoteListItem`
 ADD PRIMARY KEY (`id`), ADD KEY `noteId` (`noteId`);

--
-- Indexen voor tabel `notesNoteRole`
--
ALTER TABLE `notesNoteRole`
 ADD PRIMARY KEY (`noteId`,`roleId`), ADD KEY `roleId` (`roleId`);

--
-- Indexen voor tabel `tagsTag`
--
ALTER TABLE `tagsTag`
 ADD PRIMARY KEY (`id`), ADD KEY `name` (`name`);

--
-- Indexen voor tabel `timelineItem`
--
ALTER TABLE `timelineItem`
 ADD PRIMARY KEY (`id`), ADD KEY `ownerUserId` (`ownerUserId`,`contactId`), ADD KEY `contactId` (`contactId`), ADD KEY `deleted` (`deleted`), ADD KEY `imapMessageId` (`imapMessageId`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `announcementsAnnouncement`
--
ALTER TABLE `announcementsAnnouncement`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT voor een tabel `authRole`
--
ALTER TABLE `authRole`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1079;
--
-- AUTO_INCREMENT voor een tabel `authToken`
--
ALTER TABLE `authToken`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT voor een tabel `authUser`
--
ALTER TABLE `authUser`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1079;
--
-- AUTO_INCREMENT voor een tabel `contactsContact`
--
ALTER TABLE `contactsContact`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1576;
--
-- AUTO_INCREMENT voor een tabel `contactsContactAddress`
--
ALTER TABLE `contactsContactAddress`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `contactsContactDate`
--
ALTER TABLE `contactsContactDate`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1530;
--
-- AUTO_INCREMENT voor een tabel `contactsContactEmailAddress`
--
ALTER TABLE `contactsContactEmailAddress`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1466;
--
-- AUTO_INCREMENT voor een tabel `contactsContactPhone`
--
ALTER TABLE `contactsContactPhone`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1995;
--
-- AUTO_INCREMENT voor een tabel `customFieldsField`
--
ALTER TABLE `customFieldsField`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT voor een tabel `customFieldsFieldSet`
--
ALTER TABLE `customFieldsFieldSet`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT voor een tabel `filesFile`
--
ALTER TABLE `filesFile`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT voor een tabel `imapAccount`
--
ALTER TABLE `imapAccount`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `imapAttachment`
--
ALTER TABLE `imapAttachment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `imapMessage`
--
ALTER TABLE `imapMessage`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT voor een tabel `modulesModule`
--
ALTER TABLE `modulesModule`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT voor een tabel `notesNote`
--
ALTER TABLE `notesNote`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT voor een tabel `notesNoteImage`
--
ALTER TABLE `notesNoteImage`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `notesNoteListItem`
--
ALTER TABLE `notesNoteListItem`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `tagsTag`
--
ALTER TABLE `tagsTag`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT voor een tabel `timelineItem`
--
ALTER TABLE `timelineItem`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `announcementsAnnouncement`
--
ALTER TABLE `announcementsAnnouncement`
ADD CONSTRAINT `announcementsAnnouncement_ibfk_1` FOREIGN KEY (`ownerUserId`) REFERENCES `authUser` (`id`);

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
-- Beperkingen voor tabel `contactsContactCustomFields`
--
ALTER TABLE `contactsContactCustomFields`
ADD CONSTRAINT `contactsContactCustomFields_ibfk_1` FOREIGN KEY (`id`) REFERENCES `contactsContact` (`id`) ON DELETE CASCADE;

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