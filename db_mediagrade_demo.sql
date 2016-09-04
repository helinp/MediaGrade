-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 04, 2016 at 10:21 AM
-- Server version: 5.5.47-0+deb8u1
-- PHP Version: 5.6.17-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mediagrade`
--
CREATE DATABASE IF NOT EXISTS `mediagrade` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `mediagrade`;

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

DROP TABLE IF EXISTS `assessments`;
CREATE TABLE IF NOT EXISTS `assessments` (
`id` int(11) NOT NULL,
  `skills_group` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `criterion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cursor` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `max_vote` smallint(6) NOT NULL DEFAULT '10'
) ENGINE=InnoDB AUTO_INCREMENT=311 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`id`, `skills_group`, `criterion`, `cursor`, `max_vote`) VALUES
(246, 'FAIRE', 'Qualité technique', 'soigné le cadre (sans mauvaise coupe ou d’objets indésirables)', 10),
(247, 'FAIRE', 'Qualité technique', 'soigné la mise en lumière (sans ombres\r\nindésirables, déséquilibre, qualité),', 10),
(248, 'FAIRE', 'Qualité technique', 'soigné l’image (bien exposée, nette et sans grain inadéquat),', 10),
(249, 'FAIRE', 'Qualité technique', 'soigné le montage infographique soigné et invisible (détourage, résolution, couleurs, ombres),', 10),
(252, 'FAIRE', 'Qualité technique', 'soigné l’image (bien exposée, nette et sans grain\r\ninadéquat),', 10),
(254, 'FAIRE', 'Utilisation de l’outil', 's’est approprié les techniques afin de créer des\r\nproductions créatives.', 10),
(255, 'FAIRE', 'Qualité technique', 'soigné techniquement l’ensemble des\r\nproductions attendues, au niveau du montage sonore (bonne synchronisation,\r\nniveau sonore nominal, absence de parasites).', 10),
(269, 'FAIRE', 'Qualité technique', 'exposé parfaitement les photographies qui constituent l''animation', 10),
(270, 'FAIRE', 'Qualité technique', 'soigné le montage infographique soigné et invisible (détourage, résolution, couleurs, ombres)', 10),
(280, 'FAIRE', 'Qualité technique', 'monté la capsule de façon fluide et rythmée, sans faux raccord ni image parasite.     ', 10),
(281, 'FAIRE', 'Créativité', 's''est approprié la technique afin de créer une production créative.', 5),
(282, 'FAIRE', 'Qualité technique', 'parfaitement mis au point sur le sujet', 10),
(283, 'FAIRE', 'Qualité technique', 'exposé parfaitement la photographie', 10),
(284, 'FAIRE', 'Technique', 'réalisé les différents flous, selon les techniques demandées', 10),
(285, 'FAIRE', 'Créativité', 's''est emparé de ces nouvelles techniques afin de réaliser des visuels qui témoignent de sa créativité', 5),
(286, 'FAIRE', 'utilisation du logiciel (recadrage)', 'recadré les photographies au format carré', 5),
(287, 'FAIRE', 'regard', 'réussi à trouver les 26 lettres de l''alphabet (non imprimées) dans l''environnement direct de l''école (couloirs, classes, etc.)', 10),
(288, 'FAIRE', 'cadrage, point de vue', 'utilisé le cadrage et le point de vue afin de faire ressortir les lettres trouvées dans l''environnement', 10),
(289, 'FAIRE', 'technique photographique', 'correctement exposé et mis au point les photographies', 5),
(290, 'FAIRE', 'Qualité technique', 'cadré en coupant proprement les éléments constitutifs de la composition et en évitant\r\nd’y inclure des éléments parasites.', 15),
(291, 'APPRECIER', 'intention de mise en lumière', 'travaillé la lumière de manière à ce qu''elle figure le mot choisi', 20),
(292, 'FAIRE', 'Qualité technique', 'correctement mis au point la photographie', 5),
(293, 'FAIRE', 'Qualité technique', 'correctement exposé la photographie', 5),
(294, 'FAIRE', 'Qualité technique', 'cadré de manière équilibrée et en coupant les élèments indésirables', 5),
(295, 'APPRECIER', 'intention de cadrage', 'travaillé lae cadre de manière à ce qu''elle figure le mot choisi', 10),
(296, 'FAIRE', 'Sujet', 'respecté le thème imposé (les attentats du 22 mars 2016).', 10),
(297, 'FAIRE', 'Rapidité de travail', 'remis à temps la production demandée.', 5),
(298, 'FAIRE', 'Contribution', 'Apporté son savoir-faire de manière spontanée et opportune au groupe', 10),
(301, 'APPRECIER', 'cadrage, point de vue', 'soigné techniquement l’ensemble des\r\nproductions attendues, au niveau du montage sonore (bonne synchronisation,\r\nniveau sonore nominal, absence de parasites).', 10),
(303, 'APPRECIER', 'Qualité technique', 'soigné le cadre (sans mauvaise coupe ou d’objets indésirables)', 10),
(305, 'FAIRE', 'Qualité technique', '- de la synchronisation du son et de l''image', 10),
(306, 'FAIRE', 'Qualité technique', '- niveau sonore nominal', 5),
(307, 'FAIRE', 'Qualité technique', '- absence de parasites (distorsion ou saturation)', 5),
(308, 'EXPRIMER (s'')', 'Fond/Forme', 'l''ensemble des bruitages et de la vidéo sont cohérents entre eux', 10);

-- --------------------------------------------------------

--
-- Table structure for table `auto_check`
--

DROP TABLE IF EXISTS `auto_check`;
CREATE TABLE IF NOT EXISTS `auto_check` (
`id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `format` text COLLATE utf8_unicode_ci NOT NULL,
  `codec` text COLLATE utf8_unicode_ci NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `lenght` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `project_id`, `comment`) VALUES
(59, 219, 10, 'Attention aux ombres, mal considérées elle peuvent gacher l''équilibre de ton image.'),
(60, 219, 7, 'N''hésite pas à aller plus loin dans ta recherche'),
(61, 215, 4, 'Très chouette résultat, cela valait la peine d''être patiente, bravo!'),
(62, 215, 1, 'Dommage pour la surexposition, tu dois prendre ta mesure sur les hautes lumières!');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
`id` int(11) NOT NULL,
  `type` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `type`, `content`) VALUES
(2, 'welcome_message', '<h2>Bienvenue, %user_name% !</h2><p>Tu es sur la nouvelle plateforme de remise des projets!</p><p>Je t''invite &agrave; d&eacute;couvrir la nouvelle interface et, surtout, &agrave; recommander tes meilleurs films sur la page "Movie Advisor".</p><p>En route pour r&eacute;ussir les examens de juin!</p><p>P. H&eacute;lin&nbsp;</p>'),
(3, 'terms', 'P1,P2,P3,DEC,JUN');

-- --------------------------------------------------------

--
-- Table structure for table `files_format`
--

DROP TABLE IF EXISTS `files_format`;
CREATE TABLE IF NOT EXISTS `files_format` (
  `mime` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `extension` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `files_format`
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
-- Table structure for table `lost_password`
--

DROP TABLE IF EXISTS `lost_password`;
CREATE TABLE IF NOT EXISTS `lost_password` (
  `id` int(11) NOT NULL,
  `hash` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movies_advisor`
--

DROP TABLE IF EXISTS `movies_advisor`;
CREATE TABLE IF NOT EXISTS `movies_advisor` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `maf_id` int(11) NOT NULL,
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `original_title` text COLLATE utf8_unicode_ci NOT NULL,
  `overview` text COLLATE utf8_unicode_ci NOT NULL,
  `year` int(4) NOT NULL,
  `vote` tinyint(11) NOT NULL,
  `poster_path` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `movies_advisor`
--

INSERT INTO `movies_advisor` (`id`, `user_id`, `maf_id`, `title`, `original_title`, `overview`, `year`, `vote`, `poster_path`) VALUES
(8, 1, 8392, 'Mon voisin Totoro', 'となりのトトロ', 'Un professeur d''université, M. Kusakabe, et ses deux filles, Satsuki, onze ans, et Mei, quatre ans, s''installent dans leur nouvelle maison à la campagne. Celle-ci est proche de l''hôpital où la mère des deux filles est hospitalisée. Explorant les alentours, Mei rencontre Totoro, sorte de créature gigantesque et esprit de la forêt...', 1988, 4, '/assets/movies/poster_1988_Mon_voisin_Totoro_8392.jpg'),
(9, 1, 16804, 'Departures', 'おくりびと', 'Dans une province rurale du nord du Japon, à Yamagata, où Daigo Kobayashi retourne avec son épouse, après l''éclatement de l''orchestre dans lequel il jouait depuis des années à Tokyo. Daigo répond à une annonce pour un emploi &quot;d''aide aux départs&quot;, imaginant avoir affaire à une agence de voyages. L''ancien violoncelliste s''aperçoit qu''il s''agit en réalité d''une entreprise de pompes funèbres, mais accepte l''emploi par nécessité financière. Plongé dans ce monde peu connu, il va découvrir les rites funéraires, tout en cachant à sa femme sa nouvelle activité, en grande partie taboue au Japon.', 2008, 3, '/assets/movies/poster_2008_Departures_16804.jpg'),
(11, 1, 2011, 'Persepolis', 'Persepolis', 'Téhéran 1978 : Marjane, huit ans, songe à l''avenir et se rêve en prophète sauvant le monde. Choyée par des parents modernes et cultivés, particulièrement liée à sa grand-mère, elle suit avec exaltation les évènements qui vont mener à la révolution et provoquer la chute du régime du Chah.Avec l''instauration de la République islamique débute le temps des &quot;commissaires de la révolution&quot; qui contrôlent tenues et comportements. Marjane qui doit porter le voile, se rêve désormais en révolutionnaire.Bientôt, la guerre contre l''Irak entraîne bombardements, privations, et disparitions de proches. La répression intérieure devient chaque jour plus sévère.Dans un contexte de plus en plus pénible, sa langue bien pendue et ses positions rebelles deviennent problématiques. Ses parents décident alors de l''envoyer en Autriche pour la protéger.A Vienne, Marjane vit à quatorze ans sa deuxième révolution.', 2007, 4, '/assets/movies/poster_2007_Persepolis_2011.jpg'),
(12, 1, 129670, 'Nebraska', 'Nebraska', 'Un vieil homme, persuadé qu’il a gagné le gros lot à un improbable tirage au sort par correspondance, cherche à rejoindre le Nebraska pour y recevoir son gain... Sa famille, inquiète de ce qu’elle perçoit comme le début d’une démence sénile, envisage de le placer en maison de retraite, mais un de ses deux fils se décide finalement à emmener son père en voiture chercher ce chèque auquel personne ne croit. Pendant le voyage, le vieillard se blesse et l’équipée fait une étape forcée dans une petite ville en déclin du Nebraska. C’est là que le père est né. Épaulé par son fils, le vieil homme retrace les souvenirs de son enfance.', 2013, 3, '/assets/movies/poster_2013_Nebraska_129670.jpg'),
(13, 1, 152601, 'Her', 'Her', 'Los Angeles, dans un futur proche. Theodore Twombly, un homme sensible au caractère complexe, est inconsolable suite à une rupture difficile. Il fait alors l''acquisition d''un programme informatique ultramoderne, capable de s''adapter à la personnalité de chaque utilisateur. En lançant le système, il fait la connaissance de ''Samantha'', une voix féminine intelligente, intuitive et étonnamment drôle. Les besoins et les désirs de Samantha grandissent et évoluent, tout comme ceux de Theodore, et peu à peu, ils tombent amoureux…', 2013, 3, '/assets/movies/poster_2013_Her_152601.jpg'),
(14, 1, 6615, 'Lars et l''amour en boîte', 'Lars and the Real Girl', 'Timide et introverti, Lars vit seul dans le garage aménagé de son frère Gus et de sa belle-soeur Karin, dans un petit village du Middlewest.Quand il leur annonce qu''il a enfin rencontré une jeune fille sur internet et qu''elle va bientôt lui rendre visite, Gus et Karin sont soulagés et très impatients de faire sa connaissance.Leur surprise est grande lorsque Lars leur présente très officiellement l''étrange Bianca.Sur les conseils de leur médecin, Karin et Gus décident de ne pas heurter Lars et d''accepter son amie. Bianca accompagne Lars à table, à l''église ou au supermarché attirant l''attention et la stupéfaction générale du village.', 2007, 2, '/assets/movies/poster_2007_Lars_et_l_amour_en_boite_6615.jpg'),
(15, 1, 5915, 'Into the Wild', 'Into the Wild', 'Tout juste diplômé de l''université, Christopher McCandless, 22 ans, est promis à un brillant avenir. Pourtant, tournant le dos à l''existence confortable et sans surprise qui l''attend, le jeune homme décide de prendre la route en laissant tout derrière lui.Des champs de blé du Dakota aux flots tumultueux du Colorado, en passant par les communautés hippies de Californie, Christopher va rencontrer des personnages hauts en couleur. Chacun, à sa manière, va façonner sa vision de la vie et des autres.Au bout de son voyage, Christopher atteindra son but ultime en s''aventurant seul dans les étendues sauvages de l''Alaska pour vivre en totale communion avec la nature.', 2007, 4, '/assets/movies/poster_2007_Into_the_Wild_5915.jpg'),
(16, 1, 927, 'Gremlins', 'Gremlins', 'Rand Peltzer offre à son fils Billy un étrange animal : un mogwai. Son ancien propriétaire l''a bien mis en garde : il ne faut pas l''exposer à la lumiere, lui éviter tout contact avec l''eau, et surtout, surtout ne jamais le nourrir apres minuit... Sinon...', 1984, 2, '/assets/movies/poster_1984_Gremlins_927.jpg'),
(17, 1, 12477, 'Le Tombeau des lucioles', 'Hotaru no haka', 'Japon, été 1945. Après le bombardement de Kobé, Seita, un adolescent de quatorze ans et sa petite soeur de quatre ans, Setsuko, orphelins, vont s''installer chez leur tante à quelques dizaines de kilomètres de chez eux. Celle-ci leur fait comprendre qu''ils sont une gêne pour la famille et doivent mériter leur riz quotidien. Seita décide de partir avec sa petite soeur. Ils se réfugient dans un bunker désaffecté en pleine campagne et vivent des jours heureux illuminés par la présence de milliers de lucioles. Mais bientôt la nourriture commence cruellement à manquer.', 1988, 4, '/assets/movies/poster_1988_Le_Tombeau_des_lucioles_12477.jpg'),
(18, 1, 31011, 'Mr. Nobody', 'Mr. Nobody', 'En 2092, Nemo Nobody, âgé de 118 ans, est le dernier mortel vivant dans un monde d''immortels. Il est interrogé sur son passé et se retrouve sous les soins du docteur Feldheim, qui veut l''aider à mettre de l''ordre dans ses souvenirs brouillés. Profondément marqué par le divorce de ses parents et par ses échecs sentimentaux, le vieillard a des trous de mémoire et son témoignage est constitué d’épisodes contradictoires de son enfance, de ses amours et de sa vie conjugale.  Nemo Nobody se remémore à travers différents flashbacks ses différentes vies dans des espaces-temps différents. Il imagine alors douze vies parallèles dont chacune est caractérisée par une épouse différente. Les variations de cette arborescence s''en tiennent parfois à très peu de choses, soit une illustration de l''effet papillon. Nemo livre par intermittence au spectateur quelques réflexions sur l''espace-temps, notamment le Big Crunch.', 2009, 4, '/assets/movies/poster_2009_Mr_Nobody_31011.jpg'),
(19, 1, 76203, '12 Years a Slave', '12 Years a Slave', 'Les États-Unis, quelques années avant la guerre de Sécession.  Solomon Northup, jeune homme noir originaire de l’État de New York, est enlevé et vendu comme esclave.  Face à la cruauté d’un propriétaire de plantation de coton, Solomon se bat pour rester en vie et garder sa dignité.  Douze ans plus tard, il va croiser un abolitionniste canadien et cette rencontre va changer sa vie…', 2013, 4, '/assets/movies/poster_2013_12_Years_a_Slave_76203.jpg'),
(20, 1, 77, 'Memento', 'Memento', 'Leonard Shelby ne porte que des costumes de grands couturiers et ne se déplace qu''au volant de sa Jaguar. En revanche, il habite dans des motels miteux et règle ses notes avec d''épaisses liasses de billets.Leonard n''a qu''une idée en tête : traquer l''homme qui a violé et assassiné sa femme afin de se venger. Sa recherche du meurtrier est rendue plus difficile par le fait qu''il souffre d''une forme rare et incurable d''amnésie. Bien qu''il puisse se souvenir de détails de son passé, il est incapable de savoir ce qu''il a fait dans le quart d''heure précédent, où il se trouve, où il va et pourquoi.Pour ne jamais perdre son objectif de vue, il a structuré sa vie à l''aide de fiches, de notes, de photos, de tatouages sur le corps. C''est ce qui l''aide à garder contact avec sa mission, à retenir les informations et à garder une trace, une notion de l''espace et du temps.', 2000, 3, '/assets/movies/poster_2000_Memento_77.jpg'),
(21, 1, 110416, 'Le chant de la mer', 'Song of the Sea', 'Ben et Maïna vivent avec leur père tout en haut d''un phare sur une petite île. Pour les protéger des dangers de la mer, leur grand-mère les emmène vivre à la ville. Ben découvre alors que sa petite soeur est une selkie, une fée de la mer dont le chant peut délivrer les êtres magiques du sort que leur a jeté la Sorcière aux hiboux. Au cours d''un fantastique voyage, Ben et Maïna vont devoir affronter peurs et dangers,  et combattre la sorcière pour aider les êtres magiques à retrouver leur pouvoir.', 2014, 4, '/assets/movies/poster_2014_Le_chant_de_la_mer_110416.jpg'),
(22, 1, 7735, 'La Vague', 'Die Welle', 'En Allemagne, aujourd''hui. Dans le cadre d''un atelier, un professeur de lycée propose à ses élèves une expérience visant à leur expliquer le fonctionnement d''un régime totalitaire. Commence alors un jeu de rôle grandeur nature, dont les conséquences vont s''avérer tragiques.', 2008, 2, '/assets/movies/poster_2008_La_Vague_7735.jpg'),
(23, 1, 696, 'Manhattan', 'Manhattan', 'Isaac Davis est un auteur de sketches comiques new-yorkais de 42 ans que son épouse Jil vient de quitter. Celle-ci vit maintenant avec une autre femme, Connie, et écrit un livre sur son ancienne vie conjugale. Isaac, quant à lui, entretient avec une collégienne de 17 ans, Tracy, une liaison dont il lui rappelle le caractère éphémère. Il l''abandonne bientôt pour se mettre en ménage avec Mary Wilke, la maîtresse de Yale Pollack, son meilleur ami.', 1979, 3, '/assets/movies/poster_1979_Manhattan_696.jpg'),
(24, 1, 348, 'Alien : Le 8ème Passager', 'Alien', '2122. Le Nostromo, vaisseau de commerce, fait route vers la Terre avec à son bord un équipage de sept personnes en hibernation et une cargaison de minerais. Il interrompt soudain sa course suite à la réception d''un mystérieux message provenant d''une planète inexplorée. Réveillé par l''ordinateur de bord, l''équipage se rend sur place et découvre les restes d''un gigantesque vaisseau extraterrestre dont le seul passager semble être mort dans d''étranges circonstances...', 1979, 4, '/assets/movies/poster_1979_Alien_Le_8eme_Passager_348.jpg'),
(25, 1, 27205, 'Inception', 'Inception', 'Dom Cobb est un voleur expérimenté, le meilleur dans l''art dangereux de l''extraction, voler les secrets les plus intimes enfouis au plus profond du subconscient durant une phase de rêve, lorsque l''esprit est le plus vulnérable. Les capacités de Cobb ont fait des envieux dans le monde tourmenté de l''espionnage industriel alors qu''il devient fugitif en perdant tout ce qu''il a un jour aimé. Une chance de se racheter lui est alors offerte. Une ultime mission grâce à laquelle il pourrait retrouver sa vie passée mais uniquement s''il parvient à accomplir l''impossible inception.', 2010, 4, '/assets/movies/poster_2010_Inception_27205.jpg'),
(26, 1, 637, 'La vie est belle', 'La vita è bella', 'En 1938, Guido, jeune homme plein de gaieté, rêve d''ouvrir une librairie, malgré les tracasseries de l''administration fasciste. Il tombe amoureux de Dora, institutrice étouffée par le conformisme familial et l''enlève le jour de ses fiançailles avec un bureaucrate du régime. Cinq ans plus tard, Guido et Dora ont un fils: Giosue. Mais les lois raciales sont entrées en vigueur et Guido est juif. Il est alors déporté avec son fils. Par amour pour eux, Dora monte de son plein gré dans le train qui les emmène aux camps de la mort où Guido va tout faire pour éviter l''horreur à son fils...', 1997, 4, '/assets/movies/poster_1997_La_vie_est_belle_637.jpg'),
(27, 1, 83542, 'Cloud Atlas', 'Cloud Atlas', 'À travers une histoire qui se déroule sur cinq siècles dans plusieurs espaces temps, des êtres se croisent et se retrouvent d’une vie à l’autre, naissant et renaissant successivement… Tandis que leurs décisions ont des conséquences sur leur parcours, dans le passé, le présent et l’avenir lointain, un tueur devient un héros et un seul acte de générosité suffit à entraîner des répercussions pendant plusieurs siècles et à provoquer une révolution. Tout, absolument tout, est lié.', 2012, 3, '/assets/movies/poster_2012_Cloud_Atlas_83542.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

DROP TABLE IF EXISTS `terms`;
CREATE TABLE IF NOT EXISTS `terms` (
`id` int(11) NOT NULL,
  `name` varchar(65) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `name`) VALUES
(4, 'DEC'),
(5, 'JUN'),
(1, 'P1'),
(2, 'P2'),
(3, 'P3');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
`id` int(11) NOT NULL,
  `term` varchar(4) NOT NULL,
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
  `is_activated` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `term`, `instructions_pdf`, `instructions_txt`, `deadline`, `project_name`, `class`, `self_assessment_ids`, `assessment_type`, `skill_ids`, `extension`, `number_of_files`, `is_activated`) VALUES
(1, 'P2', 'uploads/2015-2016/4AV/instructions/4AV_2_L_vitations.pdf', '', '2016-05-14', 'Lévitations', '4AV', '1,2', 'Formative', 'F1,F10,F11,F2,F3,F4,F5,F6,F7,F8,F9', 'jpg', 1, 1),
(4, 'P2', 'uploads/2015-2016/4AV/instructions/4AV_2_Stopmotion.pdf', '', '2016-05-15', 'Stopmotion', '4AV', '1,2', 'Formative', 'F1,F10,F11,F2,F3,F4,F5,F6,F7,F8,F9', 'mp4', 1, 1),
(7, 'P2', '', '', '2016-05-06', 'Les flous', '3AV', '', 'Formative', 'E1,E4,E6,F3,F5,F7,F8,F9', 'jpg', 5, 1),
(10, 'P3', '', '', '2016-04-28', '1 mot = 1 éclairage', '3AV', '1,2,3', 'Formative', 'F2,F3,F4,F5,F7,F9,R2', 'jpg', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `projects_assessments`
--

DROP TABLE IF EXISTS `projects_assessments`;
CREATE TABLE IF NOT EXISTS `projects_assessments` (
`id` int(11) NOT NULL,
  `project_id` smallint(6) NOT NULL,
  `assessment_id` smallint(6) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projects_assessments`
--

INSERT INTO `projects_assessments` (`id`, `project_id`, `assessment_id`) VALUES
(1, 1, 246),
(2, 1, 247),
(3, 1, 248),
(4, 1, 249),
(5, 2, 246),
(6, 2, 249),
(7, 2, 252),
(8, 2, 254),
(9, 3, 255),
(10, 4, 269),
(11, 4, 280),
(12, 5, 270),
(13, 5, 281),
(14, 6, 270),
(15, 6, 282),
(16, 6, 283),
(17, 7, 284),
(18, 7, 285),
(19, 8, 286),
(20, 8, 287),
(21, 8, 288),
(22, 8, 289),
(23, 9, 290),
(24, 10, 291),
(25, 10, 292),
(26, 10, 293),
(27, 10, 294),
(28, 10, 295),
(29, 11, 296),
(30, 11, 297),
(31, 11, 298),
(32, 13, 301),
(33, 13, 302),
(34, 14, 303),
(35, 14, 304),
(36, 14, 303),
(37, 14, 304),
(50, 15, 305),
(51, 15, 306),
(52, 15, 305),
(53, 15, 307),
(54, 15, 308),
(55, 16, 309),
(56, 16, 310);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
`id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(9) DEFAULT NULL,
  `project_id` mediumint(9) DEFAULT NULL,
  `assessment_id` mediumint(9) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `max_vote` smallint(6) DEFAULT '10',
  `user_vote` float DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `user_id`, `project_id`, `assessment_id`, `date`, `max_vote`, `user_vote`) VALUES
(183, 215, 4, 269, '2016-04-04 05:35:04', 10, 9),
(184, 215, 4, 280, '2016-04-04 05:35:04', 10, 9),
(185, 211, 4, 269, '2016-04-04 05:35:19', 10, 7),
(186, 211, 4, 280, '2016-04-04 05:35:19', 10, 8),
(187, 219, 10, 291, '2016-04-04 05:43:59', 20, 16),
(188, 219, 10, 292, '2016-04-04 05:43:59', 5, 5),
(189, 219, 10, 293, '2016-04-04 05:43:59', 5, 5),
(190, 219, 10, 294, '2016-04-04 05:43:59', 5, 4),
(191, 219, 10, 295, '2016-04-04 05:43:59', 10, 8),
(192, 219, 7, 284, '2016-04-04 05:45:02', 10, 9),
(193, 219, 7, 285, '2016-04-04 05:45:02', 5, 3.5),
(194, 215, 1, 246, '2016-04-04 06:40:33', 10, 9),
(195, 215, 1, 247, '2016-04-04 06:40:33', 10, 9),
(196, 215, 1, 248, '2016-04-04 06:40:33', 10, 7),
(197, 215, 1, 249, '2016-04-04 06:40:33', 10, 9);

-- --------------------------------------------------------

--
-- Table structure for table `self_assessments`
--

DROP TABLE IF EXISTS `self_assessments`;
CREATE TABLE IF NOT EXISTS `self_assessments` (
`id` int(11) NOT NULL,
  `question` varchar(8000) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `self_assessments`
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
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `skills` (
`id` int(11) NOT NULL,
  `skill_id` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `skill_group` text COLLATE utf8_unicode_ci NOT NULL,
  `skill` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `skills`
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
-- Table structure for table `skills_groups`
--

DROP TABLE IF EXISTS `skills_groups`;
CREATE TABLE IF NOT EXISTS `skills_groups` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `skills_groups`
--

INSERT INTO `skills_groups` (`id`, `name`) VALUES
(2, 'CONNAITRE'),
(3, 'FAIRE'),
(6, 'APPRECIER'),
(7, 'REGARDER / ECOUTER'),
(8, 'EXPRIMER (s'')');

-- --------------------------------------------------------

--
-- Table structure for table `submitted`
--

DROP TABLE IF EXISTS `submitted`;
CREATE TABLE IF NOT EXISTS `submitted` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `file_name` text COLLATE utf8_unicode_ci NOT NULL,
  `answers` varchar(8000) COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `submitted`
--

INSERT INTO `submitted` (`id`, `user_id`, `file_path`, `file_name`, `answers`, `project_id`, `time`) VALUES
(63, 215, 'uploads/2015-2016/4AV/p2/Levitations/', 'JORDAN_Gaelle_Levitations_01.jpg', 'a:2:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:45:"Le traitement infographique m''a pris du temps";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:28:"A travailler avec précision";}}', 1, '2016-04-04 04:50:36'),
(65, 215, 'uploads/2015-2016/4AV/p2/Stopmotion/', 'JORDAN_Gaelle_Stopmotion_01.mp4', 'a:2:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:11:"La patience";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:32:"A créer un stopmotion de A à Z";}}', 4, '2016-04-04 05:11:02'),
(66, 211, 'uploads/2015-2016/4AV/p2/Levitations/', 'LOPEZ_Interessant_Levitations_01.jpg', 'a:2:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:28:"Le traitement de la lumière";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:29:"À travailler avec précision";}}', 1, '2016-04-04 05:24:47'),
(67, 211, 'uploads/2015-2016/4AV/p2/Stopmotion/', 'LOPEZ_Interessant_Stopmotion_01.mp4', 'a:2:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:1:".";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:1:".";}}', 4, '2016-04-04 05:29:02'),
(68, 219, 'uploads/2015-2016/3AV/p3/1_mot_1_eclairage/', 'KNIGHT_Celine_1_mot_1_eclairage_01.jpg', 'a:3:{i:0;a:2:{s:2:"id";s:1:"1";s:6:"answer";s:40:"Trouver les feuilles mortes au printemps";}i:1;a:2:{s:2:"id";s:1:"2";s:6:"answer";s:40:"À faire du beau avec des choses simples";}i:2;a:2:{s:2:"id";s:1:"3";s:6:"answer";s:7:"Naturel";}}', 10, '2016-04-04 05:38:44'),
(69, 219, 'uploads/2015-2016/3AV/p2/Les_flous/', 'KNIGHT_Celine_Les_flous_05.jpg', 's:0:"";', 7, '2016-04-04 05:42:06'),
(70, 219, 'uploads/2015-2016/3AV/p2/Les_flous/', 'KNIGHT_Celine_Les_flous_04.jpg', 's:0:"";', 7, '2016-04-04 05:42:06'),
(71, 219, 'uploads/2015-2016/3AV/p2/Les_flous/', 'KNIGHT_Celine_Les_flous_03.jpg', 's:0:"";', 7, '2016-04-04 05:42:06'),
(72, 219, 'uploads/2015-2016/3AV/p2/Les_flous/', 'KNIGHT_Celine_Les_flous_02.jpg', 's:0:"";', 7, '2016-04-04 05:42:06'),
(73, 219, 'uploads/2015-2016/3AV/p2/Les_flous/', 'KNIGHT_Celine_Les_flous_01.jpg', 's:0:"";', 7, '2016-04-04 05:42:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `role` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
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
-- Table structure for table `users_config`
--

DROP TABLE IF EXISTS `users_config`;
CREATE TABLE IF NOT EXISTS `users_config` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `type` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users_config`
--

INSERT INTO `users_config` (`id`, `user_id`, `data`, `type`) VALUES
(1, 233, '/assets/uploads/users/avatars/DOE_John_avatar.jpg', 'avatar'),
(11, 233, 'a:2:{s:23:"assessment_confirmation";s:4:"true";s:19:"submit_confirmation";b:0;}', 'email'),
(14, 1, '/assets/uploads/users/avatars/HELIN_Pierre_avatar.jpg', 'avatar');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indexes for table `auto_check`
--
ALTER TABLE `auto_check`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lost_password`
--
ALTER TABLE `lost_password`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `movies_advisor`
--
ALTER TABLE `movies_advisor`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD KEY `name` (`name`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indexes for table `projects_assessments`
--
ALTER TABLE `projects_assessments`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `self_assessments`
--
ALTER TABLE `self_assessments`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
 ADD PRIMARY KEY (`skill_id`), ADD UNIQUE KEY `skill_id` (`skill_id`), ADD KEY `id` (`id`);

--
-- Indexes for table `skills_groups`
--
ALTER TABLE `skills_groups`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indexes for table `submitted`
--
ALTER TABLE `submitted`
 ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`username`), ADD KEY `id` (`id`);

--
-- Indexes for table `users_config`
--
ALTER TABLE `users_config`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=311;
--
-- AUTO_INCREMENT for table `auto_check`
--
ALTER TABLE `auto_check`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `movies_advisor`
--
ALTER TABLE `movies_advisor`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `projects_assessments`
--
ALTER TABLE `projects_assessments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=198;
--
-- AUTO_INCREMENT for table `self_assessments`
--
ALTER TABLE `self_assessments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `skills_groups`
--
ALTER TABLE `skills_groups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `submitted`
--
ALTER TABLE `submitted`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=74;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=234;
--
-- AUTO_INCREMENT for table `users_config`
--
ALTER TABLE `users_config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
