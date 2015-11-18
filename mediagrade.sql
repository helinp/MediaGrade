-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 18, 2015 at 09:39 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1-log
-- PHP Version: 5.5.9-1ubuntu4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mediagrade`
--
CREATE DATABASE IF NOT EXISTS `mediagrade` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `mediagrade`;

-- --------------------------------------------------------

--
-- Table structure for table `assessment`
--

CREATE TABLE IF NOT EXISTS `assessment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `objective` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `criteria` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cursor` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=245 ;

--
-- Dumping data for table `assessment`
--

INSERT INTO `assessment` (`id`, `objective`, `criteria`, `cursor`) VALUES
(28, 'FAIRE', 'Conformité de la production', 'produit un travail correspondant aux consignes, mis sa photo dans le répertoire MASTER approprié et '),
(29, 'FAIRE', 'Conformité de la production', 'nommé correctement le fichier'),
(30, 'FAIRE', 'Conformité de la production', 'crée une production complète d’une durée min. de 20 sec et max. de 30 sec.'),
(75, 'CONNAITRE', 'regardé', 'le cours impassiblement'),
(87, 'CONNAITRE', 'regardé2', 'le cours impassiblement2                       '),
(88, 'CONNAITRE', 'regardé3', 'le cours impassiblement3'),
(92, 'CONNAITRE', 'imaginé', 'un monde meilleur'),
(149, 'FAIRE', 'Qualité technique', 'monté la capsule de façon fluide et rythmée, sans faux raccord ni image parasite.                   '),
(150, 'FAIRE', 'Qualité technique', 'produit une bande sonore respectant les normes de diffusion (niveau, qualité\r\néchantillonnage, mixag'),
(151, 'FAIRE', 'Qualité de la production', 'produit une image nette, sans grain, avec une lumière adéquate et pouvant être\r\ndiffusée en HD Ready'),
(158, 'FAIRE', 'Qualité technique', 'produit une bande sonore respectant les normes de diffusion (niveau, qualité\r\néchantillonnage, mixage).                             '),
(159, 'FAIRE', 'Qualité de la production', 'produit une image nette, sans grain, avec une lumière adéquate et pouvant être\r\ndiffusée en HD Ready.                          '),
(195, 'CONNAITRE', 'bugcrit', 'indicbug'),
(197, 'CONNAITRE', 'regardé', 'produit un travail correspondant aux consignes, mis sa photo dans le répertoire MASTER approprié et '),
(198, 'CONNAITRE', 'regardé', 'produit un travail correspondant aux consignes, mis sa photo dans le répertoire MASTER approprié et '),
(199, 'CONNAITRE', 'regardé', 'produit un travail correspondant aux consignes, mis sa photo dans le répertoire MASTER approprié et '),
(200, 'CONNAITRE', 'regardé', 'produit un travail correspondant aux consignes, mis sa photo dans le répertoire MASTER approprié et '),
(201, 'CONNAITRE', 'regardé', 'produit un travail correspondant aux consignes, mis sa photo dans le répertoire MASTER approprié et '),
(202, 'FAIRE', 'Conformité de la production', 'produit un travail correspondant aux consignes, mis sa photo dans le répertoire MASTER approprié et '),
(203, 'FAIRE', 'Conformité de la production', 'crée une production complète d’une durée min. de 20 sec et max. de 30 sec.'),
(204, 'CONNAITRE', 'regardé', 'le cours impassiblement'),
(205, 'CONNAITRE', 'imaginé', 'un monde meilleur'),
(206, 'FAIRE', 'Conformité de la production', 'produit un travail correspondant aux consignes, mis sa photo dans le répertoire MASTER approprié et '),
(207, 'FAIRE', 'Conformité de la production', 'crée une production complète d’une durée min. de 20 sec et max. de 30 sec.'),
(208, 'CONNAITRE', 'regardé', 'le cours impassiblement'),
(209, 'CONNAITRE', 'imaginé', 'un monde meilleur'),
(210, 'FAIRE', 'Conformité de la production', 'produit un travail correspondant aux consignes, mis sa photo dans le répertoire MASTER approprié et '),
(211, 'FAIRE', 'Conformité de la production', 'crée une production complète d’une durée min. de 20 sec et max. de 30 sec.'),
(212, 'CONNAITRE', 'regardé', 'le cours impassiblement'),
(213, 'CONNAITRE', 'imaginé', 'un monde meilleur'),
(214, 'FAIRE', 'Qualité technique', 'monté de façon fluide et rythmée, sans faux raccord ni image parasite.'),
(215, 'FAIRE', 'Qualité technique', 'cadré en coupant proprement les éléments constitutifs de la composition et en évitant\r\nd’y inclure des éléments parasites.'),
(216, 'FAIRE', 'Qualité technique', 'produit une bande sonore respectant les normes de diffusion (niveau, qualité\r\néchantillonnage, mixage).'),
(217, 'FAIRE', 'Qualité technique', 'produit une image nette, sans grain, avec une lumière adéquate et pouvant être\r\ndiffusée en HD Ready.'),
(218, 'FAIRE', 'Qualité technique', 'monté de façon fluide et rythmée, sans faux raccord ni image parasite.'),
(219, 'FAIRE', 'Qualité technique', 'cadré en coupant proprement les éléments constitutifs de la composition et en évitant\r\nd’y inclure des éléments parasites.'),
(220, 'FAIRE', 'Qualité technique', 'produit une bande sonore respectant les normes de diffusion (niveau, qualité\r\néchantillonnage, mixage).'),
(221, 'FAIRE', 'Qualité technique', 'produit une image nette, sans grain, avec une lumière adéquate et pouvant être\r\ndiffusée en HD Ready.'),
(222, 'FAIRE', 'Conformité de la production', 'produit un travail correspondant aux consignes, mis sa photo dans le répertoire MASTER approprié et '),
(223, 'FAIRE', 'Conformité de la production', 'nommé correctement le fichier'),
(224, 'FAIRE', 'Conformité de la production', 'crée une production complète d’une durée min. de 20 sec et max. de 30 sec.'),
(225, 'FAIRE', 'Qualité technique', 'produit une bande sonore respectant les normes de diffusion (niveau, qualité\r\néchantillonnage, mixage).                             '),
(226, 'FAIRE', 'Qualité technique', 'monté de façon fluide et rythmée, sans faux raccord ni image parasite.'),
(227, 'FAIRE', 'Qualité technique', 'cadré en coupant proprement les éléments constitutifs de la composition et en évitant\r\nd’y inclure des éléments parasites.'),
(228, 'FAIRE', 'Qualité technique', 'produit une image nette, sans grain, avec une lumière adéquate et pouvant être\r\ndiffusée en HD Ready.'),
(229, 'FAIRE', 'Conformité de la production', 'crée une production complète d’une durée min. de 20 sec et max. de 30 sec.'),
(230, 'FAIRE', 'Qualité technique', 'monté la capsule de façon fluide et rythmée, sans faux raccord ni image parasite.                   '),
(231, 'CONNAITRE', 'Qualité technique', 'produit une bande sonore respectant les normes de diffusion (niveau, qualité\r\néchantillonnage, mixage).                             '),
(232, 'CONNAITRE', 'Qualité de la production', 'produit une image nette, sans grain, avec une lumière adéquate et pouvant être\r\ndiffusée en HD Ready.                          '),
(233, 'FAIRE', 'Conformité de la production', 'crée une production complète d’une durée min. de 20 sec et max. de 30 sec.'),
(234, 'FAIRE', 'Qualité technique', 'monté la capsule de façon fluide et rythmée, sans faux raccord ni image parasite.                   '),
(235, 'CONNAITRE', 'Qualité technique', 'produit une bande sonore respectant les normes de diffusion (niveau, qualité\r\néchantillonnage, mixage).                             '),
(236, 'CONNAITRE', 'Qualité de la production', 'produit une image nette, sans grain, avec une lumière adéquate et pouvant être\r\ndiffusée en HD Ready.                          '),
(237, 'FAIRE', 'Conformité de la production', 'produit un travail correspondant aux consignes, mis sa photo dans le répertoire MASTER approprié et '),
(238, 'FAIRE', 'Conformité de la production', 'crée une production complète d’une durée min. de 20 sec et max. de 30 sec.'),
(239, 'CONNAITRE', 'regardé', 'le cours impassiblement'),
(240, 'CONNAITRE', 'imaginé', 'un monde meilleur'),
(241, 'FAIRE', 'Qualité technique', 'produit une bande sonore respectant les normes de diffusion (niveau, qualité\r\néchantillonnage, mixage).                             '),
(242, 'FAIRE', 'Qualité technique', 'monté de façon fluide et rythmée, sans faux raccord ni image parasite.'),
(243, 'FAIRE', 'Qualité technique', 'cadré en coupant proprement les éléments constitutifs de la composition et en évitant\r\nd’y inclure des éléments parasites.'),
(244, 'FAIRE', 'Qualité technique', 'produit une image nette, sans grain, avec une lumière adéquate et pouvant être\r\ndiffusée en HD Ready.');

-- --------------------------------------------------------

--
-- Table structure for table `auto_assesment`
--

CREATE TABLE IF NOT EXISTS `auto_assesment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(8000) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `auto_assesment`
--

INSERT INTO `auto_assesment` (`id`, `question`) VALUES
(1, 'Quelles ont été les difficultés que vous avez rencontré lors de la réalisation du projet?'),
(2, 'Qu''avez-vous appris à l''issue de ce projet?');

-- --------------------------------------------------------

--
-- Table structure for table `auto_check`
--

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
-- Table structure for table `lost_password`
--

CREATE TABLE IF NOT EXISTS `lost_password` (
  `id` int(11) NOT NULL,
  `hash` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `periode` tinyint(4) NOT NULL,
  `instructions` varchar(8000) NOT NULL,
  `deadline` date NOT NULL,
  `project_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `assessment_id` text NOT NULL,
  `auto_assessment_id` text NOT NULL,
  `assessment_type` text NOT NULL,
  `skill_id` tinytext NOT NULL,
  `extension` text NOT NULL,
  `is_activated` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`project_id`),
  KEY `id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `periode`, `instructions`, `deadline`, `project_name`, `class`, `assessment_id`, `auto_assessment_id`, `assessment_type`, `skill_id`, `extension`, `is_activated`) VALUES
(9, 3, '', '2015-12-14', 'Philadelphia', '3AV', '28,29,30', '', 'Diagnostique', 'F1,F2,F3', 'jpg', 1),
(15, 1, '', '2015-10-28', 'Diaphragme', '3AV', '28,30,75,92', '', 'Formative', 'F2', '', 0),
(16, 1, '', '2015-10-28', 'LightPainting', '3AV', '28,30,75,92', '', 'Formative', 'A4,A5,A6,A7,A8,C1', 'jpg', 1),
(17, 1, '', '2015-10-30', 'Ateliers', '5AV', '30,149,231,232', '1,2', 'Formative', 'A1,C1,C5,C7,E4,E6,F1,F2,F3,F5,F7,F8,F9', 'jpg', 1),
(18, 3, '', '2015-12-31', 'Les clônes', '5AV', '158,214,215,217', '1', 'Certificative', 'F3,F5,F7,F8,F9', 'jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(9) DEFAULT NULL,
  `project_id` mediumint(9) DEFAULT NULL,
  `skill_id` mediumint(9) DEFAULT NULL,
  `date` date NOT NULL,
  `max_grade` text COLLATE utf8_unicode_ci,
  `user_grade` mediumint(9) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=198 ;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `user_id`, `project_id`, `skill_id`, `date`, `max_grade`, `user_grade`) VALUES
(156, 194, 15, 30, '2015-11-05', NULL, 5),
(157, 194, 15, 149, '2015-11-05', NULL, 5),
(158, 194, 15, 75, '2015-11-08', NULL, 5),
(159, 198, 15, 75, '2015-11-06', NULL, 5),
(160, 198, 9, 28, '2015-11-05', NULL, 7),
(161, 198, 9, 29, '2015-11-18', NULL, 10),
(162, 194, 9, 30, '2015-11-18', NULL, 9),
(167, 194, 16, 28, '2015-11-18', NULL, 8),
(168, 198, 16, 30, '2015-11-18', NULL, 6),
(169, 198, 16, 75, '2015-11-18', NULL, 7),
(170, 198, 16, 92, '2015-11-18', NULL, 4),
(171, 196, 16, 28, '2015-11-18', NULL, 6),
(172, 196, 16, 30, '2015-11-18', NULL, 7),
(173, 196, 16, 75, '2015-11-18', NULL, 10),
(174, 196, 16, 92, '2015-11-18', NULL, 9),
(175, 194, 15, 28, '2015-11-01', NULL, 7),
(176, 194, 15, 75, '2015-11-18', NULL, 7),
(177, 182, 15, 75, '2015-11-18', NULL, 8),
(178, 182, 15, 92, '2015-11-18', NULL, 4),
(179, 194, 15, 28, '2015-11-08', NULL, 1),
(180, 191, 15, 30, '2015-11-08', NULL, 3),
(181, 194, 15, 75, '2015-11-18', NULL, 3),
(182, 191, 15, 92, '2015-11-18', NULL, 0),
(183, 191, 9, 28, '2015-11-18', NULL, 7),
(184, 191, 9, 29, '2015-11-18', NULL, 10),
(185, 191, 9, 30, '2015-11-18', NULL, 8),
(190, 194, 18, 158, '2015-11-18', NULL, 9),
(191, 194, 18, 214, '2015-11-18', NULL, 8),
(192, 194, 18, 215, '2015-11-18', NULL, 7),
(193, 194, 18, 217, '2015-11-18', NULL, 8),
(194, 194, 17, 30, '2015-11-18', NULL, 8),
(195, 194, 17, 149, '2015-11-18', NULL, 7),
(196, 194, 17, 231, '2015-11-18', NULL, 4),
(197, 194, 17, 232, '2015-11-18', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skill_id` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `skill` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`skill_id`),
  UNIQUE KEY `skill_id` (`skill_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=43 ;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `skill_id`, `skill`) VALUES
(24, 'A1', 'Construire le jugement éclairé en structurant la pensée critique (tant vis-à-vis de ses propres réalisations que de celles des autres) et permettre d’échanger ses raisons d’aimer en argumentant :\nau regard de la logique documentaire ou du reportage qui, là où elle est invoquée, implique une approche objective ;\nau regard du « quotient créateur » (puissance transformatrice, originalité de l’apport…) ;\nau regard des possibilités connotatives (ouverture ou fermeture du sens) ;\nau regard de l’existence formelle, visuelle et sonore (image, musique, texte, voix…) constitutive de l’œuvre (espace, composition, tension, formes, valeurs, couleurs, textures, proportions, lumières, échelle, mouvement, raccords, temps…), mais aussi dans le rapport de la forme et du contenu ;\nau regard de sa lisibilité et de son intelligibilité, notamment dans la relation aux conditions de production et de réception ;\nau regard de la norme et du hors norme des codes esthétiques.'),
(25, 'A2', 'Enrichir son jugement esthétique par l’éclairage de connaissances pertinentes du contexte d’émergence de l’œuvre.'),
(26, 'A3', 'Lire et comprendre le fonctionnement des documents audiovisuels existants (presse, annonces, clips, vidéos…), de manière à en assurer la compréhension (aspects sociologiques, sémiologiques, psychologiques…) et la critique de même qu’à pouvoir en évaluer l’impact sur le public.'),
(27, 'A4', 'Apprécier la richesse de ses racines et de son identité culturelle. Imposer le respect naturel et la valorisation des patrimoines.'),
(28, 'A5', 'Reconnaitre l’autre dans la spécificité de son langage et de ce qu’il est en lisant les productions audiovisuelles comme projection de l’imaginaire et rencontre de l’inconscient, du fonds culturel, des pulsions, des souvenirs, de la créativité, du savoir-faire et des connaissances.'),
(29, 'A6', 'Approcher les arts issus d’autres cultures et milieux sociaux ou religieux. Dégager des relations avec les différentes valeurs, traditions et idéologies.'),
(30, 'A7', 'S’ouvrir aux expériences esthétiques contemporaines de manière à les intégrer dans sa culture et ses intérêts.'),
(31, 'A8', 'Gérer ses choix culturels par confrontation et décodage des moyens de communication actuels (affiche, T.V., radio, cinéma, expositions, spots publicitaires, Internet) en les considérant comme sujets d’analyse.'),
(13, 'C1', 'Dégager les caractères stylistiques essentiels d’une écriture audiovisuelle, d’une époque ou d’un style.'),
(22, 'C10', 'Considérer comme indispensable ma fréquentation directe des milieux professionnels, des médiathèques, vidéothèques et cinémathèques, des galeries et musées afin de les connaitre dans leurs vraies dimensions spatiales, humaines et sensibles.'),
(23, 'C11', 'Connaitre les techniques de base des systèmes audiovisuels afin de pouvoir effectuer des choix pratiques judicieux.'),
(14, 'C2', 'Décrire l’enchainement et penser à relier l’apparition ou la résurgence des formes d’expression à leur contexte historique et social. Comprendre qu’elles s’y inscrivent, ou qu’elles peuvent être une rupture. Apprécier l’interaction dynamique entre ces différentes composantes et en quoi le créateur forge, définit, voire remet en question les valeurs et la sensibilité de la culture de telle ou telle société.'),
(15, 'C3', 'Comparer les œuvres du présent et du passé, dégager des correspondances et les convergences fortuites, déceler les influences, apprécier l’impact d’une œuvre à court terme et à long terme, prendre conscience des ruptures, donner du sens.'),
(16, 'C4', 'Connaitre les méthodes d’approche sémiologique et sémantique de base appliquées à la communication (figures et procédés stylistiques, tropes, injonction, redondance…)'),
(17, 'C5', 'Tout en proscrivant le jargon, user d’un vocabulaire précis, nuancé et spécifique à l’égard des techniques employées. Assumer de cette façon la richesse du langage professionnel.'),
(18, 'C6', 'Situer les diverses interventions des acteurs de la production audiovisuelle l’une par rapport à l’autre (chronologie, rôles et interférences).'),
(19, 'C7', 'Comprendre l’esprit et la logique des différents outils, supports et médias en usage dans le domaine de la communication audiovisuelle.'),
(20, 'C8', 'Connaitre de manière critique les principaux ressorts psychologiques agissant à l’intérieur des œuvres médiatiques ou audiovisuelles.'),
(21, 'C9', 'Apprendre à se documenter, à recueillir des témoignages, à recouper son information et à s’informer, en particulier sur l’actualité, l’évolution des médias et des productions audiovisuelles (usage des encyclopédies, des bibliothèques, des médiathèques, des tables des matières, des corrélats, des productions de la presse écrite, des CD-ROM, d’Internet, des dossiers de presse…)'),
(5, 'E1', 'Positionner ses ambitions expressives soit comme tentative de restitution objective de la réalité, soit comme écart entre cette réalité et une manière personnelle de regarder le monde.'),
(6, 'E2', 'Regarder activement le monde extérieur pour donner à voir et/ou à entendre ce qui échappe à la perception commune.'),
(7, 'E3', 'Conférer à l’activité audiovisuelle le statut de lieu de critique et d’écart par rapport à la norme, marquant ainsi la puissance créatrice de l’individu responsable s’identifiant à une création personnelle et « s’écrivant » à travers elle.'),
(8, 'E4', 'Dégager des clés pratiques constituant autant d’outils favorisant la créativité (cadrer, monter, éclairer, filtrer, superposer…) et développant l’imagination.'),
(9, 'E5', 'Transposer dans un autre registre visuel, sonore ou audiovisuel, déplacer dans un autre domaine de l’expression une première expérience formelle.'),
(10, 'E6', 'Pouvoir tirer profit des hasards, convertir les erreurs, utiliser l’inattendu et détourner les choses de leurs fonctions habituelles.'),
(11, 'E7', 'Etre capable de détourner un objet ou une image du sens convenu pour lequel il a été fait et se l’approprier en lui donnant un sens nouveau, inattendu et signifiant.'),
(12, 'E8', 'Exprimer son appréciation sur une œuvre audiovisuelle, justifier ses goûts et en dégager l’intérêt relatif.'),
(32, 'F1', 'Construire le jugement éclairé en structurant la pensée critique (tant vis-à-vis de ses propres réalisations que de celles des autres) et permettre d’échanger ses raisons d’aimer en argumentant.'),
(41, 'F10', 'Pouvoir utiliser les fonctions de base des logiciels les plus courant dans les domaines de la photographie et de l’audiovisuel.'),
(42, 'F11', 'Développer une curiosité pour l’évolution des nouvelles techniques audiovisuelles, être disponible à se les approprier, s’y adapter et à transférer les acquis potentiels dans l’acte de conception lui-même.'),
(33, 'F2', 'Assurer une relative aisance dans la manipulation du matériel audiovisuel de manière à préparer le travail créatif.'),
(34, 'F3', 'Etablir des rapports visuels (cadrage, lumières, couleurs, contrastes, raccords, profondeur de champs, mouvement du sujet et de la caméra…), temporels (rythmes, longueur et succession de séquences…), sonores (intensité, timbre, coloration, fréquences, mixage…), parlés (dialogue, timbre, respiration…) et narratifs de manière cohérente en les articulant sur une intention formelle ou expressive structurante.'),
(35, 'F4', 'Etablir et pouvoir nuancer ses partis pris formels ou expressifs.'),
(36, 'F5', 'Manipuler et expérimenter les techniques argentiques, analogiques et numériques ainsi que la diversité des émulsions et des outils (tout support de traitement de l’image) en les considérant comme des éléments de production et de recherche.'),
(37, 'F6', 'Mettre en corrélation, observer, écouter, penser et produire (dans le sens de produire dans un champ d’application audiovisuel), articuler ainsi la forme et le contenu, le geste et le sens, l’outil et la pensée, l’œuvre et le soi.'),
(38, 'F7', 'Prendre en compte des contraintes externes (cadre scolaire et/ou autre, économie des moyens, conventions, consignes, délais…) ou internes (logique des techniques) dans la structuration du travail audiovisuel.'),
(39, 'F8', 'Participer à des projets collectifs ou multidisciplinaires en y apportant son savoir-faire et en se pliant à la discipline qu’impose l’unité globale d’expression ou d’intention qui les justifie.'),
(40, 'F9', 'Organiser son travail en fonction d’un projet choisi ou imposé en y développant ses capacités d’initiative.'),
(1, 'R1', 'Etablir des rapports de grandeur, de position, de correspondance, de rythme, de proportion, de caractère, de couleurs… au sein de documents visuels et audio visuels.'),
(2, 'R2', 'Pouvoir justifier le choix d’un cadrage ou d’une composition relativement à la scène, au document, au thème ou à l’objet observé.\nInterpréter l’œuvre audiovisuelle comme un système de signes dont il convient d’objectiver les relations (forme et expression).'),
(3, 'R3', 'Distinguer ce qui relève de la dénotation et de la connotation dans l’approche d’une image ou d’un document pour pouvoir établir une analyse aussi objective que possible.'),
(4, 'R4', 'Percevoir à travers quelques œuvres majeures du cinéma, de la photographie et des productions musicales et audiovisuelles les récurrences formelles qui caractérisent le style d’un metteur en scène, d’un artiste ou d’une époque.');

-- --------------------------------------------------------

--
-- Table structure for table `submitted`
--

CREATE TABLE IF NOT EXISTS `submitted` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `file_name` text COLLATE utf8_unicode_ci NOT NULL,
  `answers` varchar(8000) COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=53 ;

--
-- Dumping data for table `submitted`
--

INSERT INTO `submitted` (`id`, `user_id`, `file_path`, `file_name`, `answers`, `project_id`) VALUES
(41, 194, 'uploads/5AV/p1/', 'FOSTER_Maïlys_Ateliers.jpg', 'a:2:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:4:"Rien";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:5:"Tout!";}}', 17),
(42, 116, 'uploads/5AV/p1/', 'TURNER_Holly_Ateliers.jpg', 'a:2:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:6:"Aucune";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:12:"De la bombe!";}}', 17),
(43, 116, 'uploads/5AV/p1/', 'TURNER_Holly_Lesclnes.jpg', 'a:1:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:6:"Aucune";}}', 18),
(44, 120, 'uploads/5AV/p1/', 'ROSARIO_Audrey_Ateliers.jpg', 'a:2:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:1:"1";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:1:"2";}}', 17),
(45, 120, 'uploads/5AV/p1/', 'ROSARIO_Audrey_Lesclnes.jpg', 'a:1:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:3:"123";}}', 18),
(47, 198, 'uploads/3AV/p3/', 'JORDAN_Gaëlle_LightPainting.jpg', 's:0:"";', 16),
(48, 198, 'uploads/3AV/p3/', 'JORDAN_Gaëlle_Philadelphia.jpg', 's:0:"";', 9),
(49, 196, 'uploads/3AV/p3/', 'KNIGHT_Céline_LightPainting.jpg', 's:0:"";', 16),
(50, 191, 'uploads/3AV/p3/', 'EDWARDS_Béatrice_Philadelphia.jpg', 's:0:"";', 9),
(52, 194, 'uploads/5AV/p1/', 'FOSTER_Maxxlys_Les_cl_nes.jpg', 'a:1:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:26:"Tout a été à merveille!";}}', 18);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `is_staff` tinyint(1) NOT NULL,
  PRIMARY KEY (`username`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=203 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `hash`, `name`, `last_name`, `class`, `is_staff`) VALUES
(198, '1', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Gaëlle', 'Jordan', '3AV', 0),
(196, '2', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Céline', 'Knight', '3AV', 0),
(191, '3', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Béatrice', 'Edwards', '3AV', 0),
(182, '4', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Andréa', 'Gonzales', '3AV', 0),
(117, 'Ahmed', '', 'password', 'Amela', 'Pena', '5AV', 0),
(192, 'apereza', '', 'PMr06bieznXN', 'Françoise', 'Perez', '4AV', 0),
(115, 'Bryar', '', 'password', 'Illiana', 'Roberson', '4AV', 0),
(118, 'Cedric', '', 'password', 'Jasper', 'Hendricks', '5AV', 0),
(202, 'charlesvosters', '', '$1$sKRiPhzC$POQ7/kvXdFKnXGWEALjvv0', 'Charles', 'Vosters', '6AV', 0),
(187, 'cjordan5', '', 'PK4rmajdcRH', 'Gaétane', 'Jordan', '5AV', 0),
(185, 'cmartin3', '', 'aICxkaeELn', 'Lén', 'Martin', '6AV', 0),
(183, 'cmorales1', '', 'pEpQBBxe7tii', 'Táng', 'Morales', '5AV', 0),
(199, 'cperezh', '', 'I9LOZQXns', 'Laurène', 'Perez', '6AV', 0),
(186, 'dboyd4', '', 'ApP6vfxggXAU', 'Marie-thérèse', 'Boyd', '6AV', 0),
(195, 'dcunninghamd', '', 'igR2CJTY', 'Kuí', 'Cunningham', '5AV', 0),
(197, 'dtaylorf', '', 'NiXkr2q', 'Océane', 'Taylor', '6AV', 0),
(194, 'fosterm', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Maïlys', 'Foster', '5AV', 0),
(189, 'hlopez7', '', '3D94vy', 'Intéressant', 'Lopez', '5AV', 0),
(116, 'Hollee', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Holly', 'Turner', '5AV', 0),
(190, 'irussell8', '', '6Ddu3SorBDHC', 'Anaïs', 'Russell', '4AV', 0),
(193, 'jlaneb', '', 'khotjzfZ7R', 'Méryl', 'Lane', '6AV', 0),
(200, 'kbaileyi', '', '2CpAYiJ', 'Pénélope', 'Bailey', '5AV', 0),
(112, 'Laith', '', 'password', 'Elaine', 'Nieves', '4AV', 0),
(120, 'Leonard', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Audrey', 'Rosario', '5AV', 0),
(188, 'lriley6', '', 'LKYRBBJU', 'Maëlys', 'Riley', '5AV', 0),
(121, 'Orla', '', 'password', 'Aquila', 'Craig', '6AV', 0),
(1, 'pierreh', 'pierre.helin@gmail.com', '$1$gKHbfuGv$l1aOhC1JTa3pKP2pv.bdf.', 'Pierre', 'Hélin', '', 1),
(119, 'Riley', '', 'password', 'Emmanuel', 'House', '5AV', 0),
(201, 'rrileyj', '', 'sdHbBDRp7r', 'Stévina', 'Riley', '5AV', 0),
(114, 'Stella', '', 'password', 'Alexis', 'Valencia', '4AV', 0),
(10, 'VelasquezDexter V', '', '', 'Paul', 'Bauer', '6AV', 0),
(184, 'wmartinez2', '', 'yxyBemZ18i', 'Aurélie', 'Martinez', '6AV', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
