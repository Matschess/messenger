-- phpMyAdmin SQL Dump
-- version 4.5.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 25. Jan 2016 um 22:18
-- Server-Version: 10.1.9-MariaDB
-- PHP-Version: 7.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `messenger`
--
CREATE DATABASE IF NOT EXISTS `messenger` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `messenger`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chats`
--

DROP TABLE IF EXISTS `chats`;
CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `user_left_id` int(11) NOT NULL,
  `user_right_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `chats`
--

INSERT INTO `chats` (`id`, `name`, `user_left_id`, `user_right_id`) VALUES
(1, NULL, 184, 185);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `friend` varchar(30) NOT NULL,
  `accepted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `contacts`
--

INSERT INTO `contacts` (`id`, `user_id`, `friend_id`, `friend`, `accepted`) VALUES
(1, 184, 185, 'michaela', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `groupmembers`
--

DROP TABLE IF EXISTS `groupmembers`;
CREATE TABLE `groupmembers` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `groupmembers`
--

INSERT INTO `groupmembers` (`id`, `chat_id`, `user_id`, `admin`) VALUES
(1, 1, 184, 0),
(2, 1, 185, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `chats_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `dataname` varchar(10) NOT NULL,
  `datatype` varchar(5) NOT NULL,
  `sent` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent` datetime NOT NULL,
  `read` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `messages`
--

INSERT INTO `messages` (`id`, `chat_id`, `user_id`, `message`, `sent`, `read`) VALUES
(1, 1, 184, 'Hallo, wie gehts :smile:?', '2016-01-23 08:23:16', NULL),
(2, 1, 185, 'Gut, dir? :+1:', '2016-01-23 15:00:00', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(70) NOT NULL,
  `portrait` varchar(20) DEFAULT NULL,
  `statustext` varchar(200) DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL,
  `last_seen` datetime NOT NULL,
  `isPublic` tinyint(1) NOT NULL DEFAULT '0',
  `lockedPin` tinyint(1) NOT NULL DEFAULT '0',
  `triesPin` int(1) NOT NULL DEFAULT '0',
  `pin` varchar(5) DEFAULT NULL,
  `lockedLogin` tinyint(1) NOT NULL DEFAULT '0',
  `triesLogin` int(1) NOT NULL DEFAULT '0',
  `ipLogin` varchar(39) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `portrait`, `statustext`, `color`, `last_seen`, `isPublic`, `lockedPin`, `triesPin`, `pin`, `lockedLogin`, `triesLogin`, `ipLogin`) VALUES
(184, 'matthias', '81dc9bdb52d04dc20036dbd8313ed055', 'langmattis@gmail.com', '1537vfj.jpg', 'ds', '', '0000-00-00 00:00:00', 1, 0, 0, '', 0, 0, ''),
(185, 'michaela', '81dc9bdb52d04dc20036dbd8313ed055', 'lang.michi00@gmail.com', '1837waj.jpg', 'Michi xD', '', '2016-01-23 20:00:00', 0, 0, 0, '', 0, 0, '');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contacts_users1_idx` (`user_id`);

--
-- Indizes für die Tabelle `groupmembers`
--
ALTER TABLE `groupmembers`
  ADD PRIMARY KEY (`id`,`chat_id`,`user_id`),
  ADD KEY `fk_chatmembers_chats1_idx` (`chat_id`),
  ADD KEY `fk_chatmembers_users1_idx` (`user_id`);

--
-- Indizes für die Tabelle `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`,`chats_id`,`users_id`),
  ADD UNIQUE KEY `media_id_UNIQUE` (`id`),
  ADD KEY `fk_media_users1_idx` (`users_id`),
  ADD KEY `fk_media_chats1_idx` (`chats_id`);

--
-- Indizes für die Tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`,`chat_id`,`user_id`),
  ADD UNIQUE KEY `message_id_UNIQUE` (`id`),
  ADD KEY `fk_messages_chats1_idx` (`chat_id`),
  ADD KEY `fk_messages_users1_idx` (`user_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `groupmembers`
--
ALTER TABLE `groupmembers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `fk_contacts_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `groupmembers`
--
ALTER TABLE `groupmembers`
  ADD CONSTRAINT `fk_chatmembers_chats1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_chatmembers_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `fk_media_chats1` FOREIGN KEY (`chats_id`) REFERENCES `chats` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_media_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_chats1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_messages_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
