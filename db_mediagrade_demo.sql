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

--
-- Contenu de la table `assessments`
--

INSERT INTO `assessments` (`id`, `skills_group`, `criterion`, `cursor`, `max_vote`) VALUES
(246, 'FAIRE', 'Cadrage', 'soigné le cadre (sans mauvaise coupe ou d’objets indésirables)', 10),
(247, 'FAIRE', 'Mise en lumière', 'soigné la mise en lumière (sans ombres\nindésirables, déséquilibre, qualité),', 10),
(248, 'FAIRE', 'Maîtrise de l''APN', 'soigné l’image (bien exposée, nette et sans grain inadéquat),', 10),
(249, 'FAIRE', 'Infographie', 'soigné le montage infographique soigné et invisible (détourage, résolution, couleurs, ombres),', 10),
(269, 'FAIRE', 'Maîtrise de l''APN', 'exposé parfaitement les photographies qui constituent l''animation', 10),
(280, 'FAIRE', 'Montage', 'monté la capsule de façon fluide et rythmée, sans faux raccord ni image parasite.     ', 10),
(284, 'FAIRE', 'Technique', 'réalisé les différents flous, selon les techniques demandées', 10),
(285, 'FAIRE', 'Créativité', 'utilisé la technique de manière créative', 5),
(291, 'APPRECIER', 'Mise en lumière', 'travaillé la lumière de manière à ce qu''elle figure le mot choisi', 20),
(292, 'FAIRE', 'Maîtrise de l''APN', 'correctement mis au point la photographie', 5),
(293, 'FAIRE', 'Maîtrise de l''APN', 'correctement exposé la photographie', 5),
(294, 'FAIRE', 'Cadrage', 'cadré de manière équilibrée et en coupant les élèments indésirables', 5),
(295, 'APPRECIER', 'Cadrage', 'travaillé le cadre de manière à ce qu''il figure le mot choisi', 10);

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
-- Contenu de la table `auto_assesment`
--

INSERT INTO `auto_assesment` (`id`, `question`) VALUES
(1, 'Quelles ont été les difficultés que vous avez rencontré lors de la réalisation du projet?'),
(2, 'Qu''avez-vous appris à l''issue de ce projet?');

-- --------------------------------------------------------

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

--
-- Contenu de la table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `project_id`, `comment`) VALUES
(59, 219, 10, 'Attention aux ombres, mal considérées elle peuvent gacher l''équilibre de ton image.'),
(60, 219, 7, 'N''hésite pas à aller plus loin dans ta recherche'),
(61, 215, 4, 'Très chouette résultat, cela valait la peine d''''être patiente, bravo!'),
(62, 215, 1, 'Dommage pour la surexposition, tu dois prendre ta mesure sur les hautes lumières!'),
(125, 211, 4, 'Très chouette résultat, cela valait la peine d''''être patiente, bravo!');

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

--
-- Contenu de la table `projects`
--

INSERT INTO `projects` (`id`, `term`, `school_year`, `instructions_pdf`, `instructions_txt`, `deadline`, `project_name`, `class`, `self_assessment_ids`, `assessment_type`, `skill_ids`, `extension`, `number_of_files`, `is_activated`, `admin_id`, `material`) VALUES
(1, 'P2', '2016-2017', 'uploads/2015-2016/4AV/instructions/4AV_2_L_vitations.pdf', '', '2016-05-14', 'Lévitations', '4AV', '1,2', 'Formative', 'F1,F10,F11,F2,F3,F4,F5,F6,F7,F8,F9', 'jpg', 1, 1, 1, 'apn,temps de pose,photoshop'),
(4, 'P2', '2016-2017', 'uploads/2015-2016/4AV/instructions/4AV_2_Stopmotion.pdf', '', '2016-05-15', 'Stopmotion', '4AV', '1,2', 'Formative', 'F1,F10,F11,F2,F3,F4,F5,F6,F7,F8,F9', 'mp4', 1, 1, 1, 'animation,premiere'),
(7, 'P2', '2016-2017', '', '', '2016-05-06', 'Les flous', '3AV', '', 'Formative', 'E1,E4,E6,F3,F5,F7,F8,F9', 'jpg', 5, 1, 1, 'apn,temps de pose,mise au point,diaphragme'),
(10, 'P3', '2016-2017', '', '', '2016-04-28', '1 mot = 1 éclairage', '3AV', '1,2,3', 'Formative', 'F2,F3,F4,F5,F7,F9,R2', 'jpg', 1, 1, 1, 'lumière,apn,studio');

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

--
-- Contenu de la table `projects_assessments`
--

INSERT INTO `projects_assessments` (`id`, `project_id`, `assessment_id`) VALUES
(1, 1, 246),
(2, 1, 247),
(3, 1, 248),
(4, 1, 249),
(10, 4, 269),
(11, 4, 280),
(17, 7, 284),
(18, 7, 285),
(24, 10, 291),
(25, 10, 292),
(26, 10, 293),
(27, 10, 294),
(28, 10, 295);

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

--
-- Contenu de la table `results`
--

INSERT INTO `results` (`id`, `user_id`, `project_id`, `assessment_id`, `date`, `max_vote`, `user_vote`) VALUES
(183, 215, 4, 269, '2016-04-04 03:35:04', 10, 9),
(184, 215, 4, 280, '2016-04-04 03:35:04', 10, 9),
(185, 211, 4, 269, '2016-04-04 03:35:19', 10, 7),
(186, 211, 4, 280, '2016-04-04 03:35:19', 10, 8),
(187, 219, 10, 291, '2016-04-04 03:43:59', 20, 16),
(188, 219, 10, 292, '2016-04-04 03:43:59', 5, 5),
(189, 219, 10, 293, '2016-04-04 03:43:59', 5, 5),
(190, 219, 10, 294, '2016-04-04 03:43:59', 5, 4),
(191, 219, 10, 295, '2016-04-04 03:43:59', 10, 8),
(192, 219, 7, 284, '2016-04-04 03:45:02', 10, 9),
(193, 219, 7, 285, '2016-04-04 03:45:02', 5, 3.5),
(194, 215, 1, 246, '2016-04-04 04:40:33', 10, 9),
(195, 215, 1, 247, '2016-04-04 04:40:33', 10, 9),
(196, 215, 1, 248, '2016-04-04 04:40:33', 10, 7),
(197, 215, 1, 249, '2016-04-04 04:40:33', 10, 9);

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

--
-- Contenu de la table `self_assessments`
--

INSERT INTO `self_assessments` (`id`, `question`) VALUES
(1, 'Quelles ont été les difficultés que vous avez rencontré lors de la réalisation du projet?'),
(2, 'Qu''avez-vous appris à l''issue de ce projet?'),
(3, 'Quel est le mot qui vous a servi à mettre en lumière votre modèle?'),
(4, 'Quelle a été ta contribution au groupe? Explique précisément.'),
(5, 'Si tu devais améliorer le projet (sujet, déroulement, résultat, ...), que ferais-tu?'),
(6, 'Qui faisait partie de ton groupe?');

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
-- Contenu de la table `skills`
--

INSERT INTO `skills` (`id`, `skill_id`, `skill_group`, `skill`) VALUES
(24, 'A1', '', 'Construire le jugement éclairé en structurant la pensée critique (tant vis-à-vis de ses propres réalisations que de celles des autres) et permettre d’échanger ses raisons d’aimer en argumentant : au regard de la logique documentaire ou du reportage qui, là où elle est invoquée, implique une approche objective ; au regard du « quotient créateur » (puissance transformatrice, originalité de l’apport…) ; au regard des possibilités connotatives (ouverture ou fermeture du sens) ; au regard de l’existence formelle, visuelle et sonore (image, musique, texte, voix…) constitutive de l’œuvre (espace, composition, tension, formes, valeurs, couleurs, textures, proportions, lumières, échelle, mouvement, raccords, temps…), mais aussi dans le rapport de la forme et du contenu ; au regard de sa lisibilité et de son intelligibilité, notamment dans la relation aux conditions de production et de réception ; au regard de la norme et du hors norme des codes esthétiques.'),
(25, 'A2', '', 'Enrichir son jugement esthétique par l’éclairage de connaissances pertinentes du contexte d’émergence de l’œuvre.'),
(26, 'A3', '', 'Lire et comprendre le fonctionnement des documents audiovisuels existants (presse, annonces, clips, vidéos…), de manière à en assurer la compréhension (aspects sociologiques, sémiologiques, psychologiques…) et la critique de même qu’à pouvoir en évaluer l’impact sur le public.'),
(27, 'A4', '', 'Apprécier la richesse de ses racines et de son identité culturelle. Imposer le respect naturel et la valorisation des patrimoines.'),
(28, 'A5', '', 'Reconnaitre l’autre dans la spécificité de son langage et de ce qu’il est en lisant les productions audiovisuelles comme projection de l’imaginaire et rencontre de l’inconscient, du fonds culturel, des pulsions, des souvenirs, de la créativité, du savoir-faire et des connaissances.'),
(29, 'A6', '', 'Approcher les arts issus d’autres cultures et milieux sociaux ou religieux. Dégager des relations avec les différentes valeurs, traditions et idéologies.'),
(30, 'A7', '', 'S’ouvrir aux expériences esthétiques contemporaines de manière à les intégrer dans sa culture et ses intérêts.'),
(31, 'A8', '', 'Gérer ses choix culturels par confrontation et décodage des moyens de communication actuels (affiche, T.V., radio, cinéma, expositions, spots publicitaires, Internet) en les considérant comme sujets d’analyse.'),
(13, 'C1', '', 'Dégager les caractères stylistiques essentiels d’une écriture audiovisuelle, d’une époque ou d’un style.'),
(22, 'C10', '', 'Considérer comme indispensable ma fréquentation directe des milieux professionnels, des médiathèques, vidéothèques et cinémathèques, des galeries et musées afin de les connaitre dans leurs vraies dimensions spatiales, humaines et sensibles.'),
(23, 'C11', '', 'Connaitre les techniques de base des systèmes audiovisuels afin de pouvoir effectuer des choix pratiques judicieux.'),
(14, 'C2', '', 'Décrire l’enchainement et penser à relier l’apparition ou la résurgence des formes d’expression à leur contexte historique et social. Comprendre qu’elles s’y inscrivent, ou qu’elles peuvent être une rupture. Apprécier l’interaction dynamique entre ces différentes composantes et en quoi le créateur forge, définit, voire remet en question les valeurs et la sensibilité de la culture de telle ou telle société.'),
(15, 'C3', '', 'Comparer les œuvres du présent et du passé, dégager des correspondances et les convergences fortuites, déceler les influences, apprécier l’impact d’une œuvre à court terme et à long terme, prendre conscience des ruptures, donner du sens.'),
(16, 'C4', '', 'Connaitre les méthodes d’approche sémiologique et sémantique de base appliquées à la communication (figures et procédés stylistiques, tropes, injonction, redondance…)'),
(17, 'C5', '', 'Tout en proscrivant le jargon, user d’un vocabulaire précis, nuancé et spécifique à l’égard des techniques employées. Assumer de cette façon la richesse du langage professionnel.'),
(18, 'C6', '', 'Situer les diverses interventions des acteurs de la production audiovisuelle l’une par rapport à l’autre (chronologie, rôles et interférences).'),
(19, 'C7', '', 'Comprendre l’esprit et la logique des différents outils, supports et médias en usage dans le domaine de la communication audiovisuelle.'),
(20, 'C8', '', 'Connaitre de manière critique les principaux ressorts psychologiques agissant à l’intérieur des œuvres médiatiques ou audiovisuelles.'),
(21, 'C9', '', 'Apprendre à se documenter, à recueillir des témoignages, à recouper son information et à s’informer, en particulier sur l’actualité, l’évolution des médias et des productions audiovisuelles (usage des encyclopédies, des bibliothèques, des médiathèques, des tables des matières, des corrélats, des productions de la presse écrite, des CD-ROM, d’Internet, des dossiers de presse…)'),
(5, 'E1', '', 'Positionner ses ambitions expressives soit comme tentative de restitution objective de la réalité, soit comme écart entre cette réalité et une manière personnelle de regarder le monde.'),
(6, 'E2', '', 'Regarder activement le monde extérieur pour donner à voir et/ou à entendre ce qui échappe à la perception commune.'),
(7, 'E3', '', 'Conférer à l’activité audiovisuelle le statut de lieu de critique et d’écart par rapport à la norme, marquant ainsi la puissance créatrice de l’individu responsable s’identifiant à une création personnelle et « s’écrivant » à travers elle.'),
(8, 'E4', '', 'Dégager des clés pratiques constituant autant d’outils favorisant la créativité (cadrer, monter, éclairer, filtrer, superposer…) et développant l’imagination.'),
(9, 'E5', '', 'Transposer dans un autre registre visuel, sonore ou audiovisuel, déplacer dans un autre domaine de l’expression une première expérience formelle.'),
(10, 'E6', '', 'Pouvoir tirer profit des hasards, convertir les erreurs, utiliser l’inattendu et détourner les choses de leurs fonctions habituelles.'),
(11, 'E7', '', 'Etre capable de détourner un objet ou une image du sens convenu pour lequel il a été fait et se l’approprier en lui donnant un sens nouveau, inattendu et signifiant.'),
(12, 'E8', '', 'Exprimer son appréciation sur une œuvre audiovisuelle, justifier ses goûts et en dégager l’intérêt relatif.'),
(32, 'F1', '', 'Construire le jugement éclairé en structurant la pensée critique (tant vis-à-vis de ses propres réalisations que de celles des autres) et permettre d’échanger ses raisons d’aimer en argumentant.'),
(41, 'F10', '', 'Pouvoir utiliser les fonctions de base des logiciels les plus courant dans les domaines de la photographie et de l’audiovisuel.'),
(42, 'F11', '', 'Développer une curiosité pour l’évolution des nouvelles techniques audiovisuelles, être disponible à se les approprier, s’y adapter et à transférer les acquis potentiels dans l’acte de conception lui-même.'),
(33, 'F2', '', 'Assurer une relative aisance dans la manipulation du matériel audiovisuel de manière à préparer le travail créatif.'),
(34, 'F3', '', 'Etablir des rapports visuels (cadrage, lumières, couleurs, contrastes, raccords, profondeur de champs, mouvement du sujet et de la caméra…), temporels (rythmes, longueur et succession de séquences…), sonores (intensité, timbre, coloration, fréquences, mixage…), parlés (dialogue, timbre, respiration…) et narratifs de manière cohérente en les articulant sur une intention formelle ou expressive structurante.'),
(35, 'F4', '', 'Etablir et pouvoir nuancer ses partis pris formels ou expressifs.'),
(36, 'F5', '', 'Manipuler et expérimenter les techniques argentiques, analogiques et numériques ainsi que la diversité des émulsions et des outils (tout support de traitement de l’image) en les considérant comme des éléments de production et de recherche.'),
(37, 'F6', '', 'Mettre en corrélation, observer, écouter, penser et produire (dans le sens de produire dans un champ d’application audiovisuel), articuler ainsi la forme et le contenu, le geste et le sens, l’outil et la pensée, l’œuvre et le soi.'),
(38, 'F7', '', 'Prendre en compte des contraintes externes (cadre scolaire et/ou autre, économie des moyens, conventions, consignes, délais…) ou internes (logique des techniques) dans la structuration du travail audiovisuel.'),
(39, 'F8', '', 'Participer à des projets collectifs ou multidisciplinaires en y apportant son savoir-faire et en se pliant à la discipline qu’impose l’unité globale d’expression ou d’intention qui les justifie.'),
(40, 'F9', '', 'Organiser son travail en fonction d’un projet choisi ou imposé en y développant ses capacités d’initiative.'),
(1, 'R1', '', 'Etablir des rapports de grandeur, de position, de correspondance, de rythme, de proportion, de caractère, de couleurs… au sein de documents visuels et audio visuels.'),
(2, 'R2', '', 'Pouvoir justifier le choix d’un cadrage ou d’une composition relativement à la scène, au document, au thème ou à l’objet observé.\nInterpréter l’œuvre audiovisuelle comme un système de signes dont il convient d’objectiver les relations (forme et expression).'),
(3, 'R3', '', 'Distinguer ce qui relève de la dénotation et de la connotation dans l’approche d’une image ou d’un document pour pouvoir établir une analyse aussi objective que possible.'),
(4, 'R4', '', 'Percevoir à travers quelques œuvres majeures du cinéma, de la photographie et des productions musicales et audiovisuelles les récurrences formelles qui caractérisent le style d’un metteur en scène, d’un artiste ou d’une époque.');

-- --------------------------------------------------------

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

--
-- Contenu de la table `skills_groups`
--

INSERT INTO `skills_groups` (`id`, `name`) VALUES
(2, 'CONNAITRE'),
(3, 'FAIRE'),
(6, 'APPRECIER'),
(7, 'REGARDER / ECOUTER'),
(8, 's''EXPRIMER');

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

--
-- Contenu de la table `submitted`
--

INSERT INTO `submitted` (`id`, `user_id`, `file_path`, `file_name`, `answers`, `project_id`, `time`) VALUES
(63, 215, 'uploads/2015-2016/4AV/p2/Levitations/', 'JORDAN_Gaelle_Levitations_01.jpg', 'a:2:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:45:"Le traitement infographique m''a pris du temps";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:28:"A travailler avec précision";}}', 1, '2016-04-04 02:50:36'),
(65, 215, 'uploads/2015-2016/4AV/p2/Stopmotion/', 'JORDAN_Gaelle_Stopmotion_01.mp4', 'a:2:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:11:"La patience";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:32:"A créer un stopmotion de A à Z";}}', 4, '2016-04-04 03:11:02'),
(66, 211, 'uploads/2015-2016/4AV/p2/Levitations/', 'LOPEZ_Interessant_Levitations_01.jpg', 'a:2:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:28:"Le traitement de la lumière";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:29:"À travailler avec précision";}}', 1, '2016-04-04 03:24:47'),
(67, 211, 'uploads/2015-2016/4AV/p2/Stopmotion/', 'LOPEZ_Interessant_Stopmotion_01.mp4', 'a:2:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:1:".";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:1:".";}}', 4, '2016-04-04 03:29:02'),
(68, 219, 'uploads/2015-2016/3AV/p3/1_mot_1_eclairage/', 'KNIGHT_Celine_1_mot_1_eclairage_01.jpg', 'a:3:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:40:"Trouver les feuilles mortes au printemps";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:40:"À faire du beau avec des choses simples";}i:2;a:2:{s:2:"id";s:1:"3";s:6:"answer";s:7:"Naturel";}}', 10, '2016-04-04 03:38:44'),
(69, 219, 'uploads/2015-2016/3AV/p2/Les_flous/', 'KNIGHT_Celine_Les_flous_05.jpg', 's:0:"";', 7, '2016-04-04 03:42:06'),
(70, 219, 'uploads/2015-2016/3AV/p2/Les_flous/', 'KNIGHT_Celine_Les_flous_04.jpg', 's:0:"";', 7, '2016-04-04 03:42:06'),
(71, 219, 'uploads/2015-2016/3AV/p2/Les_flous/', 'KNIGHT_Celine_Les_flous_03.jpg', 's:0:"";', 7, '2016-04-04 03:42:06'),
(72, 219, 'uploads/2015-2016/3AV/p2/Les_flous/', 'KNIGHT_Celine_Les_flous_02.jpg', 's:0:"";', 7, '2016-04-04 03:42:06'),
(73, 219, 'uploads/2015-2016/3AV/p2/Les_flous/', 'KNIGHT_Celine_Les_flous_01.jpg', 's:0:"";', 7, '2016-04-04 03:42:06');

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
(219, '2', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Céline', 'Knight', '3AV', 'student'),
(212, '3', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Béatrice', 'Edwards', '3AV', 'student'),
(207, '4', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Andréa', 'Gonzales', '3AV', 'student'),
(214, 'dcunninghamd', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Kuí', 'Cunningham', '4AV', 'student'),
(213, 'fosterm', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Maïlys', 'Foster', '4AV', 'student'),
(211, 'hlopez7', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Intéressant', 'Lopez', '4AV', 'student'),
(206, 'Leonard', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Audrey', 'Rosario', '3AV', 'student'),
(210, 'lriley6', '', '$1$iN5SYow1$0gx7zsRqRDIV9sUgBlKZt1', 'Maëlys', 'Riley', '4AV', 'student'),
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
