-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 15 Février 2017 à 17:40
-- Version du serveur: 5.5.49
-- Version de PHP: 5.5.37-1~dotdeb+7.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `mediagrade`
--

-- --------------------------------------------------------

--
-- Structure de la table `assessments`
--

DROP TABLE IF EXISTS `assessments`;
CREATE TABLE IF NOT EXISTS `assessments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skills_group` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `criterion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cursor` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `max_vote` smallint(6) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=345 ;



-- --------------------------------------------------------

--
-- Structure de la table `auto_assesment`
--

DROP TABLE IF EXISTS `auto_assesment`;
CREATE TABLE IF NOT EXISTS `auto_assesment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(8000) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;


--
-- Structure de la table `auto_check`
--

DROP TABLE IF EXISTS `auto_check`;
CREATE TABLE IF NOT EXISTS `auto_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `format` text COLLATE utf8_unicode_ci NOT NULL,
  `codec` text COLLATE utf8_unicode_ci NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `lenght` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `captcha`
--

DROP TABLE IF EXISTS `captcha`;
CREATE TABLE IF NOT EXISTS `captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) unsigned NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `word` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=126 ;



-- --------------------------------------------------------

--
-- Structure de la table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `config`
--

INSERT INTO `config` (`id`, `type`, `content`) VALUES
(2, 'welcome_message', '<h2>Bienvenue, %user_name% !</h2><p>Tu es sur la nouvelle plateforme de remise des projets!</p><p>Je t''invite &agrave; d&eacute;couvrir la nouvelle interface et, surtout, &agrave; recommander tes meilleurs films sur la page "Movie Advisor".</p><p>En route pour r&eacute;ussir les examens de juin!</p><p>P. H&eacute;lin&nbsp;</p>'),
(3, 'periods', 'P1,P2,P3,DEC,JUN');

-- --------------------------------------------------------

--
-- Structure de la table `files_format`
--

DROP TABLE IF EXISTS `files_format`;
CREATE TABLE IF NOT EXISTS `files_format` (
  `mime` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `extension` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `files_format`
--

INSERT INTO `files_format` (`mime`, `extension`) VALUES
('audio/mpeg', 'mp3'),
('audio/mp3', 'mp3'),
('audio/x-wav', 'wav'),
('audio/wav', 'wav'),
('image/gif', 'gif'),
('image/jpeg', 'jpeg'),
('image/jpeg', 'jpg'),
('image/png', 'png'),
('video/mpeg', 'mpg'),
('video/mp4', 'mp4'),
('video/quicktime', 'mov');

-- --------------------------------------------------------

--
-- Structure de la table `lost_password`
--

DROP TABLE IF EXISTS `lost_password`;
CREATE TABLE IF NOT EXISTS `lost_password` (
  `id` int(11) NOT NULL,
  `hash` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(4) NOT NULL,
  `school_year` text NOT NULL,
  `instructions_pdf` varchar(8000) DEFAULT NULL,
  `instructions_txt` text NOT NULL,
  `deadline` date NOT NULL,
  `project_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `self_assessment_ids` text NOT NULL,
  `assessment_type` text NOT NULL,
  `skill_ids` text NOT NULL,
  `extension` text NOT NULL,
  `number_of_files` tinyint(4) NOT NULL DEFAULT '1',
  `is_activated` tinyint(1) NOT NULL DEFAULT '1',
  `admin_id` int(11) NOT NULL,
  `material` text,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;


-- --------------------------------------------------------

--
-- Structure de la table `projects_assessments`
--

DROP TABLE IF EXISTS `projects_assessments`;
CREATE TABLE IF NOT EXISTS `projects_assessments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` smallint(6) NOT NULL,
  `assessment_id` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=91 ;


-- --------------------------------------------------------

--
-- Structure de la table `results`
--

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(9) DEFAULT NULL,
  `project_id` mediumint(9) DEFAULT NULL,
  `assessment_id` mediumint(9) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `max_vote` smallint(6) DEFAULT '10',
  `user_vote` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=424 ;


-- --------------------------------------------------------

--
-- Structure de la table `self_assessments`
--

DROP TABLE IF EXISTS `self_assessments`;
CREATE TABLE IF NOT EXISTS `self_assessments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(8000) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;


-- --------------------------------------------------------

--
-- Structure de la table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skill_id` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `skill_group` text COLLATE utf8_unicode_ci NOT NULL,
  `skill` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`skill_id`),
  UNIQUE KEY `skill_id` (`skill_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=43 ;


--
-- Structure de la table `skills_groups`
--

DROP TABLE IF EXISTS `skills_groups`;
CREATE TABLE IF NOT EXISTS `skills_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;


-- --------------------------------------------------------

--
-- Structure de la table `submitted`
--

DROP TABLE IF EXISTS `submitted`;
CREATE TABLE IF NOT EXISTS `submitted` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `file_path` text COLLATE utf8_unicode_ci,
  `file_name` text COLLATE utf8_unicode_ci,
  `answers` varchar(8000) COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=147 ;


-- --------------------------------------------------------

--
-- Structure de la table `terms`
--

DROP TABLE IF EXISTS `terms`;
CREATE TABLE IF NOT EXISTS `terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Contenu de la table `terms`
--

INSERT INTO `terms` (`id`, `name`) VALUES
(1, 'P1'),
(3, 'P2'),
(4, 'P3'),
(2, 'XDEC'),
(5, 'XJUN');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `role` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`username`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=234 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `name`, `last_name`, `class`, `role`) VALUES
(215, 'student', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Gaëlle', 'Jordan', '4AV', 'student'),
(1, 'teacher', 'teacher@yopmail.com', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Mark', 'Jefferson', '', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `users_config`
--

DROP TABLE IF EXISTS `users_config`;
CREATE TABLE IF NOT EXISTS `users_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `type` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
