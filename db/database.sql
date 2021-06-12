-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: kapps-appstore-db:3306
-- Generation Time: 12. Jun, 2021 20:26 PM
-- Tjener-versjon: 8.0.20
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kapps`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `apps`
--

CREATE TABLE IF NOT EXISTS `apps` (
  `id` int NOT NULL AUTO_INCREMENT,
  `time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_edited` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int NOT NULL COMMENT 'User ID',
  `updated_by` int DEFAULT NULL,
  `company_id` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `short_description` varchar(256) DEFAULT NULL,
  `installation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `primary_image` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type_id` int NOT NULL,
  `license_id` int DEFAULT NULL,
  `delivery_id` int DEFAULT NULL,
  `tags` text,
  `link_source_code` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `link_install` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `install_type` enum('click','download','contact') NOT NULL,
  `status` enum('draft','published','deleted') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `apps_delivery_methods`
--

CREATE TABLE IF NOT EXISTS `apps_delivery_methods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dataark for tabell `apps_delivery_methods`
--

INSERT INTO `apps_delivery_methods` (`id`, `title`) VALUES
(1, 'SaaS'),
(2, 'On Premise');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `app_has_delivery`
--

CREATE TABLE IF NOT EXISTS `app_has_delivery` (
  `app_id` int NOT NULL,
  `delivery_id` int NOT NULL,
  `link` varchar(256) NOT NULL,
  PRIMARY KEY (`app_id`,`delivery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `app_types`
--

CREATE TABLE IF NOT EXISTS `app_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `fa_icon` varchar(128) DEFAULT NULL,
  `default_image` varchar(256) NOT NULL,
  `template_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dataark for tabell `app_types`
--

INSERT INTO `app_types` (`id`, `title`, `fa_icon`, `default_image`, `template_desc`) VALUES
(1, 'Applikasjon', 'fal fa-box-full', '', ''),
(2, 'Integrasjon', 'fal fa-cogs', '', ''),
(3, 'RPA', 'fal fa-user-robot', '', '<p>Ingress</p><table style=\"border-collapse: collapse; width: 100%;\" border=\"1\"> <tbody> <tr> <td style=\"width:30%;\"><strong>Prosessnavn</strong></td> <td style=\"width:70%;\"></td> </tr> <tr> <td><strong>Involverte system/l&oslash;sninger</strong></td> <td></td> </tr> <tr> <td><strong>RPA Software</strong></td> <td></td> </tr> <tr> <td><strong>Tjenesteomr&aring;de</strong></td> <td></td> </tr> <tr> <td><strong>Datakilder benyttet</strong></td> <td></td> </tr> <tr> <td><strong>Innvolverte</strong></td> <td></td> </tr> <tr> <td><strong>Kontaktinformasjon</strong></td> <td></td> </tr> </tbody> </table> <p>Ytterligere beskrivelse</p>'),
(4, 'Dokument', 'fal fa-books', '', '');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `id` int NOT NULL AUTO_INCREMENT,
  `public_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `domain` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Login-domain',
  `title` varchar(256) NOT NULL,
  `county` varchar(256) DEFAULT NULL,
  `type_id` int DEFAULT NULL COMMENT 'Municipality ID, County ID...',
  `org_numb` int DEFAULT NULL,
  `website` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type` enum('Departement','Etat','Direktorat','Bedrift','Kommune','Fylke','Interkommunalt samarbeid','IKS','Organisasjonsledd','Skole / Universitet','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `logo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `access` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `public_id` (`public_id`)
) ENGINE=InnoDB AUTO_INCREMENT=671 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dataark for tabell `company`
--

INSERT INTO `company` (`id`, `public_id`, `domain`, `title`, `county`, `type_id`, `org_numb`, `website`, `type`, `logo`, `access`) VALUES
(1, 'o4yEbqLm', 'fosenikt.no', 'Fosen IKT', '', 0, 920031587, 'https://fosenikt.no', 'Interkommunalt samarbeid', '20d14c160376e22b92742fe91988721b33e9060758092cf9d1565686701c7e13.svg', 1),
(2, '15EXDFLQ', '22julisenteret.no', '22. juli-senteret', '', 0, 922727457, 'https://22julisenteret.no/', 'Bedrift', '', 0),
(3, 'm9BRmRoA', '', 'Aker Kværner Holding AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(4, 'w1sbIXyG', 'ambita.com', 'Ambita AS', '', 0, 945811714, 'https://www.ambita.com/', 'Bedrift', '367b5a5ef246b3c740e8b151a49fcb27bc56d72b9ca3b21c2be6d321e0a5bbda.svg', 0),
(5, 'JN11k3wP', 'andoyaspace.no', 'Andøya Space Center AS', NULL, NULL, 0, 'https://www.andoyaspace.no/', 'Bedrift', '17650a5ba6d06974297d64c9f02c64e231170e48d2c8c2478e8f5cc5600ab663.svg', 0),
(6, 'CwTFDXXr', '', 'Arbeids- og sosialdepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(7, '9SpPhXFy', 'arbeidsretten.no', 'Arbeidsretten', '', 0, 971525681, 'https://www.arbeidsretten.no/', 'Organisasjonsledd', 'ad578d1758caa82f4bbfdb0c06095719909dfe39870a9333bc1d65ea4c437645.svg', 0),
(8, 'UjxaFr6x', 'arbeidstilsynet.no', 'Arbeidstilsynet', '', 0, 974761211, 'https://www.arbeidstilsynet.no/', 'Direktorat', 'cffbc8e8a55e9598752e992edb7d09936a876ac9533333f438f85039358bdbb1.svg', 0),
(9, 'vpXksi0', '', 'Argentum Fondsinvesteringer AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(10, 'nIrTrqa9', 'aho.no', 'Arkitektur og designhøgskolen i Oslo (AHO)', '', 0, 971526378, 'https://aho.no/', 'Skole / Universitet', '69713a15e84d8c10f21725e6d5864e9dc0b42db7f66ff1586cb93183eb3fbcb1.svg', 0),
(11, 'b7xj5B9O', 'arkivverket.no', 'Arkivverket', '', 0, 961181399, 'https://www.arkivverket.no/', 'Organisasjonsledd', 'e6b75f189d8691658b6feaed361db4ab5b5211a64c6e5ac68ae400e0d353e683.svg', 0),
(12, 'Yjbtqmdc', 'artsdatabanken.no', 'Artsdatabanken', NULL, NULL, 919666102, 'https://www.artsdatabanken.no/', '', 'aff097922fde414ee8d512cc226f491b7751dcd242d9aab6278e9ac1147c7b39.svg', 0),
(13, 'meBTFOoZ', 'avinor.no', 'Avinor AS', NULL, NULL, 985198292, 'https://avinor.no/', 'Bedrift', '', 0),
(14, 'wxyWKIb8', 'banenor.no', 'Bane NOR SF', NULL, NULL, 917082308, 'https://www.banenor.no/', 'Bedrift', '', 0),
(15, 'k9pXMSKg', 'baneservice.no', 'Baneservice AS', NULL, NULL, 986392912, 'https://baneservice.no/', 'Bedrift', '', 0),
(16, 'rrM5tzON', '', 'Barne- og familiedepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(17, 'gny4w9Pe', 'bufdir.no', 'Barne-, ungdoms- og familiedirektoratet', NULL, NULL, 986128433, 'https://www.bufdir.no/', 'Direktorat', '', 0),
(18, 'W0EKhRrI', 'barneombudet.no', 'Barneombudet', '', 0, 971527765, 'https://www.barneombudet.no/', '', '736f6c6c7fa16ffbba32f77a22fbf32884096c281686d3b1bdc13ee1d9f793aa.svg', 0),
(19, 'C0irijv6', 'bioteknologiradet.no', 'Bioteknologirådet', NULL, NULL, 0, 'https://www.bioteknologiradet.no/', '', '', 0),
(20, 'ontwAwNY', '', 'Bjørnøen AS', '', 0, 927827948, '', 'Bedrift', '', 0),
(21, 'BQR2aFLZ', 'brreg.no', 'Brønnøysundregistrene', NULL, NULL, 974760673, 'https://www.brreg.no/', 'Direktorat', '2fc6f1c82e3f71477127d616e5a5a611970e3e45c5cbb4834720625a1d4e034e.svg', 0),
(22, 'a2pqDWw3', 'bufdir.no', 'Bufetat', '', 0, 992288094, 'https://www.bufdir.no/', '', '7790b52277b05cc925c52a6b09f87bdde44e63c79e52b91467ad679ed0936e24.svg', 0),
(23, 'MvA3NRXS', 'ncb.no', 'Carte Blanche AS', '', 0, 957214479, 'https://carteblanche.no/', 'Bedrift', 'd83367d4972f18b594c5e0017d49dd393f173ac98908514998a31722f2741013.svg', 0),
(24, 'OsPQumFw', 'datatilsynet.no', 'Datatilsynet', NULL, NULL, 974761467, 'http://www.datatilsynet.no/', 'Etat', 'ec8e5903f92965753b384e9bfeb716afc645e2413f135340e9963852acf34467.png', 0),
(25, 'nakMKApg', 'forskningsetikk.no', 'De nasjonale forskningsetiske komiteene', NULL, NULL, 999148603, 'https://www.forskningsetikk.no/', '', '', 0),
(26, '6jwpWzTs', 'samisk.vgs.no', 'De samiske videregående skoler, Karasjok og Kautekeino', '', 0, 971578807, 'http://www.samisk.vgs.no/', 'Skole / Universitet', '997812244fc6d5024482a308e19e884002818c1df85c72635d822e612e29833a.jpg', 0),
(27, 'Uhu232E', 'dns.no', 'Den Nationale Scene AS', NULL, NULL, 811167452, 'https://dns.no/', 'Bedrift', '', 0),
(28, 'pljHy7vZ', 'operaen.no', 'Den Norske Opera & Ballett AS', NULL, NULL, 0, 'https://operaen.no/', 'Bedrift', '', 0),
(29, 'n8SI3kG', 'dss.dep.no', 'Departementenes sikkerhets- og serviceorganisasjon (DSS)', NULL, NULL, 974761424, 'http://www.dss.dep.no/', 'Etat', '', 0),
(30, 'B7I9MGqL', 'digdir.no', 'Digitaliseringsdirektoratet', NULL, NULL, 991825827, 'https://www.digdir.no/', 'Direktorat', '', 0),
(31, 'fwctqNFL', 'dibk.no', 'Direktoratet for byggkvalitet', NULL, NULL, 974760223, 'https://dibk.no/', 'Direktorat', '', 0),
(32, 'Rya1mihC', 'ehelse.no', 'Direktoratet for e-helse', NULL, NULL, 915933149, 'https://ehelse.no/', 'Direktorat', '', 0),
(33, 'oKz3W0bo', 'digdir.no', 'Direktoratet for forvaltning og IKT (DIFI)', NULL, NULL, 0, 'https://www.digdir.no/', '', '', 0),
(34, '2u3KPLwW', 'dfo.no', 'Direktoratet for forvaltning og økonomistyring (DFØ)', NULL, NULL, 986252932, 'https://dfo.no/', '', '', 0),
(35, 'LCl8DzVi', 'unit.no', 'Direktoratet for IKT og fellestjenester i høyere utdanning og forskning', NULL, NULL, 919477822, 'https://www.unit.no/', 'Direktorat', '', 0),
(36, 'VQAqNqdI', 'diku.no', 'Direktoratet for internasjonalisering og kvalitetsutvikling i høyere utdanning', NULL, NULL, 986339523, 'https://diku.no/', 'Direktorat', '', 0),
(37, 'Zcs3yhps', 'dirmin.no', 'Direktoratet for mineralforvaltning med Bergmesteren for Svalbard', NULL, NULL, 974760282, 'https://dirmin.no/', 'Direktorat', '', 0),
(38, 'vvSvUE6H', 'dsb.no', 'Direktoratet for samfunnssikkerhet og beredskap (DSB)', NULL, NULL, 974760983, 'https://www.dsb.no/', 'Direktorat', '', 0),
(39, 'yCBAIUM', 'dsa.no', 'Direktoratet for strålevern og atomsikkerhet', NULL, NULL, 867668292, 'https://www.dsa.no/', '', '', 0),
(40, 'NNObbJ84', 'norad.no', 'Direktoratet for utviklingssamarbeid (NORAD)', NULL, NULL, 971277882, 'https://www.norad.no/', '', '', 0),
(41, 'pkBLuLsh', 'diskrimineringsnemnda.no', 'Diskrimineringsnemda (2019-)', NULL, NULL, 988681954, 'https://www.diskrimineringsnemnda.no/', '', '', 0),
(42, '4THQGfu', 'kdu.no', 'Distriktssenteret', NULL, NULL, 992375434, 'http://www.distriktssenteret.no/', 'Etat', '7e70f5f62cb15579310defecbc762a1602d43c2001a8e59150544d0aa2a68e1f.svg', 0),
(43, 'z52soV2', 'dnb.no', 'DNB ASA', NULL, NULL, 981276957, 'https://www.dnb.no/', 'Bedrift', '617568dd7362d0231ca32e31a61338ef38c1fab847cbe89e0a25cca0abd1ad42.svg', 0),
(44, 'qRqh0UOC', 'domstol.no', 'Domstolene i Norge med underliggende enheter', NULL, NULL, 984195796, 'https://www.domstol.no/', '', '', 0),
(45, 'tz9VIoxP', 'eksportfinans.no', 'Eksportfinans ASA', NULL, NULL, 816521432, 'https://www.eksportfinans.no/', 'Bedrift', '', 0),
(46, 'V3bcduyW', 'eksportkreditt.no', 'Eksportkreditt Norge AS', NULL, NULL, 0, 'https://www.eksportkreditt.no/no/', 'Bedrift', '', 0),
(47, 'nd8fhF5z', 'eldreombudet.no', 'Eldreombudet', NULL, NULL, 0, 'https://eldreombudet.no/', '', '', 0),
(48, 'JALF7ekC', 'ecc.no', 'Electronic Chart Centre AS', NULL, NULL, 0, 'https://www.ecc.no/', 'Bedrift', '', 0),
(49, 'kaEFM46x', 'enova.no', 'Enova SF', NULL, NULL, 0, 'https://www.enova.no/', 'Bedrift', '', 0),
(50, 'qVtkMu4', 'entra.no', 'Entra ASA', NULL, NULL, 999296432, 'https://entra.no/', 'Bedrift', '', 0),
(51, 'A1KLsnSA', 'entur.org', 'Entur AS', NULL, NULL, 0, 'https://om.entur.no/', 'Bedrift', '', 0),
(52, 'L8KEpz6y', 'eos-utvalget.no', 'EOS-utvalget', NULL, NULL, 0, 'https://eos-utvalget.no/', '', '', 0),
(53, '8FwjLu8N', 'equinor.com', 'Equinor ASA', NULL, NULL, 0, 'https://www.equinor.com/', 'Bedrift', '', 0),
(54, 'sruYHt80', '', 'Fartøykontoret', NULL, NULL, 971315601, '', '', '', 0),
(55, 'cLT7wnr', 'filmparken.no', 'Filmparken AS', NULL, NULL, 0, 'https://www.filmparken.no/', 'Bedrift', '', 0),
(56, 'SlJastpW', '', 'Finansdepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(57, 'f1m9Z6wG', 'finanstilsynet.no', 'Finanstilsynet', NULL, NULL, 840747972, 'https://www.finanstilsynet.no/', 'Direktorat', '', 0),
(58, 'WfYkCsfz', 'fhf.no', 'Fiskeri- og havbruksnæringens forskningsfinansiering AS', NULL, NULL, 921995121, 'https://www.fhf.no/', 'Bedrift', '', 0),
(59, 'A5flKsXO', 'fiskeridir.no', 'Fiskeridirektoratet', NULL, NULL, 971203420, 'https://www.fiskeridir.no/', 'Direktorat', '', 0),
(60, 'wxVKVZTQ', 'flytoget.no', 'Flytoget AS', NULL, NULL, 965694404, 'https://flytoget.no/no/', 'Bedrift', '', 0),
(61, 'YrsaVC2j', 'fhi.no', 'Folkehelseinstituttet', '', 0, 983744516, 'http://www.fhi.no/', '', '9c25d8413328ed380ed5484dd3e8b8861682cf3f1526acd3d4aad1d397e160ff.png', 0),
(62, 'LPVv19sM', 'ftf.no', 'Folketrygdfondet', NULL, NULL, 0, 'https://www.folketrygdfondet.no/', 'Bedrift', '', 0),
(63, 'RFtvt1io', 'forbrukerradet.no', 'Forbrukerrådet', NULL, NULL, 0, 'https://www.forbrukerradet.no/', '', '', 0),
(64, 'MJpaYr2H', 'forbrukertilsynet.no', 'Forbrukertilsynet', NULL, NULL, 974761335, 'www.forbrukertilsynet.no', 'Direktorat', '', 0),
(65, 'BDNCriaC', 'fug.no', 'Foreldreutvalget for grunnopplæring', NULL, NULL, 989628011, 'https://www.fug.no/', '', '82da60f889ef24cff28055b7e58423310c73cd3c7f186ebab489bb56606741b4.svg', 0),
(66, 'CTdSAfxo', 'ffi.no', 'Forsvarets forskningsinstitutt', NULL, NULL, 0, 'https://www.ffi.no/', '', 'af1dd6b6f9482a8fc25ab24ae1acf2a138f80c31c8118511eda9b075dafa60e2.svg', 0),
(67, 'eNQIC651', 'forsvarsbygg.no', 'Forsvarsbygg', NULL, NULL, 0, 'https://www.forsvarsbygg.no/', '', 'a9fda7a728a6e032ae8c2ef74c4bcac2063f91278f8520e43e3481abdd29d2d7.svg', 0),
(68, 'GRF6rsFp', '', 'Forsvarsdepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(69, '4pm4cpMu', 'fma.no', 'Forsvarsmateriell', NULL, NULL, 916075855, 'https://www.fma.no/', '', '75aa211c73b0d561b709d9a280553289417a39b278929e7f2bc67e6230a32605.svg', 0),
(70, 'XF0jL1fR', 'ovf.no', 'Forvaltningsorganet for opplysningsvesenets fond (2019-)', NULL, NULL, 970955569, 'https://ovf.no/', '', 'ba748a1e4e6ad3f6fce4f72648e50cd3d1799fd50f1a421801ba7cbc21ae7844.png', 0),
(71, '1E2Bc5Ax', '', 'Fylkesmannen i Aust- og Vest-Agder', NULL, NULL, 0, '', '', '', 0),
(72, '7pSsPetr', '', 'Fylkesmannen i Finnmark', NULL, NULL, 0, '', '', '', 0),
(73, 'CuHaMslg', '', 'Fylkesmannen i Hedmark', NULL, NULL, 0, '', '', '', 0),
(74, 'CDJQGxme', '', 'Fylkesmannen i Hordaland', NULL, NULL, 0, '', '', '', 0),
(75, 'NOaWiXBU', '', 'Fylkesmannen i Møre og Romsdal', NULL, NULL, 0, '', '', '', 0),
(76, 'ZvSaVctB', '', 'Fylkesmannen i Nordland', NULL, NULL, 0, '', '', '', 0),
(77, 'v6H8aLDp', '', 'Fylkesmannen i Oslo og Akershus', NULL, NULL, 0, '', '', '', 0),
(78, 'Xy9YU8sg', '', 'Fylkesmannen i Rogaland', NULL, NULL, 0, '', '', '', 0),
(79, 'YhZBMa6o', '', 'Fylkesmannen i Trøndelag', NULL, NULL, 0, '', '', '', 0),
(80, 'etaXJQ6', '', 'Fylkesmannen i Vestfold', NULL, NULL, 0, '', '', '', 0),
(81, '7POsjwxW', '', 'Fylkesmennenes fellesadministrasjon (FMFA)', NULL, NULL, 0, '', '', '', 0),
(82, 'NSH0f7W5', '', 'Fylkesnemndene for barnevern og sosiale saker', NULL, NULL, 0, '', '', '', 0),
(83, 'qe5oUMH', '', 'Garantiinstituttet for eksportkreditt (GIEK)', NULL, NULL, 0, '', '', '', 0),
(84, 'yuok7BZS', 'gassco.no', 'Gassco AS', NULL, NULL, 983452841, 'https://www.gassco.no/', 'Bedrift', '9362eacab51f30485bf973e6d2821549468741efb4658996a57db043e1623312.png', 0),
(85, 'SdE6VAro', 'gassnova.no', 'Gassnova SF', NULL, NULL, 0, 'https://gassnova.no/', 'Bedrift', '2f4807756dc9ba2647ded53b3d69a4811ac214f8d0511a502f1c63be4437caf0.png', 0),
(86, 'zm8Jh4Fj', 'generaladvokaten.no', 'Generaladvokatembetet', NULL, NULL, 0, 'https://www.generaladvokaten.no/', '', '549f8401ef891dcf76f4b3b081fd6a3556dab013a29e8b75bee7d639f91a9d4b.png', 0),
(87, 'RvtEkuOR', 'cofacegk.no', 'GIEK Kredittforsikring AS', NULL, NULL, 0, 'https://www.cofacegk.no/', 'Bedrift', 'dcc6fb134edba00d121118f235261f4766753de64de7893e8550bfc73ea26117.png', 0),
(88, 'cIm4sLwW', 'graminor.no', 'Graminor AS', NULL, NULL, 0, 'http://www.graminor.no/', 'Bedrift', 'e0ee160a12a2f50b131a5d587aba525f3e0efb0d619769a9a4767855fa549afe.png', 0),
(89, 'g8YRiXLg', 'hi.no', 'Havforskningsinstituttet', NULL, NULL, 971349077, 'https://www.hi.no/hi', '', '115e7ba87b2d915aa79188ca21e94b4dba4ed049c36c619f6938d5cba1b7e1e2.svg', 0),
(90, '6yKElyFR', 'helfo.no', 'HELFO', NULL, NULL, 0, 'https://www.helfo.no/', '', '6d44620d19b12f6bf46c280975f7fc1cd04190ab7af63c00f63ca4f3e8044edd.svg', 0),
(91, 'EagXpqL1', 'helse-midt.no', 'Helse Midt-Norge RHF', NULL, NULL, 983658776, 'https://helse-midt.no/', 'Bedrift', 'd1eed5feb8af9f2dda0c275d9a30e4ea5f5be2e21209b962dfb080090e63bbcd.svg', 0),
(92, '3mr3M5jH', 'helse-nord.no', 'Helse Nord RHF', NULL, NULL, 883658752, 'https://helse-nord.no/', 'Bedrift', 'f74c35197868d7e7aad7007067cd8250cad38a9c1e2d50407277556708918c95.svg', 0),
(93, '6OdqEqlg', '', 'Helse- og omsorgsdepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(94, 'bbvwrmNr', 'helse-sorost.no', 'Helse Sør-Øst RHF', NULL, NULL, 991324968, 'https://www.helse-sorost.no/', 'Bedrift', 'cb9ed47a38d521a4bff38614b044372e790f10bd4ee2f5b1375468d4f50585d9.svg', 0),
(95, 'B4ZXkUjN', 'helse-vest.no', 'Helse Vest RHF', NULL, NULL, 983658725, 'https://helse-vest.no/', 'Bedrift', '891f5a38ed8ddebf8c01bde87ef129724468542a3b4dec2b7caeb343d7484d40.svg', 0),
(96, 'H4XRW0hi', 'helsedir.no', 'Helsedirektoratet', NULL, NULL, 983544622, 'http://www.helsedirektoratet.no/', '', 'b46376a8079aacc0bf321a1dd6a74c47fc1252c60cb12c07c2c1bc0f411f54fe.svg', 0),
(97, 'ZxPoHy2h', 'hovedredningssentralen.no', 'Hovedredningssentralen / Hovudredningssentralen', NULL, NULL, 0, 'https://www.hovedredningssentralen.no/', '', 'beebf36682156c2b940a785852ffdea47f71a46ae065316bbe6fa254f8320aed.png', 0),
(98, 'j63TGPre', 'husbanken.no', 'Husbanken', NULL, NULL, 942114184, 'http://www.husbanken.no/', 'Etat', '9edcc94648eca849b0f6eb3412a2c30a0137e4d90c49175a677b013d16c9ee6c.svg', 0),
(99, '8TLcw5t3', 'htu.no', 'Husleietvistutvalget / Husleigetvistutvalet', NULL, NULL, 0, 'http://www.htu.no/', 'Etat', '1ac835505cca0882d37b9a708a29a34020dddfdbd5716c7da21b6a1b367a5aba.png', 0),
(100, 'SW5Ikmlg', 'inn.no', 'Høgskolen i Innlandet', NULL, NULL, 0, 'https://www.inn.no/', '', 'b4352f9046810efc99df18594988b3bafbe24b747103efd62ad136ed61e199a4.svg', 0),
(101, 'T91xbjFr', 'himolde.no', 'Høgskolen i Molde', NULL, NULL, 0, 'https://www.himolde.no/', '', '225a6d3ee6172c4202a4d2274cc91d0f52fd5644f7d1b33144a4c7e27d25327a.svg', 0),
(102, 'pyoHe7n', 'hiof.no', 'Høgskolen i Østfold', NULL, NULL, 0, 'https://www.hiof.no/', '', '1cebb2ac060f9c186d8576b51cd0b09b21690a59a04403bb96c3c264ece6aef1.svg', 0),
(103, '4yqw7fYr', 'hivolda.no', 'Høgskulen i Volda', NULL, NULL, 0, 'https://www.hivolda.no/', '', '0d13ba6735c8c8baeda91834411fd4c41f89c5df25fede7fb9bbf85b70e88b90.svg', 0),
(104, 'fY5ntVb2', 'hvl.no', 'Høgskulen På Vestlandet', NULL, NULL, 917641404, 'https://www.hvl.no/', '', '2930dc824441e8fa8d5d7ef73e73bb717c9fc5cfc466188202029543576133ce.svg', 0),
(105, 'KxV6Dokw', '', 'IC Particles AS', NULL, NULL, 988635723, '', 'Bedrift', '', 0),
(106, 'GJqaFEi', 'innovasjonnorge.no', 'Innovasjon Norge', NULL, NULL, 986399445, 'http://www.innovasjonnorge.no/', 'Bedrift', 'b17f2757c3b32fedeb34f49a17333a3712742421b135e4e1628fe08cf8d2369c.svg', 0),
(107, 'mLh79yDn', 'imidi.no', 'Integrerings- og mangfoldsdirektoratet', NULL, NULL, 987879696, 'https://www.imdi.no/', '', '54884ec441c5dc7e43f34cf1575e85e74dd4147eb14c1c0531eb8365ad903bee.svg', 0),
(108, 'm24igG0F', 'reindeercentre.org', 'Internasjonalt reindriftssenter', NULL, NULL, 0, 'http://www.reindeercentre.org/', 'Etat', 'bb9db0b92804fcc82c49d8f8a96b0fa264c33b365d326173213d77501cb1733b.png', 0),
(109, 'lkUz2REb', 'investinor.no', 'Investinor AS', NULL, NULL, 992447141, 'https://investinor.no/', 'Bedrift', '92c52748061179a001130fa9a490e7f295948caddd7f0a062807856a210666fc.svg', 0),
(110, 'MkQPjbh', 'jernbanedirektoratet.no', 'Jernbanedirektoratet', NULL, NULL, 916810962, 'www.jernbanedirektoratet.no', 'Direktorat', '0e3cac52d6e5100e8100971e9a8b3b1021fc99ccf57d0570c8094803fab902a8.png', 0),
(111, '5ABZxUTj', 'justervesenet.no', 'Justervesenet', NULL, NULL, 874761192, 'www.justervesenet.no', 'Direktorat', 'f2e4d7566be17fb462b989f040ff1c1a316bdc46a455e86b3ed9867e88d272ab.png', 0),
(112, 'NVaVH2D', '', 'Justis- og beredskapsdepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(113, 'beHCWfUE', 'kartverket.no', 'Kartverket', '', 0, 971040238, 'http://www.kartverket.no/', 'Etat', '8058a8fd1bce82752b493640065684fb70cb1887233563f5fbfd3d1b6c96fe6e.svg', 0),
(114, 'cfRb3Uvi', 'kimen.no', 'Kimen Såvarelaboratoriet AS', NULL, NULL, 987003995, 'https://www.kimen.no/', 'Bedrift', 'c48ff6347bf62e5390e3b632799aacbaf61e9c55ddcf647b4fd3cab13216b914.png', 0),
(115, 'VJzGFCW6', 'kingsbay.no', 'Kings Bay AS', NULL, NULL, 930155500, 'https://kingsbay.no/', 'Bedrift', 'e985c4ae8752de9855ac10bc890531e70c82933eb226e161ff36704c0fc2add9.png', 0),
(116, 'S2nkSun8', 'kfir.no', 'Klagenemda for industrielle rettigheter', NULL, NULL, 999205437, 'https://kfir.no/', '', '04be4e0bc453d35bb4d07d6dd548ac1320e40e7728b11a153c47f0a8f1bb7e2d.svg', 0),
(117, 'kh3hovfu', 'knse.no', 'Klagenemndssekretariatet (Kns)', NULL, NULL, 918195548, 'https://www.klagenemndssekretariatet.no/', '', 'a81e8330e86ff47d87caecdfe273869241684335f6279294cd92b65a7d88e73f.png', 0),
(118, 'ROORhfC', '', 'Klima- og miljødepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(119, '2KShmQiT', 'gjenopptakelse.no', 'Kommisjonen for gjenopptakelse av straffesaker', NULL, NULL, 985847215, 'https://www.gjenopptakelse.no/', '', '1f7bb8a2d9a2be14d054ca146a092469ebe603a5a3bbb4cc1eb30ca1c22ec331.svg', 0),
(120, 'TdCoDdbY', '', 'Kommunal- og moderniseringsdepartement', NULL, NULL, 0, '', 'Departement', '', 0),
(121, '2NrvmU9a', 'kbn.com', 'Kommunalbanken AS', NULL, NULL, 981203267, 'https://www.kbn.com/', 'Bedrift', '', 0),
(122, '2N8071oV', 'kompetansenorge.no', 'Kompetanse Norge', NULL, NULL, 974788985, 'www.kompetansenorge.no', 'Direktorat', '24ef85d4f7e1619204bc74dfbe244957cec15ba2e71769e3a33aa49e11c7213d.png', 0),
(123, 'w8MMdSVC', 'kdu.no', 'Kompetansesenter for distriktsutvikling', NULL, NULL, 992375434, 'https://distriktssenteret.no/', '', '7e70f5f62cb15579310defecbc762a1602d43c2001a8e59150544d0aa2a68e1f.svg', 0),
(124, 'QwKDvY4b', 'konfliktraadet.no', 'Konfliktrådene / Konfliktråda', NULL, NULL, 986074465, 'https://www.konfliktraadet.no/', '', '8eaf48ef5001cff9c074ed486349998a95af65e0567c04298bca1586c3ae248e.png', 0),
(125, 'yKlDOXN', 'kongsberg.com', 'Kongsberg Gruppen ASA', NULL, NULL, 0, 'https://www.kongsberg.com/no/', 'Bedrift', '8b8357d4c77bcfed8fc333d020948718a5e3a7f0a81989806385655d8b8da008.svg', 0),
(126, 'P47qxUH3', 'kt.no', 'Konkurransetilsynet', NULL, NULL, 974761246, 'www.konkurransetilsynet.no', 'Direktorat', 'cf259c100f21a6d59762df4493f19b89090940e096845bfc14e1665e3bfb5c4f.jpg', 0),
(127, '6onmj5yJ', 'voldsoffererstatning.no', 'Kontoret for voldsoffererstatning / Kontoret for valdsoffererstatning', NULL, NULL, 0, 'https://www.voldsoffererstatning.no/', '', '5a1a39a0a6b3143bc3e1f9830e6f3079dfebfacf21b1fefda633dd3293d0fcf2.png', 0),
(128, 'Vy1QSXB', 'kriminalomsorg.no', 'Kriminalomsorgsdirektoratet', NULL, NULL, 0, 'http://www.kriminalomsorgen.no/', '', 'c2b8d528b38d88f87e3a7f1c257baee609917c146311613413cf6615f505f358.png', 0),
(129, '0opP14sB', '', 'Kulturdepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(130, 'tJQu6PM', 'kulturtanken.no', 'Kulturtanken - Den kulturelle skolesekken Norge', NULL, NULL, 974761114, 'www.kulturtanken.no', 'Direktorat', 'a6e793884edd863d8ee53ff7cb25bcd5b442aa77f937725c9dad1dec9a544182.svg', 0),
(131, 'tvj1zMGm', '', 'Kunnskapsdepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(132, 'mkefhfZZ', 'koro.no', 'Kunst i offentlige rom (KORO)', NULL, NULL, 974778998, 'www.koro.no', 'Direktorat', '8020b0ce751163cc332d1e610ee13b41886f829760e7b46cd99a7bce9983d3c9.svg', 0),
(133, '9pAHS29w', 'khio.no', 'Kunsthøgskolen i Oslo', NULL, NULL, 977027233, 'https://khio.no/', '', '078023c4a84d28c1ee8a07704adba72207bd846b391d553d9073d206e7e4504b.svg', 0),
(134, '5aH2SJ5m', 'kystverket.no', 'Kystverket', NULL, NULL, 874783242, 'www.kystverket.no', 'Direktorat', '958d30ce799438bb10881c66cd7bc54439b88776b3f5b554383b8295e94c32c8.svg', 0),
(135, 'XhhPvzuq', '', 'Landbruks- og matdepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(136, '8lCZXqP9', 'landbruksdirektoratet.no', 'Landbruksdirektoratet', NULL, NULL, 981544315, 'www.landbruksdirektoratet.no', 'Direktorat', 'eebb6265254745619b6bbfbafecea9eda88705776517d121229786d3c949d3d2.svg', 0),
(137, 'GcJt3Uk', 'ldo.no', 'Likestillings- og diskrimineringsombudet (2019-)', NULL, NULL, 988681873, 'https://www.ldo.no/', '', 'e6127b1b12e027c1718f13a5a64cb26500a25aa323397cd55a6c9d76c04747fa.svg', 0),
(138, 'uBVwh1mF', 'lottstift.no', 'Lotteri- og stiftelsestilsynet', NULL, NULL, 982391490, 'www.lottstift.no', 'Direktorat', '9ce84b2021dd59e819242c90e24e37bb45d86b68484ffe039f50206e7bdf5b30.svg', 0),
(139, 'beQOD3jr', 'caa.no', 'Luftfartstilsynet', NULL, NULL, 981105516, 'www.luftfartstilsynet.no', 'Direktorat', '63cf1a9f1c4e5fb1c567de55526775d97137b692223e8c477f1e707a6400ef58.svg', 0),
(140, 'egonrM14', 'lanekassen.no', 'Lånekassen', NULL, NULL, 960885406, 'www.lanekassen.no', 'Direktorat', '4c861fd05cce83a74cf3a47d52e652191a6e8da82330a41398d1b52724804497.svg', 0),
(141, 'c76ZoDaa', 'mantena.org', 'Mantena AS', NULL, NULL, 984040849, 'https://mantena.org/', 'Bedrift', 'f946ccedc9a559a9a98298c764c0bbb03f599d2667fc1689814a4443732fe4ec.png', 0),
(142, 'w7wwSSJC', 'mattilsynet.no', 'Mattilsynet', NULL, NULL, 985399077, 'http://www.mattilsynet.no/', '', '61493d282b9cd33d03cc0524c8ed25fe2795d36034377df6649a0226f366ac66.png', 0),
(143, 'hrpTBlsb', 'medietilsynet.no', 'Medietilsynet', NULL, NULL, 974760886, 'www.medietilsynet.no', 'Direktorat', 'd8dd3cbb176d4e5f547a6ae1ef64bf3a0cdeb2dfb9ce58f9f4ee754d2696d4ea.png', 0),
(144, 'wpd3tG3M', 'mesta.no', 'Mesta AS', NULL, NULL, 992804440, 'https://www.mesta.no/', 'Bedrift', 'd669b7a09c2963cf23b44915d73062b105aa81650a97396c61ef99b2a4e41837.svg', 0),
(145, '3oeQsWS', 'met.no', 'Meteorologisk institutt', NULL, NULL, 971274042, 'https://www.met.no/', '', '43b984a2a48479b5ece9b33b9b487448105370122329e0ceb79a6adcf1c6aa5a.svg', 0),
(146, '6o4U02bB', 'miljodir.no', 'Miljødirektoratet', NULL, NULL, 999601391, 'https://www.miljodirektoratet.no/', 'Direktorat', 'baf2316d0735d94501210bf344085d9fdc124f6754270438a0ae72bd27f7ada8.svg', 0),
(147, 'LsuqdBUS', 'nammo.com', 'Nammo AS', NULL, NULL, 979984731, 'https://www.nammo.com/', 'Bedrift', '1f896a1c518bff89ce2ce6cbef35c67f150aea0591b102ae1dca87cb5d167d7a.svg', 0),
(148, '4N5TM0Up', 'nkom.no', 'Nasjonal kommunikasjonsmyndighet /Nasjonal kommunikasjonsmyndigheit (Nkom)', NULL, NULL, 974446871, 'http://www.nkom.no/', 'Etat', 'e433c9b6e1d241a634ceed26a8d21e715d63c6f37b0fbafeb38110f6f2dee924.svg', 0),
(149, 'IFc4xNQ', 'nsm.no', 'Nasjonal sikkerhetsmyndighet / Nasjonalt tryggingsorgan', NULL, NULL, 985165262, 'https://nsm.no/', '', '1f74d825c35514b602983ff46d23a6512d229d0c9cb1d09b674d4ae1888b32ae.svg', 0),
(150, 'HC89LaeQ', 'nb.no', 'Nasjonalbiblioteket', NULL, NULL, 976029100, 'https://www.nb.no/', '', '0aded4396acdda702d735809042c8ee46245fab1689ce69da36876632b1f4e2c.svg', 0),
(151, 'D6GomvGk', 'helseklage.no', 'Nasjonalt klageorgan for helsetjenesten', NULL, NULL, 984936966, 'http://www.helseklage.no/', '', '3b05c6b2fc2e3c905b87d0d3260a36e1872bf2c44c5df13e1e49a73098e8c19c.svg', 0),
(152, 'WFKQdBaT', 'nokut.no', 'Nasjonalt organ for kvalitet i utdanningen (NOKUT)', NULL, NULL, 985042667, 'www.nokut.no', '', '78a956298c62cb8bc28ab90b656d40e310912206c54031f2ddb99272cdf46d1c.svg', 0),
(153, 'UUtDCuJU', 'nationaltheatret.no', 'Nationaltheatret AS', NULL, NULL, 914531365, 'https://www.nationaltheatret.no/', 'Bedrift', '79b1b8a15fd04d5b78522e5c57d03255ff405d74c3a64b5575bf089213bfe618.svg', 0),
(154, 'zjfjGVb7', 'nav.no', 'NAV - Arbeids- og velferdsdirektoratet', NULL, NULL, 889640782, 'https://www.nav.no/', '', '306a5067e0e3dec29f5c5c28a1791ec8aeaaf7e172e8168282ad7c957c58b3ad.svg', 0),
(155, 'JDR7UW3T', 'nidarosdomen.no', 'Nidaros domkirkes restaurereingsarbeider', NULL, NULL, 938966397, 'https://www.nidarosdomen.no/ndr', '', '0bcd8c5f67bce012038533355d4de5e362cf1bbce1a0fb425967aff6fb6e4535.svg', 0),
(156, 'oHJkGK8A', 'nofima.no', 'Nofima AS', NULL, NULL, 989278835, 'https://nofima.no/', 'Bedrift', '809a5510f49ce3c4f3233fd639651370f6de3b4dcab38619d4ac187b48bd4988.svg', 0),
(157, 'Ej3wOzsf', 'nord.no', 'Nord universitet', '', 0, 970940243, 'https://www.nord.no', 'Skole / Universitet', '6a70a809664d068c51cc76a1889254e7db3506c6d020e79b705a2fa916716730.jpg', 0),
(158, 'jbYyjInL', 'niom.no', 'Nordisk institutt for odontologiske materialer AS (NIOM AS)', NULL, NULL, 994970135, 'https://niom.no/', 'Bedrift', '71e29b8fe64e33b0f5b7b27262632413d8ed6ddb9ccb24e51b109320ed496b63.png', 0),
(159, 'YPxFFUgj', 'nib.int', 'Nordiske Investeringsbanken', NULL, NULL, 0, 'https://www.nib.int/', 'Bedrift', '388a89258e046e2df2d0292e5f0e8c026c69e44271d4861050834085fe87d82e.svg', 0),
(160, '2rDI8AqP', 'norfund.no', 'Norfund', NULL, NULL, 879554802, 'https://www.norfund.no/no/', 'Bedrift', '3677bd0df0c9b6259eb122aef888540e2227f752e350869d8db10627c4340e13.svg', 0),
(161, 'ALwQa84A', 'norges-bank.no', 'Norges Bank', NULL, NULL, 937884117, 'https://www.norges-bank.no/', 'Bedrift', '', 0),
(162, 'LmPW7uuh', 'forskningsradet.no', 'Norges forskningsråd', NULL, NULL, 970141669, 'http://www.forskningsradet.no/', '', '75c395e19efc0e1e29647ca22f4fd7da524099f387dbda17c632ea43b7a77322.svg', 0),
(163, 'EP1DFYAW', 'ngu.no', 'Norges geologiske undersøkelse', NULL, NULL, 970188290, 'https://www.ngu.no/', '', '8a61ab771cb15079875a593e633a37159f82c55ab45fa9e7b4785f0f14fd632a.svg', 0),
(164, 'QL0QTLjg', 'vea-fs.no', 'Norges Grønne Fagskole - Vea', NULL, NULL, 870961642, 'https://www.vea-fs.no/', '', '0a0a139a50c05d93490891470fb19d74df6d4d3bae9d8fa1bc1c08e4e9df58a0.svg', 0),
(165, 'L6mANGqF', 'nhh.no', 'Norges handelshøyskole', NULL, NULL, 974789523, 'https://www.nhh.no/', '', '5da21a898b5c35251715600e19487cf1e5bb022ede66d8dddfdba7fd7b879737.svg', 0),
(166, '2Jff5vi', 'nih.no', 'Norges idrettshøgskole', NULL, NULL, 971526033, 'https://www.nih.no/', '', 'a5d0fcfd56f61c1be2f25e5bc1d1e9ff94a6c68a4473a1e72ccdbc52bed8f8a8.svg', 0),
(167, '9V7yr6f', 'nmbu.no', 'Norges miljø- og biovitenskapelige universitet (NMBU)', NULL, NULL, 969159570, 'https://www.nmbu.no/', '', 'a9bed158493aa3152e9165074a10892915ee6c47bb100e2873e3d738b14f829d.png', 0),
(168, '6zML76Cc', 'nmh.no', 'Norges musikkhøgskole', NULL, NULL, 974761106, 'https://nmh.no/', '', '2095906f0d65a3a9a9fd22baa6f1a69bb9e407eaf8ac4bc03ce55e3db4769b79.svg', 0),
(169, 'OefWUl5F', 'nhri.no', 'Norges nasjonale institusjon for menneskerettigheter', NULL, NULL, 0, 'https://www.nhri.no/', '', '0d3a765f9166208d093e019524e3c579f5e0f78412cc21358d0a8f4f92265e56.svg', 0),
(170, '4wan0r3T', 'seafood.no', 'Norges sjømatråd AS', NULL, NULL, 988597627, 'https://seafood.no/', 'Bedrift', '', 0),
(171, 'uDSpEiOY', 'ntnu.no', 'Norges teknisk-naturvitenskapelige universitet NTNU', '', 0, 974767880, 'https://www.ntnu.no/', 'Skole / Universitet', '70633c8f99f9754ac6aa480e6fe83528814388f7b3873e530a71036d92b565ea.svg', 0),
(172, 'X9HvFFfJ', 'nve.no', 'Norges vassdrags- og energidirektorat (NVE)', NULL, NULL, 970205039, 'https://nve.no', '', '', 0),
(173, 'F4aRgO0y', 'norid.no', 'Norid', NULL, NULL, 985821585, 'https://www.norid.no/no/', '', '', 0),
(174, '3QrIrfl2', 'akkreditert.no', 'Norsk Akkreditering', NULL, NULL, 986028307, 'www.akkreditert.no', 'Direktorat', '', 0),
(175, 'vxPQCXlh', 'nfi.no', 'Norsk filminstitutt', NULL, NULL, 892211442, 'www.nfi.no', 'Direktorat', '', 0),
(176, 'G771Xkkt', 'nhn.no', 'Norsk Helsenett SF', NULL, NULL, 994598759, 'https://www.nhn.no/', 'Bedrift', '', 0),
(177, 'ixvYHalv', 'hydro.com', 'Norsk Hydro ASA', NULL, NULL, 914778271, 'https://www.hydro.com/', 'Bedrift', '', 0),
(178, 'Du6Vvtf1', 'nibio.no', 'Norsk institutt for bioøkonomi (NIBIO)', NULL, NULL, 890675522, 'https://www.nibio.no/', '', '', 0),
(179, 'MyzwjbhF', 'norsk-jernbanemuseum.no', 'Norsk jernbanemuseum', NULL, NULL, 918147764, 'https://jernbanemuseet.no/', '', '', 0),
(180, 'TduKAHpi', 'kulturminnefondet.no', 'Norsk kulturminnefond', NULL, NULL, 985980101, 'https://kulturminnefondet.no/', '', '', 0),
(181, 'imJfterR', 'kulturradet.no', 'Norsk kulturråd', NULL, NULL, 971527412, 'https://www.kulturradet.no/', 'Direktorat', '', 0),
(182, 'DQ152ahQ', 'nlb.no', 'Norsk lyd og blindeskriftbibliotek', NULL, NULL, 970145567, 'https://www.nlb.no/', '', '', 0),
(183, '5MLiITNy', 'nnd.no', 'Norsk Nukleær dekommisjonering', NULL, NULL, 920440754, 'https://www.norskdekommisjonering.no/', 'Direktorat', '', 0),
(184, 'B78c6t9t', 'npe.no', 'Norsk pasientskadeerstatning', NULL, NULL, 984936923, 'http://www.npe.no/', '', '', 0),
(185, 'zfrK12f', 'npolar.no', 'Norsk polarinstitutt', NULL, NULL, 971022264, 'https://www.npolar.no/', 'Direktorat', '', 0),
(186, 'cRQ5olVD', 'nrk.no', 'Norsk rikskringkasting AS', NULL, NULL, 976390512, 'https://www.nrk.no/', 'Bedrift', '', 0),
(187, 'TjxTkchU', 'spaceagency.no', 'Norsk romsenter', NULL, NULL, 886028482, 'https://www.romsenter.no/', '', '', 0),
(188, 'jJfY0auS', 'norec.no', 'Norsk senter for utvekslingssamarbeid (Norec)', NULL, NULL, 981965132, 'https://www.norec.no/', '', '', 0),
(189, 'vgKYljIb', 'norsk-tipping.no', 'Norsk Tipping AS', NULL, NULL, 925836613, 'https://www.norsk-tipping.no/', 'Bedrift', '', 0),
(190, 'nj0F9tOp', 'nupi.no', 'Norsk Utenrikspolitisk Institutt (NUPI)', NULL, NULL, 970955992, 'https://www.nupi.no/', '', '', 0),
(191, 'vUUD2Cp6', 'norsketog.no', 'Norske tog AS', NULL, NULL, 917445060, 'https://www.norsketog.no/', 'Bedrift', 'a0a9262a8e124b62b6d74287cfd1c3a51c5cd295d5f156b0c6bd8f583f06c8d0.svg', 0),
(192, 'J1DOTIs4', 'nsd.no', 'NSD  Norsk senter for forskningsdata AS', NULL, NULL, 985321884, 'https://www.nsd.no/', 'Bedrift', 'fba0bea932e370939e25c497adabd1daa17e8c6a028d36c97037a9ffe9d8fe4a.svg', 0),
(193, 'zacvQEaU', 'nyeveier.no', 'Nye Veier AS', NULL, NULL, 915488099, 'https://www.nyeveier.no/', 'Bedrift', '0d1c93397478caca3092531da055bf04c873c3bc00700b1ebf94f4c70f1848f7.png', 0),
(194, '9iVoZAe', 'nysnoinvest.no', 'Nysnø Klimainvesteringer AS', NULL, NULL, 920312039, 'https://www.nysnoinvest.no/', 'Bedrift', 'bfd6579bf7156bc1f85e0c772809e572e90a9954902c573eb4a8cc6c61a64675.svg', 0),
(195, '6Cygp74D', '', 'Nærings- og fiskeridepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(196, 'pOWVIBi4', '', 'Olje- og energidepartementet', NULL, NULL, 0, '', 'Departement', '', 0),
(197, 'AEVnrQ2M', 'npd.no', 'Oljedirektoratet', NULL, NULL, 870917732, 'https://www.npd.no/', 'Direktorat', '8dc2a845c31650c5b2930c32e1e8d389123036597bf6cfa147559030d607efb5.svg', 0),
(198, 'P69ss1Jl', 'ombudsmann.no', 'Ombudsmannen for Forsvaret', NULL, NULL, 971527439, 'https://ombudsmann.no/', '', '342d681775b055eb16d1f34d083dde650b7e12dc0ee3aaaa9f73ab9dd3573f60.jpg', 0),
(199, 'eHaSrgz', 'oslomet.no', 'OsloMet - storbyuniversitetet', NULL, NULL, 997058925, 'https://www.oslomet.no/', '', '30d54780cc12560e6fb9579e7a13a2cadab354421728edf204ef8951b8ae83f9.svg', 0),
(200, 'IpZl7SLz', 'patentstyret.no', 'Patentstyret', NULL, NULL, 971526157, 'www.patentstyret.no', 'Direktorat', '9426ea35e3a0618ca3487b1e4ced8c9973505a4e362be708ea99f9996d5c245a.jpg', 0),
(201, 'i5qI3HRH', 'pts.no', 'Pensjonstrygden for sjømenn', NULL, NULL, 940415683, 'www.pts.no', 'Direktorat', '7564f1b99bb31bf79fd4001304867a41d5280acae34710c2d1e867c89334895a.png', 0),
(202, '1WwDf6tk', 'petoro.no', 'Petoro AS', NULL, NULL, 983382355, 'https://www.petoro.no/', 'Bedrift', '8986078a8cede56450025bb529003f9eeb9c29d9e4d4509d6b6823f430cc7efe.svg', 0),
(203, 'LMx9fPVj', 'ptil.no', 'Petroleumstilsynet', NULL, NULL, 986174613, 'www.ptil.no', 'Direktorat', '6e6ee852bcf863829d8fc5958881c5a37c1e7705584a3734af4194a8b836bc70.svg', 0),
(204, 'WwDI76b9', 'politiet.no', 'Politidirektoratet', NULL, NULL, 912814076, 'https://www.politiet.no/', 'Direktorat', 'fa84cc2016186db11edb7df7d5a44440473bcbd94b81718c4e0d222ef04c4fdb.svg', 0),
(205, 'Tj4T3PrS', 'pst.politiet.no', 'Politiets sikkerhetstjeneste / Politiets tryggingsteneste', NULL, NULL, 0, 'https://www.pst.no/', '', '', 0),
(206, 'UB0HpIlt', '', 'Posten Norge AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(207, 'Dlz87Ick', '', 'Regelrådet', NULL, NULL, 0, '', '', '', 0),
(208, 'Ljlk1fCT', '', 'Registerenheten i Brønnøysund', NULL, NULL, 0, '', '', '', 0),
(209, 'OytvBFNB', '', 'Regjeringsadvokaten', NULL, NULL, 0, '', '', '', 0),
(210, 'PueZrvcO', '', 'Riksadvokaten', NULL, NULL, 0, '', '', '', 0),
(211, 'mUJBRX8r', '', 'Riksantikvaren', NULL, NULL, 974760819, 'www.riksantikvaren.no', 'Direktorat', '', 0),
(212, 'JGWKJpU', '', 'Riksmekleren', NULL, NULL, 0, '', '', '', 0),
(213, 'gcfLS2D', '', 'Riksrevisjonen', NULL, NULL, 0, '', '', '', 0),
(214, '5pafJ95B', '', 'Riksteatret', NULL, NULL, 0, '', '', '', 0),
(215, 'wRmu2h2a', '', 'Rogaland Teater', NULL, NULL, 0, '', 'Bedrift', '', 0),
(216, 'Wg3y8TYX', '', 'Rosenkrantzgt. 10 AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(217, 'TQNKVvtW', '', 'Rygge 1 AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(218, 'HyVvcAmX', '', 'Samediggi Sametinget med underliggende enheter', NULL, NULL, 0, '', '', '', 0),
(219, '1bjeY1LN', '', 'Sametinget', NULL, NULL, 0, 'http://www.samediggi.no/', '', '', 0),
(220, 'eZs10OFD', '', 'Samferdselsdepartementet', NULL, NULL, 0, '', '', '', 0),
(221, 'Sy6n4eX', '', 'Sámi allaskuvla Samisk høgskole', NULL, NULL, 0, '', '', '', 0),
(222, 'auebCL4o', '', 'Sekretariatet for markedsrådet og forbrukertvistutvalget', NULL, NULL, 0, '', '', '', 0),
(223, 'okr8Fums', '', 'Selskapet for industrivekst (SIVA)', NULL, NULL, 0, 'http://www.siva.no/', '', '', 0),
(224, 'CB24aOyK', '', 'Senter for oljevern og marint miljø', NULL, NULL, 0, '', '', '', 0),
(225, 'BAvyyTHQ', '', 'Sikkerhet og beredskap', NULL, NULL, 0, '', '', '', 0),
(226, 'Z7fuOTI5', '', 'Simula Research Laboratory AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(227, '78z0paK7', '', 'Siva  Selskapet for Industrivekst SF', NULL, NULL, 0, '', 'Bedrift', '', 0),
(228, 'FXwNCl4K', '', 'Sivil klareringsmyndighet', NULL, NULL, 918929673, 'https://skm.stat.no/', 'Direktorat', '', 0),
(229, 'b1ZOoa3W', '', 'Sivilombudsmannen', NULL, NULL, 0, '', '', '', 0),
(230, 's0z8UAXE', '', 'Sivilombudsmannen', NULL, NULL, 0, '', '', '', 0),
(231, 'HZyWWCUT', '', 'Sjøfartsdirektoratet', NULL, NULL, 974761262, 'www.sjofartsdir.no', 'Direktorat', '', 0),
(232, '4zWSDysV', '', 'Skattedirektoratet', NULL, NULL, 974761076, 'www.skatteetaten.no', 'Direktorat', '', 0),
(233, 'J3O17cJ', '', 'Skatteetaten', NULL, NULL, 0, 'https://www.skatteetaten.no', 'Etat', '', 0),
(234, 'wERdRIif', '', 'Skatteetaten i alt', NULL, NULL, 0, '', '', '', 0),
(235, 'cDMaix7o', '', 'Space Norway AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(236, 'kJED2eE', '', 'Spesialenheten for politisaker', NULL, NULL, 0, '', '', '', 0),
(237, 'laj9EERs', '', 'Språkrådet', NULL, NULL, 0, '', '', '', 0),
(238, '0fwA6Vkq', '', 'Statens arbeidsmiljøinstitutt', NULL, NULL, 0, '', '', '', 0),
(239, 't6IvrUm4', '', 'Statens havarikommisjon for transport', NULL, NULL, 881143712, 'www.aibn.no', 'Direktorat', '', 0),
(240, 'MjTmDSBa', '', 'Statens helsetilsyn', NULL, NULL, 0, 'http://www.helsetilsynet.no/', '', '', 0),
(241, 'lYkVWrsG', '', 'Statens jernbanetilsyn', NULL, NULL, 979363974, 'www.sjt.no', 'Direktorat', '', 0),
(242, 'tWpgaS1W', '', 'Statens kartverk', NULL, NULL, 971040238, 'www.kartverket.no', 'Direktorat', '', 0),
(243, 'z2aaU7xr', '', 'Statens legemiddelverk', NULL, NULL, 0, 'http://www.legemiddelverket.no/', '', '', 0),
(244, 'rIXDepvD', '', 'Statens lånekasse for utdanning', NULL, NULL, 0, '', '', '', 0),
(245, 'VUtZ8AZn', '', 'Statens Pensjonskasse', NULL, NULL, 0, 'https://www.spk.no/', '', '', 0),
(246, '7GOasC', '', 'Statens Pensjonskasse forvaltningsbedrift', NULL, NULL, 0, '', '', '', 0),
(247, '5Kdea50u', '', 'Statens sivilrettsforvaltning', NULL, NULL, 0, 'http://www.sivilrett.no/', '', '', 0),
(248, 'JuuIrh08', '', 'Statens strålevern', NULL, NULL, 0, '', '', '', 0),
(249, 'HoGqXXe7', '', 'Statens undersøkelseskommisjon for helse- og omsorgstjenesten (UKOM)', NULL, NULL, 0, 'https://www.ukom.no/', '', '', 0),
(250, 'EvYvHBGX', '', 'Statens vegvesen', NULL, NULL, 0, 'https://www.vegvesen.no/', 'Etat', '', 0),
(251, 'QRfpRqMC', '', 'Statistisk sentralbyrå', NULL, NULL, 971526920, 'www.ssb.no', 'Direktorat', '', 0),
(252, 'SKFlzEpd', '', 'Statkraft SF', NULL, NULL, 0, '', 'Bedrift', '', 0),
(253, 'hNhIVEjs', '', 'Statnett SF', NULL, NULL, 0, '', 'Bedrift', '', 0),
(254, 'ALfrpVcS', '', 'Statped', NULL, NULL, 0, '', '', '', 0),
(255, 'phWMrk', '', 'Statsbygg', NULL, NULL, 0, 'http://www.statsbygg.no/', 'Etat', '', 0),
(256, 'fh4sqejV', '', 'Statsforvalteren / statsforvaltaren', NULL, NULL, 0, 'https://www.statsforvalteren.no/', 'Etat', '', 0),
(257, 'U3DqVlzn', '', 'Statskog SF', NULL, NULL, 0, '', 'Bedrift', '', 0),
(258, '53DMlG7Y', '', 'Statsministerens kontor', NULL, NULL, 0, '', '', '', 0),
(259, '7zqyN2v1', '', 'Staur gård AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(260, 'KKel0rEf', '', 'Store Norske Spitsbergen Kulkompani AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(261, 'sPKHvgz2', '', 'Stortinget', NULL, NULL, 0, '', '', '', 0),
(262, 'oBVDJVUA', '', 'Sysselmannen på Svalbard', NULL, NULL, 0, 'https://www.sysselmannen.no/', '', '', 0),
(263, 'SKaYCD0l', '', 'Talent Norge AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(264, 'mRBsZINh', '', 'Telenor ASA', NULL, NULL, 0, '', 'Bedrift', '', 0),
(265, 'LczFagV', '', 'Tilsynsrådet for advokatvirksomhet', NULL, NULL, 0, '', '', '', 0),
(266, 'YWW93TTR', '', 'Tolldirektoratet', NULL, NULL, 974761343, 'www.toll.no', 'Direktorat', '', 0),
(267, 'FfUQYrDe', 'toll.no', 'Tolletaten', '', 0, 0, 'https://www.toll.no/', 'Etat', 'cc43aa1d876204f3df94fe5a57766f5da970e52881d020c701103d00466e125b.png', 0),
(268, 'bZ2XZdzW', '', 'Tollvesenet', NULL, NULL, 0, 'https://www.toll.no/', 'Etat', '', 0),
(269, 'ZTegdh3P', '', 'Trygderetten', NULL, NULL, 0, '', '', '', 0),
(270, 'tRTpUzUy', '', 'Trøndelag Teater AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(271, 'IHnd4Iql', 'uninett.no', 'UNINETT AS', '', 0, 968100211, 'https://www.uninett.no/', 'Bedrift', '982c45edd35a28d96b46fc37561d32e53bdcc9acd9598438f834b78506631e99.svg', 0),
(272, 'Y9fSldRj', '', 'UNIT- Direktoratet for IKT og fellestjenester i høyere utdanning og forskning', NULL, NULL, 0, '', '', '', 0),
(273, 'Z1VmxvG6', 'uia.no', 'Universitetet i Agder', '', 0, 970546200, 'https://www.uia.no/', 'Skole / Universitet', '94f02c13cde1ad2d87f170a249718e320bd563f583dcf20e2f7d430cee14dee3.svg', 0),
(274, 'UqXlRvh3', 'uib.no', 'Universitetet i Bergen', '', 0, 874789542, 'https://www.uib.no/', 'Skole / Universitet', '2dafff65b10e03b034a4118ca0086bee7e862305d7898b95cf255d506b28d717.svg', 0),
(275, 'NsYz6mlc', 'uio.no', 'Universitetet i Oslo', '', 0, 971035854, 'https://www.uio.no/', 'Skole / Universitet', '1a286a055faa8855748f1ba79ff0228147e446e6f7fef783a141bb4678196912.svg', 0),
(276, 'XGudTXTA', 'uis.no', 'Universitetet i Stavanger', '', 0, 971564679, 'https://www.uis.no/', 'Skole / Universitet', 'c4001356c5fe4d4575537240520997a214918141bea78ba3577abdc90f99f5ab.svg', 0),
(277, 'N6glqOYQ', 'usn.no', 'Universitetet i Sørøst-Norge', '', 0, 911770709, 'https://www.usn.no/', 'Skole / Universitet', '6dd07de533518d0ef71d1b25925cee2c2794a4062252cbc3a3aabbcc3aa15815.svg', 0),
(278, '4VVSuXFe', 'uit.no', 'Universitetet i Tromsø - Norges arktiske universitet', '', 0, 970422528, 'https://uit.no/', 'Skole / Universitet', '20eb26b200dee77f607b4d4ae59e43df8da2981c054934c67dde784a5012b44d.svg', 0),
(279, '67f70pQT', 'unis.no', 'Universitetssenteret på Svalbard AS (UNIS)', '', 0, 985204454, 'https://www.unis.no/', 'Skole / Universitet', '4b9d383da4f85f783506e660b9249379ebab8cfc2c3a6f819e028e10907c00b8.svg', 0),
(280, 'uJaUjsYW', '', 'Utdanningsdirektoratet', NULL, NULL, 0, 'https://www.udir.no/', '', '', 0),
(281, 'T29659Im', '', 'Utenriksdepartementet', NULL, NULL, 0, '', '', '', 0),
(282, 'lrcyz8Ic', '', 'Utlendingsdirektoratet (UDI)', NULL, NULL, 0, 'https://www.udi.no/', '', '', 0),
(283, 'xS5HUczS', '', 'Utlendingsnemnda (UNE)', NULL, NULL, 0, 'https://www.une.no/', '', '', 0),
(284, 'UpaZIeHq', '', 'Valgdirektoratet', NULL, NULL, 916132727, 'www.valg.no', 'Direktorat', '', 0),
(285, 'KA7X3bJ8', '', 'Vegdirektoratet', NULL, NULL, 971032081, 'www.vegvesen.no', 'Direktorat', '', 0),
(286, 'qz7FK8Pd', '', 'Vegtilsynet', NULL, NULL, 917928010, 'www.vegtilsynet.com', 'Direktorat', '', 0),
(287, 'DBbyd868', '', 'Veterinærinstituttet', NULL, NULL, 0, '', '', '', 0),
(288, 'dmxAWuV', '', 'Vinmonopolet, AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(289, 'jLLAlBM', '', 'Vygruppen AS', NULL, NULL, 0, '', 'Bedrift', '', 0),
(290, 'KLHpKIN', '', 'Yara International ASA', NULL, NULL, 0, '', 'Bedrift', '', 0),
(291, 'JKd8ulej', 'samisk365.org', 'Åarjelsaemien vierhtiesåafoe - Sørsamisk kunnskapspark', '', 0, 974764814, 'https://samisk365.org/', 'Organisasjonsledd', '8ab2bcaa4439cf2cde9674f1baef0e4f99bb18497899dadb6e66f014ee20881f.png', 0),
(292, 'QUtdipQ6', 'halden.kommune.no', 'Halden', 'Viken', 3001, 0, '', 'Kommune', '8d434032133921e8324339c1548b18bca43d5d34ebe032430c1cd1f6a4dd89a8.svg', 0),
(293, '59IG09do', 'moss.kommune.no', 'Moss', 'Viken', 3002, 0, '', 'Kommune', '7f701e35fd8ed43a550ca1bbd8d82db565b747b7c1a4938146752821f3b264c1.svg', 0),
(294, '8Y4O1Ycg', 'sarpsborg.kommune.no', 'Sarpsborg', 'Viken', 3003, 0, '', 'Kommune', '33448aae95160ef9095d6777880f38830364a67c7d200d24da87859b656b84fb.svg', 0),
(295, 'nzZaZlRk', 'fredrikstad.kommune.no', 'Fredrikstad', 'Viken', 3004, 0, '', 'Kommune', '3c1d51d4814c5d05b529391c16eacc5082d0008dcafd741d32254ddb338c51b2.svg', 0),
(296, 'bBBhbhWw', 'drammen.kommune.no', 'Drammen', 'Viken', 3005, 0, '', 'Kommune', '21d05307728e6e9dcab2844873a51db9417194b259c4eedb522ea509e1976d23.svg', 0),
(297, 'qxct05Dr', 'kongsberg.kommune.no', 'Kongsberg', 'Viken', 3006, 0, '', 'Kommune', 'b6441c214e09935ac920bd251b758137bc3bd18b2178be25fca5b32638185af1.svg', 0),
(298, 'kopzhRQK', 'ringerike.kommune.no', 'Ringerike', 'Viken', 3007, 0, '', 'Kommune', '195084c462385db611995dfa640b50f62110a396461a751e552f581dbbe3f012.svg', 0),
(299, 'WGq67kDp', 'hvaler.kommune.no', 'Hvaler', 'Viken', 3011, 0, '', 'Kommune', '3cf5e7d6007f2106cf63fed15f6ddd3b2abfddf87b6a88a9d41e8c3d95678c69.svg', 0),
(300, 'ZXHGLVb6', 'aremark.kommune.no', 'Aremark', 'Viken', 3012, 0, '', 'Kommune', 'b5778cd64352bd06c900340bf08814026e884f2801bf39e9e4f861603d0a1d28.svg', 0),
(301, 'EZpvCx8n', 'marker.kommune.no', 'Marker', 'Viken', 3013, 0, '', 'Kommune', '1a2e9f71fe9518fbb9eaa520d9263b026f0ebd7004761799cba801666793a527.svg', 0),
(302, 'T2fa3Xfl', 'io.kommune.no', 'Indre Østfold', 'Viken', 3014, 0, '', 'Kommune', 'cd29609320e3819a0890d9c8ca1b4552f2033826f9e8bfc646afd9e080ed2503.svg', 0),
(303, 'ZwLXILOF', 'skiptvet.kommune.no', 'Skiptvet', 'Viken', 3015, 0, '', 'Kommune', 'afbe67c7d9558e7b4b7de5103ed8a64ba028a4d17b3f3e41930f02bcf107c4fe.svg', 0),
(304, 'csxrWfYJ', 'rakkestad.kommune.no', 'Rakkestad', 'Viken', 3016, 0, '', 'Kommune', '88a0cc258ec9e44e32926b8fc5fdc70c004b277e68fd31e546835747414e8a5b.svg', 0),
(305, 'HnPg19NC', 'rade.kommune.no', 'Råde', 'Viken', 3017, 0, '', 'Kommune', '83a9bfb0f238dc59e690f12eae73bd26ff862e050ec4a80b48c2942109536624.svg', 0),
(306, 'pYreb0sb', 'valer.kommune.no', 'Våler (Viken)', 'Viken', 3018, 0, '', 'Kommune', '32cbb9bad0cb44e1844c95c64dac3e1a20b4275ab98262aad0d5ad54dfbea6f4.svg', 0),
(307, 'aD3mWIwf', 'vestby.kommune.no', 'Vestby', 'Viken', 3019, 0, '', 'Kommune', '254bb9cd2008897893e641be8eced0b711568bfd62bf0eccce8db39aea082f89.svg', 0),
(308, 'XG6yX4EJ', 'nordrefollo.kommune.no', 'Nordre Follo', 'Viken', 3020, 0, '', 'Kommune', '37512556d83daeacca831a91049fcc95f6f1d07a819d169a7279536755f70f3f.svg', 0),
(309, 'CG0JNGHy', 'as.kommune.no', 'Ås', 'Viken', 3021, 0, '', 'Kommune', '2846a303c77e77b8c7c3c5f3e5923753fc404b355acb68dfa733ec3ea0e58b83.svg', 0),
(310, 'Byol8XEm', 'frogn.kommune.no', 'Frogn', 'Viken', 3022, 0, '', 'Kommune', '305c94660be95a5496f5e5aa26b58bd17b212d553e867e45d296c3fc09a45074.svg', 0),
(311, 'ttRpQtC', 'nesodden.kommune.no', 'Nesodden', 'Viken', 3023, 0, '', 'Kommune', '34f6a89396eaa1d391d15de6b9836ff01fa74c2ab61585fa3bc2d1d26a76a9be.svg', 0),
(312, 'bjIzsRB', 'baerum.kommune.no', 'Bærum', 'Viken', 3024, 0, '', 'Kommune', '40c9a25f023e25ea93b80009744cb24999b90566411323d4a6158848f36296d0.svg', 0),
(313, 'vRVn4595', 'asker.kommune.no', 'Asker', 'Viken', 3025, 0, '', 'Kommune', '31a23b9844ef7b5cf1ba765a20669dc4ef60a532980d4450d40cc0a46e1dca8a.svg', 0),
(314, 'x24KmWRv', 'ahk.no', 'Aurskog-Høland', 'Viken', 3026, 0, '', 'Kommune', '594bd7e549c12f973012e40d52eb1aad5ad753617491feeaf49853c8bff50ca8.svg', 0),
(315, 'LJ4VYHD1', 'ralingen.kommune.no', 'Rælingen', 'Viken', 3027, 0, '', 'Kommune', '56655abd61c0f9c9664d3876f6bf63fa3f3187d1d1bb75dfd473e31ab1f92391.svg', 0),
(316, 'mROAkHo4', 'enebakk.kommune.no', 'Enebakk', 'Viken', 3028, 0, '', 'Kommune', 'ba568eaca8e9ff7b72cdb5e16eb5d8e5c7de3fbc66cb321aaa03730da9b17314.svg', 0),
(317, '9sb7vklP', 'lorenskog.kommune.no', 'Lørenskog', 'Viken', 3029, 0, '', 'Kommune', '6bd016aaf56469fd77cfc453a503ac53b34a6b11c57f295406d445a610ced19d.svg', 0),
(318, 'cQxZGoHb', 'lillestrom.kommune.no', 'Lillestrøm', 'Viken', 3030, 0, '', 'Kommune', '70091ee3d526c71c0c4aff5bd8fa58107203d93350ade617200517e96ca6875e.svg', 0),
(319, 'ooqb07YD', 'nittedal.kommune.no', 'Nittedal', 'Viken', 3031, 0, '', 'Kommune', '27828270c16dab28b149c1231b59cfea632916c76c8599db97c26f677ae87f91.svg', 0),
(320, 'vEMde4IS', 'gjerdrum.kommune.no', 'Gjerdrum', 'Viken', 3032, 0, '', 'Kommune', '70c5807217ea9adf969b52082b3da7b68a6b4616e41767219f9c3993f2637a04.svg', 0),
(321, 'AF62VPtJ', 'ullensaker.kommune.no', 'Ullensaker', 'Viken', 3033, 0, '', 'Kommune', '05787391a8b6764613dae1d2f7cb84d169ee80a6fa0229808a84014a4e625307.svg', 0),
(322, 'VtpGG6UU', 'nes.kommune.no', 'Nes', 'Viken', 3034, 0, '', 'Kommune', '55e9e851b675b200d2a24de883d5bfd44f0ac86523241e802a93016d145160fc.svg', 0),
(323, 'Ek5p3Eol', 'eidsvoll.kommune.no', 'Eidsvoll', 'Viken', 3035, 0, '', 'Kommune', '227217d9e48ce5b9b5034cba5c1f0acd0d108e4f8e9f3af9d3c168d76cd810e8.svg', 0),
(324, 'iabP1Lk6', 'nannestad.kommune.no', 'Nannestad', 'Viken', 3036, 0, '', 'Kommune', 'e3bfe93212e0bce6bf104fcf0111737322cf2c904a822e2814dbf5e397d25fed.svg', 0),
(325, 's1mBbC', 'hurdal.kommune.no', 'Hurdal', 'Viken', 3037, 0, '', 'Kommune', '5324d6f6e3749aeff1468eb23c5c3bd6359c9fe1b52f2f980b607dc544712a8e.svg', 0),
(326, 'ftBTGCbE', 'hole.kommune.no', 'Hole', 'Viken', 3038, 0, '', 'Kommune', 'e53a2560c65cd68548560bcd5c466f61ef22913592e76b9696a02615da0a6dfb.svg', 0),
(327, 'uhEhpWX0', 'flaa.kommune.no', 'Flå', 'Viken', 3039, 0, '', 'Kommune', 'c6a292e6a682871f6915af8b850161ad2a65c78e021fd01cd6cd00e69cb469df.svg', 0),
(328, 'wNOrJ0YL', 'nesbyen.kommune.no', 'Nesbyen', 'Viken', 3040, 0, '', 'Kommune', '6ecdc96f677dcd04578c92270bc7bdc142fd9a9c25f88afb3b3c30d2ba246113.svg', 0),
(329, 'dkDwKbal', 'gol.kommune.no', 'Gol', 'Viken', 3041, 0, '', 'Kommune', '080316b1315a06b2009cdaa40bf782da833dd245dfabd3e03f98eb6b44cacf55.svg', 0),
(330, 'q7fIlYGf', 'hemsedal.kommune.no', 'Hemsedal', 'Viken', 3042, 0, '', 'Kommune', 'a1f32087c25e97dada62d2839a6f99334af4adc8dc9959fb54f1ed1fb8160943.svg', 0),
(331, '1xLWuQ9', 'aal.kommune.no', 'Ål', 'Viken', 3043, 0, '', 'Kommune', '4c29a4a808a0c8e78280e4df05e0623c478422b9e2e1da0683a161c4bf233813.svg', 0),
(332, 'hrEzNVUN', 'hol.kommune.no', 'Hol', 'Viken', 3044, 0, '', 'Kommune', '91feb540817b66538fa24795c631213ef5c935cf3cf69e6cffe1e2b16b95790b.svg', 0),
(333, '2YQx4Zg', 'sigdal.kommune.no', 'Sigdal', 'Viken', 3045, 0, '', 'Kommune', 'd2d415b7d60a2c49c3bad69286932a6cab8959a9998363f5b7a9519ab6292f32.svg', 0),
(334, 'JoQ3TP9z', 'krodsherad.kommune.no', 'Krødsherad', 'Viken', 3046, 0, '', 'Kommune', '57889108ef963ae4ecfc1a7f12f90ed46d40feb855ed96a90dddb311cff53268.svg', 0),
(335, 'Irdc9JF4', 'modum.kommune.no', 'Modum', 'Viken', 3047, 0, '', 'Kommune', '8f75ae275664c82c4e49d1952a1de7bcf7ea6b9c93516387ecfe827d75ce6697.svg', 0),
(336, 'DeynzKjB', 'ovre-eiker.kommune.no', 'Øvre Eiker', 'Viken', 3048, 0, '', 'Kommune', '0e8e81870ea673e5f0252b361c90f78a4070cf63b8351ad04ec991b456523f02.svg', 0),
(337, 'RAMIhtat', 'lier.kommune.no', 'Lier', 'Viken', 3049, 0, '', 'Kommune', 'c563e2ef50105f2eaf4580cf45e8a85028a9986bfced99c77c86d8143e802e8b.svg', 0),
(338, 'z2ys0oAa', 'flesberg.kommune.no', 'Flesberg', 'Viken', 3050, 0, '', 'Kommune', 'a16b0f159fc5259801cd6a1a7f237ece05076fa96c4801eda37aa05c5b976e51.svg', 0),
(339, 'SSzGvd5', 'rollag.kommune.no', 'Rollag', 'Viken', 3051, 0, '', 'Kommune', '38c197b1f219da04dced51205544504db831079d20d7e0628244aea6a494de94.svg', 0),
(340, '12KV245r', 'nore-og-uvdal.kommune.no', 'Nore og Uvdal', 'Viken', 3052, 0, '', 'Kommune', 'c878cb2d839f8e9838e342eeef5f4ec2da8071d0ae17280c68d9d7bce78e7489.svg', 0),
(341, 'SQmGe6Ua', 'jevnaker.kommune.no', 'Jevnaker', 'Viken', 3053, 0, '', 'Kommune', 'fb60808fd2bf6a81be287a751f34af44361d60ca0d275b1c24e0f4eb83bdbf3c.svg', 0),
(342, 'EV2A7dMT', 'lunner.kommune.no', 'Lunner', 'Viken', 3054, 0, '', 'Kommune', '7d2be6f85e39dfd5310700c19768efdf306a24a8fefa3d87e130210a5498e804.svg', 0),
(343, '6TQLWDlc', 'oslo.kommune.no', 'Oslo kommune', 'Oslo', 301, 0, '', 'Kommune', '72e59daffee7fb46c4507d3afc2b798fdc6dc09f63f5d2bec3c5136bb0b4d5b2.svg', 0),
(344, '7v51NLSr', 'kongsvinger.kommune.no', 'Kongsvinger', 'Innlandet', 3401, 0, '', 'Kommune', '2b549c9c429c6036b9ddd7f6dd84a4a2bad85fe6571d83395d6dce9e86f489ec.svg', 0),
(345, '4cfCpc', 'hamar.kommune.no', 'Hamar', 'Innlandet', 3403, 0, '', 'Kommune', 'd72e7b396763692a84280fb1d3610520c4ec3301240ac521f04a658438208e62.svg', 0),
(346, '4kzUKv', 'lillehammer.kommune.no', 'Lillehammer', 'Innlandet', 3405, 0, '', 'Kommune', 'f97418259efdce5b2184b5612c945ea5f829e84998b0f73361cb122ef98d0777.svg', 0),
(347, 'wtnktHC', 'gjovik.kommune.no', 'Gjøvik', 'Innlandet', 3407, 0, '', 'Kommune', 'adee84d62a8ce1020a7ee025ec0ab3df04907f27912e65fba6ea601d9631174f.svg', 0),
(348, 'o35oftJV', 'ringsaker.kommune.no', 'Ringsaker', 'Innlandet', 3411, 0, '', 'Kommune', '20e80883e95c24f526467e883d073637e0e39bb03c9a723a25b007fbd67a2c69.svg', 0),
(349, 'Y6PutqNh', 'loten.kommune.no', 'Løten', 'Innlandet', 3412, 0, '', 'Kommune', '0c640ece38dd3069b1dd938b1da8b13a756c511e79d0c5a517180e4a1b17f27c.svg', 0);
INSERT INTO `company` (`id`, `public_id`, `domain`, `title`, `county`, `type_id`, `org_numb`, `website`, `type`, `logo`, `access`) VALUES
(350, 'CMqqYcYS', 'stange.kommune.no', 'Stange', 'Innlandet', 3413, 0, '', 'Kommune', '553d07ca617d7ad3028bc225622eb569a6e16f2ce88c51a2a029d3e8a5f79f14.svg', 0),
(351, 'J3eAj4dp', 'nord-odal.kommune.no', 'Nord-Odal', 'Innlandet', 3414, 0, '', 'Kommune', '91ed248d98f45254053432a90f9585fc7c5578fa217e27f4bda7e9d1cb3deee3.svg', 0),
(352, 'GudRYcOK', 'sor-odal.kommune.no', 'Sør-Odal', 'Innlandet', 3415, 0, '', 'Kommune', '8a391b261d70899cef9d28e736a6e42463c1643e9e6292ad6e4eb47a77057d76.svg', 0),
(353, '0eZsQm2x', 'eidskog.kommune.no', 'Eidskog', 'Innlandet', 3416, 0, '', 'Kommune', 'ef910202bb720f508495059b1e9b49f0f13393bf2d45cb3aaf235ccd8c8732f6.svg', 0),
(354, '86QlbrAb', 'grue.kommune.no', 'Grue', 'Innlandet', 3417, 0, '', 'Kommune', 'bb846525f2ae787da88d275681a2a6c5e9f2d6bf6e36943621b235234fd702d7.svg', 0),
(355, 'gtxOK28a', 'asnes.kommune.no', 'Åsnes', 'Innlandet', 3418, 0, '', 'Kommune', '1e0539fefd7930c6e691c7f5a8d0f1722cff634faab71f2cad1a7f6ef123c233.svg', 0),
(356, 'nW9ne6gg', 'valer-of.kommune.no', 'Våler (Innlandet)', 'Innlandet', 3419, 0, '', 'Kommune', '58332f388a1a00fb57d843f19525dc14f3a8bf084883f4daf0dfd1fa9359662b.svg', 0),
(357, 'VmdYAKgW', 'elverum.kommune.no', 'Elverum', 'Innlandet', 3420, 0, '', 'Kommune', '66e08d8a3e4458ca7f4e208ad1d8ec644454c5cf674a7f58ad1c3bb46ed3d6ad.svg', 0),
(358, 'cuiNGGgU', 'trysil.kommune.no', 'Trysil', 'Innlandet', 3421, 0, '', 'Kommune', '2bcf099804def51b6773159633ccf24ecf9f06f1c9ff8ecbb5eb4eeaaca9037d.svg', 0),
(359, '985hHNbR', 'amot.kommune.no', 'Åmot', 'Innlandet', 3422, 0, '', 'Kommune', 'f7bff8559c05cb33ae4c7172e54b260f47b2b466cf84f93fc59fbd3cb60d363f.svg', 0),
(360, '7daITKZK', 'stor-elvdal.kommune.no', 'Stor-Elvdal', 'Innlandet', 3423, 0, '', 'Kommune', '92273f4f112166ac9851db08de62b2cc3bec2001436514d2a44e0cb4a0c0a9ed.svg', 0),
(361, 'TH7du35P', 'rendalen.kommune.no', 'Rendalen', 'Innlandet', 3424, 0, '', 'Kommune', '136e59ff9403c5eda151b415378b0af69f1df7fd7c3b7db66ea1b7650b1ea3e1.svg', 0),
(362, 'mnD7LeGK', 'engerdal.kommune.no', 'Engerdal', 'Innlandet', 3425, 0, '', 'Kommune', 'a9b2139f1e55f4d085db8c01933f5d87dbb2c7a688e6dd0098d163e5aa1b87fd.svg', 0),
(363, 'jvJH0FC', 'tolga.kommune.no', 'Tolga', 'Innlandet', 3426, 0, '', 'Kommune', '42ccab10e0997a45b2b3c5bf68106b9797de912915f51e4f098b02092f461af9.svg', 0),
(364, 'DF3S9EPv', 'tynset.kommune.no', 'Tynset', 'Innlandet', 3427, 0, '', 'Kommune', 'abd28ae7696f0e161c08e937665c7c035afb8bf4e326c92bedd15300af6f8780.svg', 0),
(365, 'sULbfvPj', 'alvdal.kommune.no', 'Alvdal', 'Innlandet', 3428, 0, '', 'Kommune', '78d984d58e1c5e576f84f54eb0e46020c4c019d71b3c751922410dccddc3d4b5.svg', 0),
(366, 'EYVPgns1', 'folldal.kommune.no', 'Folldal', 'Innlandet', 3429, 0, '', 'Kommune', '8e62f0dca4fe75707f0cb2c72501605c6b0771fecaf01e9b5d60148beca39de1.svg', 0),
(367, '0KBzlAbz', 'os.kommune.no', 'Os', 'Innlandet', 3430, 0, '', 'Kommune', 'f4b0c2b562c8139a7be5fe5852a7a14d465647a87b891b17ddf04c50c3793962.svg', 0),
(368, 'kSfB5K19', 'dovre.kommune.no', 'Dovre', 'Innlandet', 3431, 0, '', 'Kommune', '0fbd49c16dc50155477db39a2c1b1d8ca3e23f34522a2857976a35d9c51fe120.svg', 0),
(369, 'enmeFayW', 'lesja.kommune.no', 'Lesja', 'Innlandet', 3432, 0, '', 'Kommune', '2226e3a6445fd4b71136f575d8ed2634545d6f7855e73ae671773c45362edb80.svg', 0),
(370, '5JlpMNxW', 'skjaak.kommune.no', 'Skjåk', 'Innlandet', 3433, 0, '', 'Kommune', '5472a4165937b0532c1aefe564c68c0e2c7b80325eef7553d254c823fef34ce1.svg', 0),
(371, 'VstRLan', 'lom.kommune.no', 'Lom', 'Innlandet', 3434, 0, '', 'Kommune', '6bbfdd56fa20b46374c0c9d052c798da5d352825815f0d1e430140303e5c9a99.svg', 0),
(372, 'p2RzzqLS', 'vaga.kommune.no', 'Vågå', 'Innlandet', 3435, 0, '', 'Kommune', '193eb5c734f558cdd04ab5d7c9d428134bd3a6196f4c297a7e61bb4fee6e6f77.svg', 0),
(373, 'Yic9FXGw', 'nord-fron.kommune.no', 'Nord-Fron', 'Innlandet', 3436, 0, '', 'Kommune', '40cbca6720d8dddc3f7df04d27b3e26066ae028e7647c48abc0d74451e7b8401.svg', 0),
(374, '6AIlDZD', 'sel.kommune.no', 'Sel', 'Innlandet', 3437, 0, '', 'Kommune', '94f0d456899f0b0b28b6146887b0d93c2b2793386c0d378059ea58b873c7a0fc.svg', 0),
(375, 'e3Ld1ltT', 'sor-fron.kommune.no', 'Sør-Fron', 'Innlandet', 3438, 0, '', 'Kommune', '5b177343c05aea9fdbd336b644e2d170552d8e31df25dba729008e9fc87cefb1.svg', 0),
(376, 'W1marhg', 'ringebu.kommune.no', 'Ringebu', 'Innlandet', 3439, 0, '', 'Kommune', '131deca0fbfb897152eb29e95fba16883e61dfe6881901a010fec3465bc7c016.svg', 0),
(377, 'u5XmC5XG', 'oyer.kommune.no', 'Øyer', 'Innlandet', 3440, 0, '', 'Kommune', 'f8ade01163c60512bbde919fe02b89b0c0754c75f48730cff8a430902bc46ac1.svg', 0),
(378, 'b9fxmRKY', 'gausdal.kommune.no', 'Gausdal', 'Innlandet', 3441, 0, '', 'Kommune', '1f92f6e3fb70608c2410a4bbc0eadbec114ad1e03f6fec5b8f352fac69c3f0d7.svg', 0),
(379, 'AivaRGH', 'ostre-toten.kommune.no', 'Østre Toten', 'Innlandet', 3442, 0, '', 'Kommune', '608c6f313084a77a3f78d30cc5a923ea4b0fccd13963a4dab42d25b12e9c39dc.svg', 0),
(380, 'XCZ8tSOM', 'vestre-toten.kommune.no', 'Vestre Toten', 'Innlandet', 3443, 0, '', 'Kommune', 'ce361a743815d907a46252ece068b9013f4cff5c7e49cb82ce215fcf6a18b3e8.svg', 0),
(381, 'AONmexNk', 'gran.kommune.no', 'Gran', 'Innlandet', 3446, 0, '', 'Kommune', '28313490c3f52aa99b31765d7ea2c16018b0a97d4a08e3cc0ba78158de7bc07a.svg', 0),
(382, 'xYNcz5z', 'sondre-land.kommune.no', 'Søndre Land', 'Innlandet', 3447, 0, '', 'Kommune', '5ab57eae225d2c6646b37960690c22705c88047969cce8c0ff75a670678544bb.svg', 0),
(383, 'AIthcwNc', 'nordre-land.kommune.no', 'Nordre Land', 'Innlandet', 3448, 0, '', 'Kommune', '5d40fb8aa1b419fddb75cf71f780ad7b90cf3df4c415d1b8d0733b0d29883102.svg', 0),
(384, '5fW1x6eL', 'sor-aurdal.kommune.no', 'Sør-Aurdal', 'Innlandet', 3449, 0, '', 'Kommune', 'cfc920e861e08f5c05c063460afe1f4e20f2b80f43fe79c02b01e94174285bdb.svg', 0),
(385, '6DqzOSQA', 'etnedal.kommune.no', 'Etnedal', 'Innlandet', 3450, 0, '', 'Kommune', '9b89b39f3fedb16d1677a93557a60c4b5af19a6277d7d96a2adab7a6029cfb9a.svg', 0),
(386, '6NjJMtZg', 'nord-aurdal.kommune.no', 'Nord-Aurdal', 'Innlandet', 3451, 0, '', 'Kommune', 'aba21a76e99284d51ab20299219a03ca12bb884d732eeed748755c7259f2ca93.svg', 0),
(387, '1zerz0Mf', 'vestre-slidre.kommune.no', 'Vestre Slidre', 'Innlandet', 3452, 0, '', 'Kommune', '22ffe7f901661b30439ae55f72c4de8404470938c14eae379f41d951c629b47f.svg', 0),
(388, 'ppMAX8RP', 'oystre-slidre.kommune.no', 'Øystre Slidre', 'Innlandet', 3453, 0, '', 'Kommune', '0897cfd1ed6a7b17c6a0bbbef45877595324fc03fb81c32415122851376aef89.svg', 0),
(389, 'IjMubKZd', 'vang.kommune.no', 'Vang', 'Innlandet', 3454, 0, '', 'Kommune', '575d98fb470b9ee5bec501d75c2bc4e2497418d847d48cace4a7bd7a84101104.svg', 0),
(390, 'NzqMB6JQ', 'horten.kommune.no', 'Horten', 'Vestfold og Telemark', 3801, 0, '', 'Kommune', '9b94e1efcc54f5a5e1b01bec89d142824c8ce09b9d797a440dee3b5bec3e583c.svg', 0),
(391, 'oGs7bxDm', 'holmestrand.kommune.no', 'Holmestrand', 'Vestfold og Telemark', 3802, 0, '', 'Kommune', 'acface35b545f652883ae749362179ddf0272d7ace9cf35ed51942ff8e969a79.svg', 0),
(392, 'ttwKBfyn', 'tonsberg.kommune.no', 'Tønsberg', 'Vestfold og Telemark', 3803, 0, '', 'Kommune', '16f528ace58d8bc811619d6719b9fcc581f54231ff2c492464b49dd49d4d22e3.svg', 0),
(393, 'fkt7Fu8A', 'sandefjord.kommune.no', 'Sandefjord', 'Vestfold og Telemark', 3804, 0, '', 'Kommune', 'a37a4dbf69446a20190b9c58c167a158aeb1d1ebfbebf9117c673ddcf9e5937c.svg', 0),
(394, 'HVp8IAgK', 'larvik.kommune.no', 'Larvik', 'Vestfold og Telemark', 3805, 0, '', 'Kommune', 'fd2670f6fb8b832a9e7fb1772c9b399ee2b50d72196e8c8263a7b1aa8de10626.svg', 0),
(395, 'lm8to6yz', 'porsgrunn.kommune.no', 'Porsgrunn', 'Vestfold og Telemark', 3806, 0, '', 'Kommune', 'f68bbbef2b4c8696553075b4baf454986cb76f3f7c670c6336d471e5878ba372.svg', 0),
(396, 'I7xEz3Hg', 'skien.kommune.no', 'Skien', 'Vestfold og Telemark', 3807, 0, '', 'Kommune', 'cf7cf512b44203a6555b1a08221b272992cf86ecd580069bf3249a0b47c7be87.svg', 0),
(397, '9BLZ8SUX', 'notodden.kommune.no', 'Notodden', 'Vestfold og Telemark', 3808, 0, '', 'Kommune', '5122f39e736e4bf561bcb85fd4f65146043031012383075d14c548297fa3e4f6.svg', 0),
(398, 'oqAI5yzv', 'faerder.kommune.no', 'Færder', 'Vestfold og Telemark', 3811, 0, '', 'Kommune', '0260a9e93e239769bf21b0d33c928ebe8da251f47359985ebbee2739fb323456.svg', 0),
(399, '7eqRzqwX', 'siljan.kommune.no', 'Siljan', 'Vestfold og Telemark', 3812, 0, '', 'Kommune', '2aaa9c9fe25716408bc73f33a35ba69184321fb57bb87ba9120e8e4065ede3a7.svg', 0),
(400, 'N2kQOBJ9', 'bamble.kommune.no', 'Bamble', 'Vestfold og Telemark', 3813, 0, '', 'Kommune', '03ea9794fbce02fe7dc9bbb27163c465a60eac431c39127e46b3ce4599d456d8.svg', 0),
(401, '9QhFFyqc', 'kragero.kommune.no', 'Kragerø', 'Vestfold og Telemark', 3814, 0, '', 'Kommune', '912ea58b9193b7b77a8552d0c14acb0eab4fb0e7168eb1c49d3335ab2ee2036f.svg', 0),
(402, '49LPCwAS', 'drangedal.kommune.no', 'Drangedal', 'Vestfold og Telemark', 3815, 0, '', 'Kommune', '7bbd236e31f7c4d027ff997ca3c2e4efc6bd6df045941d11d9eac6f9c9f0f616.svg', 0),
(403, 'QETJEDfm', 'nome.kommune.no', 'Nome', 'Vestfold og Telemark', 3816, 0, '', 'Kommune', '2fcc9983cfadac19c0549d6b30e2779ba3a4ade462225566a5960bedb6fb4f45.svg', 0),
(404, '0ThJ1B8', 'mt.kommune.no', 'Midt-Telemark', 'Vestfold og Telemark', 3817, 0, '', 'Kommune', '7076ee7078afc907b6648b0a27d7dff9644f4e850dd61ab71cf635b0d6f551e1.svg', 0),
(405, 'eF0GEuL', 'tinn.kommune.no', 'Tinn', 'Vestfold og Telemark', 3818, 0, '', 'Kommune', '5256e6ac7de4a9bde3a6e8eb3851e85094b4fd56c1c45a12fbe6243733426cb3.svg', 0),
(406, 'FHBTZbBp', 'hjartdal.kommune.no', 'Hjartdal', 'Vestfold og Telemark', 3819, 0, '', 'Kommune', 'a670a114719f61572d83fe0d7fbca617535c2491c8e5d46462ae658efc935f4c.svg', 0),
(407, '9jgu3mTB', 'seljord.kommune.no', 'Seljord', 'Vestfold og Telemark', 3820, 0, '', 'Kommune', 'b927b7bcc9fad43636d98b88ef4df223cbf4b26387a288b5ecc77c16ddc53ef1.svg', 0),
(408, 'UgZYw09M', 'kviteseid.kommune.no', 'Kviteseid', 'Vestfold og Telemark', 3821, 0, '', 'Kommune', '51471da981995077ea12bafdc273e933a459c5b6edd48baa811b0680dabdea8b.svg', 0),
(409, 'l19Lqtfq', 'nissedal.kommune.no', 'Nissedal', 'Vestfold og Telemark', 3822, 0, '', 'Kommune', '3213a6d18b3c922f680a76066bbbef0d56378ca456d73bf176d34617ce337945.svg', 0),
(410, 'yUKvwELz', 'fyresdal.kommune.no', 'Fyresdal', 'Vestfold og Telemark', 3823, 0, '', 'Kommune', '3093eed153b5e828f59ef354ddde7bc90ae8f4721953894a09b82f4906c8a7bb.svg', 0),
(411, 'JESWiyNF', 'tokke.kommune.no', 'Tokke', 'Vestfold og Telemark', 3824, 0, '', 'Kommune', '183881358f53be2ab8b646e4e07a354dc6d2f2cce63eeef240281280ba01f900.svg', 0),
(412, 'oAygYWj2', 'vinje.kommune.no', 'Vinje', 'Vestfold og Telemark', 3825, 0, '', 'Kommune', '4b1bcb0e8b8db3d25218185c729765472eee225fe41b4c6df34c6ad77571ed6d.svg', 0),
(413, 'cmK51L4H', 'risor.kommune.no', 'Risør', 'Agder', 4201, 0, '', 'Kommune', '28bae27cea194ae804fcf37d00aa88cb6ef6b75f97982123c395a28076accb1a.svg', 0),
(414, 'llw7LVmy', 'grimstad.kommune.no', 'Grimstad', 'Agder', 4202, 0, '', 'Kommune', 'd501afb1f6726e749a40783402db48e95de7d513f64b4f14da1388a14231d0ab.svg', 0),
(415, '0RJlsKe', 'arendal.kommune.no', 'Arendal', 'Agder', 4203, 0, '', 'Kommune', 'f6f0990386422cb9e48483c1b114083e77ba7e47bd56cdba0c3c8c1edc5d6d55.svg', 0),
(416, 'hIa5bHBi', 'kristiansand.kommune.no', 'Kristiansand', 'Agder', 4204, 0, '', 'Kommune', 'c438aa7d054d515881274802cdcdd6b48401e4a94d511a84516886225864f226.svg', 0),
(417, '5E470JjN', 'lindesnes.kommune.no', 'Lindesnes', 'Agder', 4205, 0, '', 'Kommune', '80dc366e12520db23bde42f14065380f1f25a947ec72fd76546479954fae52b3.svg', 0),
(418, 'eoGbNGDC', 'farsund.kommune.no', 'Farsund', 'Agder', 4206, 0, '', 'Kommune', '66f2907cb4f1f4952ec5033de655e742541eb40523cb3c2cb92dbcd698f721ec.svg', 0),
(419, 'gHCGReHP', 'flekkefjord.kommune.no', 'Flekkefjord', 'Agder', 4207, 0, '', 'Kommune', '4a508f3b1ebc08be8af67515523b17b12c0f730d4eee47a857b711a766ffa4b7.svg', 0),
(420, 'UIvlu0cr', 'gjerstad.kommune.no', 'Gjerstad', 'Agder', 4211, 0, '', 'Kommune', 'c543d6cff94b907c5f42cf8db8d9f67607c3a40721363c3a9f74bbabaf67374f.svg', 0),
(421, 'SOlSboNP', 'vegarshei.kommune.no', 'Vegårshei', 'Agder', 4212, 0, '', 'Kommune', '400cade5e4a6949ddef9f551639dbb013983972bb66e402ab3a429c6466e053f.svg', 0),
(422, 'TcpwUn9n', 'tvedestrand.kommune.no', 'Tvedestrand', 'Agder', 4213, 0, '', 'Kommune', 'f6053d50cf0b8093a83c95c9bb95363b8a719b2f59724bc9a77e7306b591f8b0.svg', 0),
(423, 'ZuJl7Tat', 'froland.kommune.no', 'Froland', 'Agder', 4214, 0, '', 'Kommune', 'aeb36000abc163cdf4ec37f201caffd25721bbd764eaa05358e01240ac95af6e.svg', 0),
(424, '1fgls6Z', 'lillesand.kommune.no', 'Lillesand', 'Agder', 4215, 0, '', 'Kommune', '7d345e20ca704712c791e863d1e4bf9e797dbe655b3bb18147d93de2c38054d7.svg', 0),
(425, 'EeEKuG8', 'birkenes.kommune.no', 'Birkenes', 'Agder', 4216, 0, '', 'Kommune', '345c288f362b219a79ab3b01aeec5abfef473376b1f93b5c02b05eb3d7a9af1c.svg', 0),
(426, 'ggyN8V0J', 'amli.kommune.no', 'Åmli', 'Agder', 4217, 0, '', 'Kommune', 'b6d3f06e814ea4ffaa2843467f7a22217f7627de4f4281ebd10b24925c9dcb4c.svg', 0),
(427, 'ROzuTgF1', 'iveland.kommune.no', 'Iveland', 'Agder', 4218, 0, '', 'Kommune', '51456a6ccc604b42b54fdd55c10dc9031e3749343d0e286373c7f683227ef2b6.svg', 0),
(428, 'mxtwj561', 'e-h.kommune.no', 'Evje og Hornnes', 'Agder', 4219, 0, '', 'Kommune', '7330f53e8147b2119d5f8a1a0d7f62131c67167dbdb47833d15b060099902260.svg', 0),
(429, 'E8LtJuMG', 'bygland.kommune.no', 'Bygland', 'Agder', 4220, 0, '', 'Kommune', '325213e953a8d55a03beb4f7c5e9325ffa5b0b86d9c11d57a746166825938dc1.svg', 0),
(430, 'ZGC3lXG7', 'valle.kommune.no', 'Valle', 'Agder', 4221, 0, '', 'Kommune', 'b004e8b6220357953182efc8aca207cecbcdd2f104c660f026e19f997331b424.svg', 0),
(431, 'tWEp2y1v', 'bykle.kommune.no', 'Bykle', 'Agder', 4222, 0, '', 'Kommune', '6a572d1aef70e5f21849b07f923e833641b69bb845de51a8257b01f9f312a5b4.svg', 0),
(432, 'WVfLhBww', 'vennesla.kommune.no', 'Vennesla', 'Agder', 4223, 0, '', 'Kommune', '519f826e2c596f60e11b46dd9f3fcf306ff7aec8099e86825cd6e807a4370c2e.svg', 0),
(433, 'aUMJNpEo', 'aseral.kommune.no', 'Åseral', 'Agder', 4224, 0, '', 'Kommune', 'c2ca0275b85114892f5f66f132f9cc5da2d4376faf72a9b59f70576c34be551b.svg', 0),
(434, 'yp1MCDqu', 'lyngdal.kommune.no', 'Lyngdal', 'Agder', 4225, 0, '', 'Kommune', '28d12b8d699586f7c8f0e600cf746366088e851f8788687c9803e9e3883792e4.svg', 0),
(435, '6L1XyQ1l', 'haegebostad.kommune.no', 'Hægebostad', 'Agder', 4226, 0, '', 'Kommune', '2ac70f926eed9feb80d5cc295bd93a6f331ab63428e727883e9dbcd0a169186b.svg', 0),
(436, 'GUYVBaZi', 'kvinesdal.kommune.no', 'Kvinesdal', 'Agder', 4227, 0, '', 'Kommune', 'e377cb8830168589db38f7aa54f47a33b6fd647efcfb07aa95c1ae912f8c1885.svg', 0),
(437, 'XgUkACFE', 'sirdal.kommune.no', 'Sirdal', 'Agder', 4228, 0, '', 'Kommune', '74100f993525dbca1b488a5cc83d3cd46c09371355fe5df9d926b4e277cbfe62.svg', 0),
(438, 'NsDpGdVV', 'eigersund.kommune.no', 'Eigersund', 'Rogaland', 1101, 0, '', 'Kommune', 'a6e6c0caba9d92e671189d2884a28673687d33783fed813e0f6408f08f5e1bb2.svg', 0),
(439, 'E8H1d2cw', 'stavanger.kommune.no', 'Stavanger', 'Rogaland', 1103, 0, '', 'Kommune', 'df6b0c02ab9a54c2ca888adb14b45ab6bc7bdec878ee7fa821b1763ccce94160.svg', 0),
(440, 'WKYfSjNm', 'haugesund.kommune.no', 'Haugesund', 'Rogaland', 1106, 0, '', 'Kommune', 'e6fe8be419dd3bfde57b399bf62a6b23989a8720aa19ceab12606ab604842b7a.svg', 0),
(441, 'ucOvr4am', 'sandnes.kommune.no', 'Sandnes', 'Rogaland', 1108, 0, '', 'Kommune', 'da3866698c3e2b19f2fb88913e3064d1a7abba100bdae8c86b2306fcf987729b.svg', 0),
(442, 'sQ69guEW', 'sokndal.kommune.no', 'Sokndal', 'Rogaland', 1111, 0, '', 'Kommune', '32aadb9a9354131aee8028688ae5810db765cd044fe074dc2651c5099bb77fa1.svg', 0),
(443, 'DUYWowx1', 'lund.kommune.no', 'Lund', 'Rogaland', 1112, 0, '', 'Kommune', '13703c235ddddb7dd19629cdf5e35c3b821de681ee6140d133aa0453276a7b1b.svg', 0),
(444, 'iAKu9VJF', 'bjerkreim.kommune.no', 'Bjerkreim', 'Rogaland', 1114, 0, '', 'Kommune', 'ddf10fccfdfe4b1fb4f4984f21f433622ac62ec53c34564f9bd6213b12dc20db.svg', 0),
(445, 'bgLdwjYX', 'ha.kommune.no', 'Hå', 'Rogaland', 1119, 0, '', 'Kommune', '9fa4754a5ae911082e14023f87718372b213b73b38ef294444dd4b9c13845d79.svg', 0),
(446, 'KCtxUXG', 'klepp.kommune.no', 'Klepp', 'Rogaland', 1120, 0, '', 'Kommune', 'b6a3254af1280a59943d3933dc524b745bb0d69a612f52afa8d526d660f02814.svg', 0),
(447, '7P8NgJHu', 'time.kommune.no', 'Time', 'Rogaland', 1121, 0, '', 'Kommune', '70221167e2c04c0cc75f2f3aa3039139e74f1219d16c91fc6fb58ce1a3fa171b.svg', 0),
(448, '8FQlLt8P', 'gjesdal.kommune.no', 'Gjesdal', 'Rogaland', 1122, 0, '', 'Kommune', '3719c4ec4157aaa321fffb0568ea2a89077f5a4a4b72639aad4a2f1815c5eb12.svg', 0),
(449, 'wZhUfy7d', 'sola.kommune.no', 'Sola', 'Rogaland', 1124, 0, '', 'Kommune', 'c041e5cef0e888eada802446c037893fe2755eaa9ec5e73fba71630665731576.svg', 0),
(450, 'AgzDVGTA', 'randaberg.kommune.no', 'Randaberg', 'Rogaland', 1127, 0, '', 'Kommune', '2754705e3574b2cef21897a29960cd879e1a0633825714e9c86cff81d6e6a3d5.svg', 0),
(451, '1nCekNzg', 'strand.kommune.no', 'Strand', 'Rogaland', 1130, 0, '', 'Kommune', '350ab93acd12b7add169ee098c35041074f97cc25f2354709260db9deaa91916.svg', 0),
(452, 'LgCFJjED', 'hjelmeland.kommune.no', 'Hjelmeland', 'Rogaland', 1133, 0, '', 'Kommune', '6964fa963b687fa813da88ecc1d6a0fcbad5d56d472df36fdc5acd409f352165.svg', 0),
(453, 'w3rHxuoy', 'suldal.kommune.no', 'Suldal', 'Rogaland', 1134, 0, '', 'Kommune', '0dc7e8c58f3eb72a3ab42ff5588e2fda8c05a35019b9fc0ec55657bc2696da75.svg', 0),
(454, 'fwhKY9IG', 'sauda.kommune.no', 'Sauda', 'Rogaland', 1135, 0, '', 'Kommune', 'a74b216f128822e81e12bf4f5c916fbe0ea15d5526e246a0df29de684549d835.svg', 0),
(455, 'JBIn55KH', 'kvitsoy.kommune.no', 'Kvitsøy', 'Rogaland', 1144, 0, '', 'Kommune', '86dfdf412b25d902ffcbdae48dc8eea8f3653a108c88f0a7d35294411b61672f.svg', 0),
(456, 'Id04pzY', 'bokn.kommune.no', 'Bokn', 'Rogaland', 1145, 0, '', 'Kommune', '70f1ef33ce1a627a83a463ca5c3867ba9a3f752fae94cacb36c0c98f83fa8557.svg', 0),
(457, 'it10wwtg', 'tysver.kommune.no', 'Tysvær', 'Rogaland', 1146, 0, '', 'Kommune', '5e18b6436b4be3e1c1a512625f12dd1a1c383f17297e6aaf11a76fdf9c1628b0.svg', 0),
(458, 'Bb0WKQFe', 'karmoy.kommune.no', 'Karmøy', 'Rogaland', 1149, 0, '', 'Kommune', '5b7f13afb85cb5a0eede71912c6611221c8ac3dbc0c3139c6771b64c0ec0d94c.svg', 0),
(459, 'y6mUAaUI', 'utsira.kommune.no', 'Utsira', 'Rogaland', 1151, 0, '', 'Kommune', '0c5dcab1f736148f2131691350fb5a807fd5459a043af6e37b09b733d173ce46.svg', 0),
(460, 'gJD6dqsJ', 'vindafjord.kommune.no', 'Vindafjord', 'Rogaland', 1160, 0, '', 'Kommune', '17f08b4b1ae8ba22549bc16b1ee8b12ba6acd6f795ae2e9cd9a57b5a4552a77c.svg', 0),
(461, 'tTsdupP3', 'bergen.kommune.no', 'Bergen', 'Vestland', 4601, 0, '', 'Kommune', 'b328079e3472f2df234599326abfb3b8061c1a98348dcca40af7462bc238bd97.svg', 0),
(462, '5u3SZypo', 'kinn.kommune.no', 'Kinn', 'Vestland', 4602, 0, '', 'Kommune', 'f7dc099b420dbfae11c83daa88a3b51af16b906327d4330c34abf59ab881b82e.svg', 0),
(463, 'v2PKnPEY', 'etne.kommune.no', 'Etne', 'Vestland', 4611, 0, '', 'Kommune', '491b0836cfbeb8eea4f8a91fd9c458845ec6c90a7998a2406b713de9ae88c9ec.svg', 0),
(464, '6CryZHMn', 'sveio.kommune.no', 'Sveio', 'Vestland', 4612, 0, '', 'Kommune', 'a1a544dd36f046ebab30b1062fe5e7657f4a7d687b7054371d4fccf3377a75df.svg', 0),
(465, 'q8vxS5cK', 'bomlo.kommune.no', 'Bømlo', 'Vestland', 4613, 0, '', 'Kommune', '1d2bd6c5c3787b85bc4bfe776df52368488d0276cd8da78944651ef431c13679.svg', 0),
(466, 'bOdLZxji', 'stord.kommune.no', 'Stord', 'Vestland', 4614, 0, '', 'Kommune', 'b600870045ea869da01779d7203e7b3c5cad32a1d146637a8d64c6c18667acef.svg', 0),
(467, 'N1CMtKkf', 'fitjar.kommune.no', 'Fitjar', 'Vestland', 4615, 0, '', 'Kommune', '9bafa84d45f6c078ed3442423e65ec0ae3408a5d63f62eb2f871c4a4df96aae3.svg', 0),
(468, 'G2G0M8Ie', 'tysnes.kommune.no', 'Tysnes', 'Vestland', 4616, 0, '', 'Kommune', 'ddbea1a9e675f7cdce1bf7059cfdb7bfd55029345703b76ca14a0a459ce3343e.svg', 0),
(469, 'MsyoND5d', 'kvinnherad.kommune.no', 'Kvinnherad', 'Vestland', 4617, 0, '', 'Kommune', 'a31a8fa16325f5d806566d60c4f941daf9d70d0810794f7498b0ab065cf16abd.svg', 0),
(470, 'UNKdYYS8', 'ullensvang.kommune.no', 'Ullensvang', 'Vestland', 4618, 0, '', 'Kommune', 'ba0bb31537be92f887e2f57c0fc12ccda1534e7c166d9eb1d1e05bcda74e8616.svg', 0),
(471, 'l0cS3cSh', 'eidfjord.kommune.no', 'Eidfjord', 'Vestland', 4619, 0, '', 'Kommune', 'ab91710d97fa7404ac23a6160f428cf005a1f0fe6e72db666e52bddbfed22698.svg', 0),
(472, '4dF72dUv', 'ulvik.kommune.no', 'Ulvik', 'Vestland', 4620, 0, '', 'Kommune', 'efb0f8624532b249e2f483021227a0f5dcb60bbf8f184018717c60e15a0ddd31.svg', 0),
(473, 'CvQOCJGH', 'voss.kommune.no', 'Voss', 'Vestland', 4621, 0, '', 'Kommune', '06fbdf3f3dfd8c250966362ec249004580f0f6123a3db922c76cee8882da58af.svg', 0),
(474, 'N6NET1q8', 'kvam.kommune.no', 'Kvam', 'Vestland', 4622, 0, '', 'Kommune', 'bfd9d5ab4b675cc6b0ec022ba4f30bf62dd77e0baeef54c6affb96a93b1db41e.svg', 0),
(475, '0wMk3w56', 'samnanger.kommune.no', 'Samnanger', 'Vestland', 4623, 0, '', 'Kommune', '59e83f1c8302438bed32b6f7efb901d84a8f7a39a99f5e3ee335657dc013b635.svg', 0),
(476, 'HPccxgh5', 'bjornafjorden.kommune.no', 'Bjørnafjorden', 'Vestland', 4624, 0, '', 'Kommune', '7f311319ba9d21197c74fc82d46e21ef77145cd3a895847520b090fc02cba8b9.svg', 0),
(477, '4VrSz5Cc', 'austevoll.kommune.no', 'Austevoll', 'Vestland', 4625, 0, '', 'Kommune', '8c2e1fdcb7ab5d2a1cc8307973bf460c4e2609376c8f9f2d64dd5c6694c333f0.svg', 0),
(478, 'sqRakOgj', 'oygarden.kommune.no', 'Øygarden', 'Vestland', 4626, 0, '', 'Kommune', 'f5ed5ee2c212f26b062fb13d0889f68ac7a241808a8560ad3c20efc6ad7fc9ae.svg', 0),
(479, 'vKus2EX', 'askoy.kommune.no', 'Askøy', 'Vestland', 4627, 0, '', 'Kommune', '525d35d8c8dccae1eafbd9d8fc331e086771a4e8d2559947ebb4641679d621fd.svg', 0),
(480, 'Cr3q8ie', 'vaksdal.kommune.no', 'Vaksdal', 'Vestland', 4628, 0, '', 'Kommune', 'a42ffe7e43835016bb489558a8deba9624069be460f7b546469735edf5242d0b.svg', 0),
(481, 'xlUeQFy', 'modalen.kommune.no', 'Modalen', 'Vestland', 4629, 0, '', 'Kommune', '9cf4279cc6cc3fedb7114581c6ac2416c989effee19fcc6da8cac4ea0b8cad30.svg', 0),
(482, 'ufDv82UM', 'osteroy.kommune.no', 'Osterøy', 'Vestland', 4630, 0, '', 'Kommune', '7f80d2f33bfb601cb7d09f12d09609a05138471538bbc8fb251e5aa352c26863.svg', 0),
(483, 'SkmIt1l0', 'alver.kommune.no', 'Alver', 'Vestland', 4631, 0, '', 'Kommune', '2efe7f41d872e311ac9fbbcfcdb4241bf572c238763bbccf1eb599941d89036a.svg', 0),
(484, '4jaVtgi1', 'austrheim.kommune.no', 'Austrheim', 'Vestland', 4632, 0, '', 'Kommune', '002c9f316d2c132c939141c93a24c5d8d096e4dc16e76342f064dc36bba369d9.svg', 0),
(485, '9w665Zpv', 'fedje.kommune.no', 'Fedje', 'Vestland', 4633, 0, '', 'Kommune', 'c6a1bcba7cfa5a51474ab6f1a2b8e687acba207508e23d37bb2db7791588a0a9.svg', 0),
(486, 'xmYPDGvD', 'masfjorden.kommune.no', 'Masfjorden', 'Vestland', 4634, 0, '', 'Kommune', '50a5eedb27ddd7c86f98569a46a6f4557e8cbf672fca361478daafa131659a92.svg', 0),
(487, 'X8rKWZg2', 'gulen.kommune.no', 'Gulen', 'Vestland', 4635, 0, '', 'Kommune', '56b5f93465d5a38836d2c2d23e32c50e0d1086cccd330526f1e9d009b178b519.svg', 0),
(488, 'aH4upjN2', 'solund.kommune.no', 'Solund', 'Vestland', 4636, 0, '', 'Kommune', '2fda1fc0af43f3741bfe2277f3db9e7df682ad6e1da2200efcfcf4b1af9d0440.svg', 0),
(489, 'oRVq0QCh', 'hyllestad.kommune.no', 'Hyllestad', 'Vestland', 4637, 0, '', 'Kommune', '4412632dc859d06a4163fc071786beed79f96192235d7b87f14937c3ffbcfcfd.svg', 0),
(490, 'Zy8gatly', 'hoyanger.kommune.no', 'Høyanger', 'Vestland', 4638, 0, '', 'Kommune', 'fccbc39c67e13a96191dc5c34f084ae29d75c39bf57074e9006f3720ee744f21.svg', 0),
(491, '1vRY1DC0', 'vik.kommune.no', 'Vik', 'Vestland', 4639, 0, '', 'Kommune', '0921e3cdaec2c3284461ccf2c4a20fab9d18d96edf10e122e98e19285a4e9eb2.svg', 0),
(492, 'oIBD3Mop', 'sogndal.kommune.no', 'Sogndal', 'Vestland', 4640, 0, '', 'Kommune', '100ddd1c018cdd68e4e4c086cf0ed72d351e613804c7b25d3b5c1ee1cb7b6ade.svg', 0),
(493, 'QqJzQzjq', 'aurland.kommune.no', 'Aurland', 'Vestland', 4641, 0, '', 'Kommune', 'ea10514c4b216d599b5b48bf9d9e352dc844373b3b6aa2b023f27d20ad15815b.svg', 0),
(494, 'zFHP4p7j', 'laerdal.kommune.no', 'Lærdal', 'Vestland', 4642, 0, '', 'Kommune', '6f4a1f9bd39799647d043c39d3cd6fce47442ddd732088cf1d83f152bfc56bb3.svg', 0),
(495, 'IqgoCdrp', 'ardal.kommune.no', 'Årdal', 'Vestland', 4643, 0, '', 'Kommune', '6a25b6e64212b4f31eeb47e33afab112c2eb26b1509529806cbe90853a8630ff.svg', 0),
(496, 'TN4XQweU', 'luster.kommune.no', 'Luster', 'Vestland', 4644, 0, '', 'Kommune', '0207aa07f95852345a3e22d6e7cbfcbfe8596b78c6d741f196fd7fa39ee0debb.svg', 0),
(497, 'LvujkZSo', 'askvoll.kommune.no', 'Askvoll', 'Vestland', 4645, 0, '', 'Kommune', 'bbe0d18fedd5fe4fd5cec47839872f75fffc9f44bc10415624c4631a5e93d55e.svg', 0),
(498, 'sRpF1DN0', 'fjaler.kommune.no', 'Fjaler', 'Vestland', 4646, 0, '', 'Kommune', '14fc044208ffe115ddba8450f0498265ed7a0119144a7973ba865b4f075f6b7d.svg', 0),
(499, 'GnRNXktk', 'sunnfjord.kommune.no', 'Sunnfjord', 'Vestland', 4647, 0, '', 'Kommune', '31ff789f5b62c51e9a519b4959abd88ed9127eb2427793077db1c26a643fa51e.svg', 0),
(500, 'WLsV5G1N', 'bremanger.kommune.no', 'Bremanger', 'Vestland', 4648, 0, '', 'Kommune', '3ec85f51c713162e39c26f2c9296c77bdd364eff1104f4bca0622e30f2c5a5a5.svg', 0),
(501, 'xGkaFHGb', 'stad.kommune.no', 'Stad', 'Vestland', 4649, 0, '', 'Kommune', '6d5bfc51a804d857ae6ddb381798f5e8e9353910063cbb8793291449d59f95ed.svg', 0),
(502, 'iAC88jaR', 'gloppen.kommune.no', 'Gloppen', 'Vestland', 4650, 0, '', 'Kommune', '06a6dd10fff948b654bf8d43b0a1568c15785396621d3805f7b39382e7f236d2.svg', 0),
(503, 'eZgBSApw', 'stryn.kommune.no', 'Stryn', 'Vestland', 4651, 0, '', 'Kommune', '8dc01ec1b48d991564213ebc3b3d77f3a84fc270d22f1213abef8c5365bf6bc1.svg', 0),
(504, 'oyixsGE', 'kristiansund.kommune.no', 'Kristiansund', 'Møre og Romsdal', 1505, 0, '', 'Kommune', 'ff11e581bffc89a4eec8fccd58202039441597df16c290d46e7265525782400c.svg', 0),
(505, 'pLn05WpW', 'molde.kommune.no', 'Molde', 'Møre og Romsdal', 1506, 0, '', 'Kommune', 'ecba644ce91b351429bc85187651cd6d413e7e221e3cc7afba87b88ac92d3737.svg', 0),
(506, 'XNz9tw6', 'alesund.kommune.no', 'Ålesund', 'Møre og Romsdal', 1507, 0, '', 'Kommune', '71dff3a1de60966d26908cb02ee630acbfdb2a9ebb3a93fcb5fb5e1aebb49951.svg', 0),
(507, 'iR0Rrufk', 'vanylven.kommune.no', 'Vanylven', 'Møre og Romsdal', 1511, 0, '', 'Kommune', '1bd76738b5a634b75fea0e6cc7fd051beb52ee27e3417bbfc08b39212dbbfaba.svg', 0),
(508, 'U3nj5gNs', 'sande-mr.kommune.no', 'Sande (Møre og Romsdal)', 'Møre og Romsdal', 1514, 0, '', 'Kommune', 'ac6713d35b685f5ede52f5c6dbcf41e894bb910b1c0b4fcca8bf600d90060540.svg', 0),
(509, 'X6qeFpW', 'heroy.kommune.no', 'Herøy (Møre og Romsdal)', 'Møre og Romsdal', 1515, 0, '', 'Kommune', '3e7f5dd739f74ddc2692b7da64ac987ca4a446ee45a46dfd9e6f88bcca7ca53a.svg', 0),
(510, 'O37rrXVP', 'ulstein.kommune.no', 'Ulstein', 'Møre og Romsdal', 1516, 0, '', 'Kommune', '07eb771d4c000502e0a741cde0f58f3bf8b9dfe6fc6e990c262b741c8b851457.svg', 0),
(511, 'MhkBsmsz', 'hareid.kommune.no', 'Hareid', 'Møre og Romsdal', 1517, 0, '', 'Kommune', '1554e7a3cbc56e4529c4ef84f10db950e73aaf1aead5dd4184f0bcfae608ed9f.svg', 0),
(512, 'gyk8QtI8', 'orsta.kommune.no', 'Ørsta', 'Møre og Romsdal', 1520, 0, '', 'Kommune', 'd1f2c14ce30f903ba15a050a245842fad708586c3e6afdacc3d6f89f9c4f36b3.svg', 0),
(513, 'Q2FstvA3', 'stranda.kommune.no', 'Stranda', 'Møre og Romsdal', 1525, 0, '', 'Kommune', 'a5eb489f7545b131aafd2bbfbeb5b2851aa5fed12b768a75d2166b3a7a15310d.svg', 0),
(514, 'srVevThd', 'sykkylven.kommune.no', 'Sykkylven', 'Møre og Romsdal', 1528, 0, '', 'Kommune', '6dcdae61374efb1653db7d24aa609f0ccf3666562444cc3ec51fe1e5c6a8d26b.svg', 0),
(515, 'DkeDqiUd', 'sula.kommune.no', 'Sula', 'Møre og Romsdal', 1531, 0, '', 'Kommune', 'c91f223aeecd7c7b1fa5b926ab57227697d7b4cd179c60c6e8f4d9cacfc4319c.svg', 0),
(516, 'yGAkfoI4', 'giske.kommune.no', 'Giske', 'Møre og Romsdal', 1532, 0, '', 'Kommune', '771a7008b05f315b552bf6e8858706490ce1ac175350c6e5ed4c380a1bd1f55c.svg', 0),
(517, 'F4v0ZPKe', 'vestnes.kommune.no', 'Vestnes', 'Møre og Romsdal', 1535, 0, '', 'Kommune', '4231d541772bd98c99504e6f118545c4872fcb7d43e5e5d9a23ebbf283ef50f2.svg', 0),
(518, '6qFeBVNW', 'rauma.kommune.no', 'Rauma', 'Møre og Romsdal', 1539, 0, '', 'Kommune', '5a3747c65a77f7df608f5fcf52772e2eb5a28a2028459e53f997d67175f24791.svg', 0),
(519, 'irG7HR4', 'aukra.kommune.no', 'Aukra', 'Møre og Romsdal', 1547, 0, '', 'Kommune', '09341146d26f153456597be6d448c948b240b90a540a01602c8971ad37f4ca5f.svg', 0),
(520, 'SotiOKH0', 'averoy.kommune.no', 'Averøy', 'Møre og Romsdal', 1554, 0, '', 'Kommune', '964c415bae231c788b3a17f6a63ea2c2338c609b3578fc7f4691b35e4996201f.svg', 0),
(521, 'VVjJ9Ldq', 'gjemnes.kommune.no', 'Gjemnes', 'Møre og Romsdal', 1557, 0, '', 'Kommune', 'ab4dab8c0cc17eed972441aba6e23b0110885245f41612b0371e0df60c5aab40.svg', 0),
(522, 'WMQ4T8NL', 'tingvoll.kommune.no', 'Tingvoll', 'Møre og Romsdal', 1560, 0, '', 'Kommune', '27365b7ad4720f3b71ee4fce9a9ec3cc06d730a74c9eb5ca876f6b5401357117.svg', 0),
(523, 'zGXvT4Xa', 'sunndal.kommune.no', 'Sunndal', 'Møre og Romsdal', 1563, 0, '', 'Kommune', '92abe1b95a2e3a04bec91cf679508cbe324df7f7780ca4ce87a23f57cdc1ffb1.svg', 0),
(524, 'hASfR2Kh', 'surnadal.kommune.no', 'Surnadal', 'Møre og Romsdal', 1566, 0, '', 'Kommune', 'd880cb59693842983365ff8cd7c868d0a51fd291210ada0b2e3563e22fa736b0.svg', 0),
(525, '68PUSD08', 'smola.kommune.no', 'Smøla', 'Møre og Romsdal', 1573, 0, '', 'Kommune', '48845a1a9578fdb02c5dfdfa3c97715f6ba24bdbabd9dbc1518f9d6e878f2d42.svg', 0),
(526, '8FVokM7h', 'aure.kommune.no', 'Aure', 'Møre og Romsdal', 1576, 0, '', 'Kommune', '3919c0fc2763ebcd62b362b4432fa7fbf2651cd87650d58c02738677f6fa04fe.svg', 0),
(527, 'H130tl8K', 'volda.kommune.no', 'Volda', 'Møre og Romsdal', 1577, 0, '', 'Kommune', '9afc2dd425c2be42a415d87932ff29e4544f6d013c27f8dd70f6d7edb4d85ea6.svg', 0),
(528, 'khgQtZie', 'fjord.kommune.no', 'Fjord', 'Møre og Romsdal', 1578, 0, '', 'Kommune', '86bdaa65ec81eb5545c098534a8abb4d2bd6d012aca40549965781c8ebeb24aa.svg', 0),
(529, 'LXwNkSmT', 'hustadvika.kommune.no', 'Hustadvika', 'Møre og Romsdal', 1579, 0, '', 'Kommune', '697b8f0f5a5ed0fd117cb829f423d1d6bda6d2fc13eb89327b6d6aff330b362e.svg', 0),
(530, 'XxH0pWAM', 'trondheim.kommune.no', 'Trondheim', 'Trøndelag', 5001, 0, '', 'Kommune', 'e45b49567c1f0f0b058dbf99e6489937b01613890b08f90d7f4c285f2d5ee88d.svg', 0),
(531, 'mTfknRTt', 'steinkjer.kommune.no', 'Steinkjer', 'Trøndelag', 5006, 0, '', 'Kommune', '7671ada4ef29d0ee89edafca9c380cc6a605ee16e18b4ab8be1e97d85694de09.svg', 0),
(532, 'mu1wV9Gd', 'namsos.kommune.no', 'Namsos', 'Trøndelag', 5007, 0, '', 'Kommune', 'd778077d9fb1c33955bac2a73ef56f8604a6c8fcc3ffe369d1b240624269cdcb.svg', 0),
(533, 'CLlXZ37S', 'froya.kommune.no', 'Frøya', 'Trøndelag', 5014, 0, '', 'Kommune', 'a00211bf93b4b16c54ac654c5edc4e8d3e7ec15235faacd9e3e2a3601402492a.svg', 0),
(534, 'GC3I5Wq4', 'osen.kommune.no', 'Osen', 'Trøndelag', 5020, 0, '', 'Kommune', 'd7efadcbeade1fda96d9dc2e19d204b516cdb59969992845f684b5794410a8a3.svg', 0),
(535, 'leabhmLI', 'oppdal.kommune.no', 'Oppdal', 'Trøndelag', 5021, 0, '', 'Kommune', 'dde97ec6ff4a5059894cc54c1b05eef2d41ef50284aef133f34ad4b6c78c8bb4.svg', 0),
(536, 'mPrVyyAN', 'rennebu.kommune.no', 'Rennebu', 'Trøndelag', 5022, 0, '', 'Kommune', '6c916f2a034362704c0235c2ad0653c546a36e0ebeee3e8bc5f947c28ced12ca.svg', 0),
(537, '0VSBbYRb', 'roros.kommune.no', 'Røros', 'Trøndelag', 5025, 0, '', 'Kommune', 'a06be4a480eb913f53345643d6f6bd5388cb6f930c5482d0c8fb04501797f88f.svg', 0),
(538, '3prn8tp', 'holtalen.kommune.no', 'Holtålen', 'Trøndelag', 5026, 0, '', 'Kommune', '7eb9c45870c1b129e9115a0a1602cbb0e4ba345043f7adecc98c79d5beafc9ce.svg', 0),
(539, 'ZKZRjhUR', 'mgk.no', 'Midtre Gauldal', 'Trøndelag', 5027, 0, '', 'Kommune', 'a598b0cb1412038306de9164377b4353f7b090ea8050bb113ab8230f947d5d61.svg', 0),
(540, 'lCmyZnM', 'melhus.kommune.no', 'Melhus', 'Trøndelag', 5028, 0, '', 'Kommune', '9e65fe1cdbd219e9d80eadf20d9cfa5452000218b155ada318b4077de435e2c3.svg', 0),
(541, 'oaNxP96', 'skaun.kommune.no', 'Skaun', 'Trøndelag', 5029, 0, '', 'Kommune', 'b66cbb1f0d65135ef99d8dd31b6f360a617e6d9f443b62cd83edc18acf0e9956.svg', 0),
(542, 'akc5yyUG', 'malvik.kommune.no', 'Malvik', 'Trøndelag', 5031, 0, '', 'Kommune', '2669a01cac8a6e3ebf64d719782c97186bc350978a27a2c185b7cbc8c50df7e8.svg', 0),
(543, 'iCL46CY', 'selbu.kommune.no', 'Selbu', 'Trøndelag', 5032, 0, '', 'Kommune', '917c049b1b4f24e6d7609e5b32dfbcdb1db84394aeb79a857912bdbf0d72dbab.svg', 0),
(544, 'VPWTGh51', 'tydal.kommune.no', 'Tydal', 'Trøndelag', 5033, 0, '', 'Kommune', '5844fa2507bcb0d59743c21d68e3f14c58130092e600dacf390d40ec17fa1b18.svg', 0),
(545, 'l6KLXKWn', 'meraker.kommune.no', 'Meråker', 'Trøndelag', 5034, 0, '', 'Kommune', '1aec4eba8ad14d5f2b896183d22f6ec655660789d8ef24174bb6e39c5c44eef9.svg', 0),
(546, '95jrFjrh', 'stjordal.kommune.no', 'Stjørdal', 'Trøndelag', 5035, 0, '', 'Kommune', 'ea3b7266844c5535ddde7779d26f32764da57fff4ab35990e8c2474eb89a084e.svg', 0),
(547, 'lfihrHlo', 'frosta.kommune.no', 'Frosta', 'Trøndelag', 5036, 0, '', 'Kommune', '92eb157da6c137d33c8d3220dcd11e81f7715c0e0ca779c215e3195dcdfce08a.svg', 0),
(548, 'DXqi8PbM', 'levanger.kommune.no', 'Levanger', 'Trøndelag', 5037, 0, '', 'Kommune', 'b418594edb8290faa3425767bfdf05bca64844489efddfa2d3e637863a7e26d9.svg', 0),
(549, 'oq45fkn', 'verdal.kommune.no', 'Verdal', 'Trøndelag', 5038, 0, '', 'Kommune', '79ad3b0e34550c06f283c1a9bf0f8ca550eaac29dcee6844a0e4a0834e856412.svg', 0),
(550, 'oRLmRWg', 'snasa.kommune.no', 'Snåase  Snåsa', 'Trøndelag', 5041, 0, '', 'Kommune', '7e332ceaa1ea254791c0683f080385002d31738687934835335bd8f40d45d49e.svg', 0),
(551, 'ICH5pvvD', 'lierne.kommune.no', 'Lierne', 'Trøndelag', 5042, 0, '', 'Kommune', 'e6e4ba51982630b599f477632d792bebb362ec234cb56744e8fb4cbdc05e1d95.svg', 0),
(552, '1nBQoQB5', 'royrvik.kommune.no', 'Raarvihke  Røyrvik', 'Trøndelag', 5043, 0, '', 'Kommune', 'cffd4a2ae8480090421eefef6cc512f5a2a62d89dc5f163bdf9d8faa80ddcf1c.svg', 0),
(553, '1gOCablp', 'namsskogan.kommune.no', 'Namsskogan', 'Trøndelag', 5044, 0, '', 'Kommune', '9eaaf5920a382714b1111dc5c8218e641699b4fdddce7c0dbf847db13160acdf.svg', 0),
(554, 'wwCTO1wj', 'grong.kommune.no', 'Grong', 'Trøndelag', 5045, 0, '', 'Kommune', '98ec408950a7dce3ae4e19f5712e252d020b9a955fa5ca74783f7f5b55ebed35.svg', 0),
(555, 'cASRn932', 'hoylandet.kommune.no', 'Høylandet', 'Trøndelag', 5046, 0, '', 'Kommune', '3b216a9731ceb43e4eab1feb9f7d67faab67c9ebf30e29fb8edd72f5e3890cab.svg', 0),
(556, 'qGwLF8Ms', 'overhalla.kommune.no', 'Overhalla', 'Trøndelag', 5047, 0, '', 'Kommune', 'fb11746f53044720aabfbe41d7e163d2d0fa1566885766d6899cd1bcb120a41b.svg', 0),
(557, 'xIp11cp', 'flatanger.kommune.no', 'Flatanger', 'Trøndelag', 5049, 0, '', 'Kommune', '8a3611a40a9b9976fb0784f5f3af732be1d9e66e824d11651122bf705cd1e88b.svg', 0),
(558, 'AaEdiHSb', 'leka.kommune.no', 'Leka', 'Trøndelag', 5052, 0, '', 'Kommune', '74f1ad369a0d3234d4f9830212931dc758e5725de7fb7ea89cf849679eb6a346.svg', 0),
(559, '4AGXoOv2', 'inderoy.kommune.no', 'Inderøy', 'Trøndelag', 5053, 0, '', 'Kommune', '0b374021a42126d0ad46edb56f2546fb0e0cf58eec2bb5c99efa364b8e63798e.svg', 0),
(560, 'a2YzGzyU', 'indrefosen.kommune.no', 'Indre Fosen', 'Trøndelag', 5054, 0, '', 'Kommune', '5c097f3a7cd5cb77ffe6f9c764a8a2195012433d1d9ae4db17595c93917d92a0.svg', 0),
(561, 'Ei8HPUvf', 'heim.kommune.no', 'Heim', 'Trøndelag', 5055, 0, '', 'Kommune', '58fde2d9dd8ae3c7fa846f6d842bf7648ceef2534a4de36987c9ff6459e4f978.svg', 0),
(562, 'mStyBoO', 'hitra.kommune.no', 'Hitra', 'Trøndelag', 5056, 0, '', 'Kommune', '4dc5e3c4c55b89c873d523379340ff85ff49057f4da9b3f058ad60bd2018e9fb.svg', 0),
(563, 'UlCbhsD7', 'orland.kommune.no', 'Ørland', 'Trøndelag', 5057, 0, 'https://orland.kommune.no/', 'Kommune', '9065ea988685b369087e422d24a0b72220c4eb2714d90ce9459f8759b7650e2b.svg', 0),
(564, 'o4WU3pvx', 'afjord.kommune.no', 'Åfjord', 'Trøndelag', 5058, 0, '', 'Kommune', '91a878a98a754633ce5aba94608a874a3fa5336674f737da89795286e94a8273.svg', 0),
(565, 'fozxgGB', 'orkland.kommune.no', 'Orkland', 'Trøndelag', 5059, 0, '', 'Kommune', 'a73a037ce8183cab4f41c955b2b0a86090956b822a8454f831c2b7917d6c6a55.svg', 0),
(566, 'WH9y3UfX', 'naroysund.kommune.no', 'Nærøysund', 'Trøndelag', 5060, 0, '', 'Kommune', '14116d5a9907fb7ae8e459d4dd865048058f1efea70978ffaa6e32813ea74116.svg', 0),
(567, 'QqaUjNUY', 'rindal.kommune.no', 'Rindal', 'Trøndelag', 5061, 0, '', 'Kommune', 'f81986bd61c8c240e393250e7ad6f0aa8bd455810a1143b27771ff3c8a5913ca.svg', 0),
(568, 'Q4enToV6', 'bodo.kommune.no', 'Bodø', 'Nordland', 1804, 0, '', 'Kommune', '703b9497e712e471c3235ce76098993b684e2b61d7e7a3cd4fa758e1e5cba3ef.svg', 0),
(569, 'JCizqhXP', 'narvik.kommune.no', 'Narvik', 'Nordland', 1806, 0, '', 'Kommune', 'f61f5e868632110616414b0c790bf7395b340a06634adf9cef9938508b891bb1.svg', 0),
(570, 'SeMkIAhQ', 'bindal.kommune.no', 'Bindal', 'Nordland', 1811, 0, '', 'Kommune', '9a7f8e038f052df7400c3a6f0dfd4066050e22ae91131462260392a92afec9c5.svg', 0),
(571, 'VAjyzO3a', 'somna.kommune.no', 'Sømna', 'Nordland', 1812, 0, '', 'Kommune', '7d93bd5319485d2048eed012ea3d895ea77c488d5c042a06f92f9157e95ab00a.svg', 0),
(572, 'oatjGJBC', 'brønnoy.kommune.no', 'Brønnøy', 'Nordland', 1813, 0, '', 'Kommune', '3c46756f110aadce4c31cf094bcef64745d4a2bc5f8d5436e662d656f04300be.svg', 0),
(573, 'BQtjRZcZ', 'vega.kommune.no', 'Vega', 'Nordland', 1815, 0, '', 'Kommune', 'efc3ea8bee0d5512ce8faaf689bc4ef2c3186f2c8389b71bd8f943e4b56b9c6f.svg', 0),
(574, 'sUaj3rO', 'vevelstad.kommune.no', 'Vevelstad', 'Nordland', 1816, 0, '', 'Kommune', '7797281ae4798d625efcaa97747e3eba1aa6b04f7b9189d992abe72540e40747.svg', 0),
(575, '6h9oXcpV', 'heroy-no.kommune.no', 'Herøy (Nordland)', 'Nordland', 1818, 0, '', 'Kommune', '80fd1605396d7034fe2803db4288cc0a244ff363467744afc76d829423e223ff.svg', 0),
(576, 'vWATJ6hH', 'alstahaug.kommune.no', 'Alstahaug', 'Nordland', 1820, 0, '', 'Kommune', 'd64461d18a2e5b2e5f9c0990d735cdd1c72ad76ba0495b6b10feb7ee0897f419.svg', 0),
(577, '5KpWoCfE', 'leirfjord.kommune.no', 'Leirfjord', 'Nordland', 1822, 0, '', 'Kommune', '3512a4a7a5e6780264446eec49d36143d6b3d1c8a3601a4d1abe10e096c1e903.svg', 0),
(578, '2SyPRQ3', 'vefsn.kommune.no', 'Vefsn', 'Nordland', 1824, 0, '', 'Kommune', '14c73be662e23d0f2355694dbc8598f946e0aca6d289410301fa3a9436f3f0c6.svg', 0),
(579, 'KGtjlTGQ', 'grane.kommune.no', 'Grane', 'Nordland', 1825, 0, '', 'Kommune', '3638cfb0581477cf0f4d4b1d2548b01c83b853970cfb1b288b28817a670beb5a.svg', 0),
(580, 'Z1VwdDv5', 'hattfjelldal.kommune.no', 'Hattfjelldal', 'Nordland', 1826, 0, '', 'Kommune', '0fe712f40fddc03ffcc735ccfd13d00ef341e47278fa4a60442f60790f9f368f.svg', 0),
(581, 'L8UdqDun', 'donna.kommune.no', 'Dønna', 'Nordland', 1827, 0, '', 'Kommune', 'e7f4a2a8d68a9dbb51d9f3e048cab563c1f7c153dc29d9a592860123f677a38e.svg', 0),
(582, 'gsQbASL4', 'nesna.kommune.no', 'Nesna', 'Nordland', 1828, 0, '', 'Kommune', '4264e427aab5502bee51544b5b197bba0ea80b4c9db0b5d3d5d621eb11d8bb08.svg', 0),
(583, '0DRZ5g5I', 'hemnes.kommune.no', 'Hemnes', 'Nordland', 1832, 0, '', 'Kommune', '5ec8b2e938d5fdafb05d9ed2051950c5a1651068d715608122105da0d288d1c4.svg', 0),
(584, 'bwahHW1c', 'rana.kommune.no', 'Rana', 'Nordland', 1833, 0, '', 'Kommune', 'f83f4b2d8efdaf0bcb60a6b7918bc8af038e5f0443ebb03de4bc096b33cb5064.svg', 0),
(585, 'CYykLCt1', 'luroy.kommune.no', 'Lurøy', 'Nordland', 1834, 0, '', 'Kommune', 'a073f8208e910fadc0db32314295d913baec6f7d4e47761af0a1ee18f5cc5cbc.svg', 0),
(586, 'zoZukvPC', 'trana.kommune.no', 'Træna', 'Nordland', 1835, 0, '', 'Kommune', '9ad0a3fca20e79c6bf88b12ac0046d37ed71f26b634c45fca6a09d1ca208c737.svg', 0),
(587, 'QwMxZ6k9', 'rodoy.kommune.no', 'Rødøy', 'Nordland', 1836, 0, '', 'Kommune', 'fed2a0c7589b5ff1b7a3b8e0b087bbcd7059c250b6e619508beab72f3ae7f8f0.svg', 0),
(588, 'y9Vy1bUe', 'meloy.kommune.no', 'Meløy', 'Nordland', 1837, 0, '', 'Kommune', '13b1fd4d2af9587d107252f59cc39ee5d3fa1bb1ae6cd0c53401a891545a4e9a.svg', 0),
(589, 'TlOJm8BV', 'gildeskal.kommune.no', 'Gildeskål', 'Nordland', 1838, 0, '', 'Kommune', 'fb00c431a3cd8ef407395c6dc9d9bda47ce13def78708f5014415e0f06258223.svg', 0),
(590, 'ikO5lTYF', 'beiarn.kommune.no', 'Beiarn', 'Nordland', 1839, 0, '', 'Kommune', '159dcd29fd67cb5e7efa632777bdb29f2752d10696d62240c011a96d8a004bec.svg', 0),
(591, 'GGWGbPRH', 'saltdal.kommune.no', 'Saltdal', 'Nordland', 1840, 0, '', 'Kommune', '308e8706a1b1db2b9eecaaa431a2ec37d1d66563aa5b1e7d264965eab538e1ce.svg', 0),
(592, 'MoeVQXmS', 'fauske.kommune.no', 'Fauske  Fuossko', 'Nordland', 1841, 0, '', 'Kommune', '8a466e4d4f924fb4a108235acc1fa3ffb3efdbab189f2ad94f36bfb48b4b598d.svg', 0),
(593, 'xJ9M7vXW', 'sorfold.kommune.no', 'Sørfold', 'Nordland', 1845, 0, '', 'Kommune', '2da87491154ee42ba728a0921326df50db484df6387fe8cc772ebd8702cab2a9.svg', 0),
(594, 'dl8MLu0T', 'steigen.kommune.no', 'Steigen', 'Nordland', 1848, 0, '', 'Kommune', '8fa849fb1c9e7c2136550973e8c4ec1eaf6d61f718585e9899b16327e1111d08.svg', 0),
(595, 'X0tNrbeQ', 'lodingen.kommune.no', 'Lødingen', 'Nordland', 1851, 0, '', 'Kommune', '621dacd03bb57145c06019925d9f3030eece3d451be15a9de99421d985de458c.svg', 0),
(596, '24dipr15', 'evenes.kommune.no', 'Evenes', 'Nordland', 1853, 0, '', 'Kommune', '69fbe326707a14ecedd68d26a5c0e3026869ba9048d553bfca64f291501e53f4.svg', 0),
(597, 'BHpg70jD', 'rost.kommune.no', 'Røst', 'Nordland', 1856, 0, '', 'Kommune', '849f206fd497695f1416053f1feb30e0a82815013f6ca014a6d875e7ba74efd6.svg', 0),
(598, 'M9rmmJ9C', 'varoy.kommune.no', 'Værøy', 'Nordland', 1857, 0, '', 'Kommune', 'e89c4e3e79d5d5a3f8c841f1dee3482ad44a7f8ba1225da7d062a14801b9ed3f.svg', 0),
(599, 'ZihJ1sIi', 'flakstad.kommune.no', 'Flakstad', 'Nordland', 1859, 0, '', 'Kommune', '7d1dd622b12d8729414d7360477d5d3b5374e5d5625fa7eb22d393da384b844a.svg', 0),
(600, 'oAY27lIu', 'vestvagoy.kommune.no', 'Vestvågøy', 'Nordland', 1860, 0, '', 'Kommune', '1a602ed0fc1e0412e96e47a60c7fefac67ecd1dbbc1fd57909d36f4209b1d1aa.svg', 0),
(601, 'jvofylvD', 'vagan.kommune.no', 'Vågan', 'Nordland', 1865, 0, '', 'Kommune', '5dc2c7515e069806b1da67642724911532a6fc66adca458d936391680bc13e95.svg', 0),
(602, 'NKxMzejq', 'hadsel.kommune.no', 'Hadsel', 'Nordland', 1866, 0, '', 'Kommune', 'df384b80b44ee680a40f4e3c5437d6a5c8d8b5659158e8b37963e48f649c1a3b.svg', 0),
(603, '7h3yW31P', 'boe.kommune.no', 'Bø (Nordland)', 'Nordland', 1867, 0, '', 'Kommune', 'd28543033d1ca64ec13120e1823db1f15e71c6ac1c546a5c8507460c00776a75.svg', 0),
(604, '3X8KE1fN', 'oksnes.kommune.no', 'Øksnes', 'Nordland', 1868, 0, '', 'Kommune', 'd5affe0ccfaf43aa51ea83bddb1162db3b6020fdacf5841321ab462e77c81c9b.svg', 0),
(605, 'C6Sm19E', 'sortland.kommune.no', 'Sortland  Suortá', 'Nordland', 1870, 0, '', 'Kommune', 'b7484962446823236929798ef1cc251941979988f723f921268e68d10058d055.svg', 0),
(606, 'Fu7BYhfm', 'andoy.kommune.no', 'Andøy', 'Nordland', 1871, 0, '', 'Kommune', '834430ea10daeef0b716d3fe93d6e1894c5ae4f4a3c2505b15382ca58cc8ca7a.svg', 0),
(607, '2ySQ7l0K', 'moskenes.kommune.no', 'Moskenes', 'Nordland', 1874, 0, '', 'Kommune', '3fff330e4417f7f92ff322ac84b03fbb581451b291cff8c8e697c7ddc70b05e9.svg', 0),
(608, 'r4lWxF21', 'hamaroy.kommune.no', 'Hamarøy', 'Nordland', 1875, 0, '', 'Kommune', '482295f8e63075531dc78616989bec99719b6de35da82c10da9cd22eedbb6400.svg', 0),
(609, 'hRfzYvV7', 'tromso.kommune.no', 'Tromsø', 'Troms og Finnmark', 5401, 0, '', 'Kommune', '6ba6dcb1e5e99e2320a4cb38e129eb6dccdad9b96403c1f5ed4e66e55013f2aa.svg', 0),
(610, 'DhhvHhHp', 'harstad.kommune.no', 'Harstad', 'Troms og Finnmark', 5402, 0, '', 'Kommune', '100970d869ca5af3e0440bb40e97fb7f74f9fa8da8f11e56749f0e942013fe18.svg', 0),
(611, 'zCpZDLB9', 'alta.kommune.no', 'Alta', 'Troms og Finnmark', 5403, 0, '', 'Kommune', '5e670d0c0138e248e72e448dc8bfa8b925701522a98fae87e82a73c9ce48099c.svg', 0),
(612, '6L9QbNWq', 'vardo.kommune.no', 'Vardø', 'Troms og Finnmark', 5404, 0, '', 'Kommune', '2245eac3399c2196b3ed5910b05092f89b2de4d5dec3977e93f482efd68cf893.svg', 0),
(613, 'z0soWKwz', 'vadso.kommune.no', 'Vadsø', 'Troms og Finnmark', 5405, 0, '', 'Kommune', '41ad411674f767169001ccf1736866f191d925ad31fb13170b7381e4694fc269.svg', 0),
(614, '8PnHAw5H', 'hammerfest.kommune.no', 'Hammerfest', 'Troms og Finnmark', 5406, 0, '', 'Kommune', 'c1c045792eb98b91d203cd5d0db9f770e0e07f7fbbf8a30cec6b88ca4e88e7d2.svg', 0),
(615, 'msi2PK1H', 'kvafjord.kommune.no', 'Kvæfjord', 'Troms og Finnmark', 5411, 0, '', 'Kommune', '84624aba0533319b81c7dc0e35813e4a95b611b8caec089f009f3ac6d3e91c41.svg', 0),
(616, '1lm8fGN4', 'tjeldsund.kommune.no', 'Tjeldsund', 'Troms og Finnmark', 5412, 0, '', 'Kommune', 'a2e163e20cebeae9f40160093bf147852331e0802378fe137206910b6fee5628.svg', 0),
(617, 'Fylocmp', 'ibestad.kommune.no', 'Ibestad', 'Troms og Finnmark', 5413, 0, '', 'Kommune', 'de4cc4cf227edb79983d2cf73b564fa5bfae9d90c778cdb37d7dfde6617d6d32.svg', 0),
(618, '2qv12f9u', 'gratangen.kommune.no', 'Gratangen', 'Troms og Finnmark', 5414, 0, '', 'Kommune', '2df2cb5155642326a4b4a3114184c6b1d193550cf644327bf2dbc8829bf0286b.svg', 0),
(619, 'oyW0hKKx', 'lavangen.kommune.no', 'Loabák  Lavangen', 'Troms og Finnmark', 5415, 0, '', 'Kommune', '3dbe71fbfd69a76fc573cbd3c7ec6934014ce0c47892162be35d52ac196fdec8.svg', 0),
(620, '1Edp56Bp', 'bardu.kommune.no', 'Bardu', 'Troms og Finnmark', 5416, 0, '', 'Kommune', '10346db3ba40b44c457c3c457ed1ec2d438d4f73411ba9d89fc6174c6950ad8c.svg', 0),
(621, 'A6o9s82v', 'salangen.kommune.no', 'Salangen', 'Troms og Finnmark', 5417, 0, '', 'Kommune', 'ccef149fca594a5607d616b457222d8cd270bb57a2852ee9feeb0a65f298496a.svg', 0),
(622, 'EkSvQJKG', 'malselv.kommune.no', 'Målselv', 'Troms og Finnmark', 5418, 0, '', 'Kommune', 'a01b39b748e571319d3d290d83b68301a7925e78d8a591f46cce3472fd30e556.svg', 0),
(623, 'doaEtzQn', 'sorreisa.kommune.no', 'Sørreisa', 'Troms og Finnmark', 5419, 0, '', 'Kommune', 'b9d6a03bc1a930e8081af2198392aec849353b90f63b322a961cd8303caa76ae.svg', 0),
(624, '4KLTfBgQ', 'dyroy.kommune.no', 'Dyrøy', 'Troms og Finnmark', 5420, 0, '', 'Kommune', 'efa8a7b9c9fcf2a8efc41c22464189710c38b310fce6310757e7505c75c0a402.svg', 0),
(625, '9e9SzDkO', 'senja.kommune.no', 'Senja', 'Troms og Finnmark', 5421, 0, '', 'Kommune', 'd3e64b307e1ef4349be0c1bb1254cfd4d79dec68522dba5d035e9a14363a9639.svg', 0),
(626, 'n9TD6rzR', 'balsfjord.kommune.no', 'Balsfjord', 'Troms og Finnmark', 5422, 0, '', 'Kommune', '82e6604579e2cf64396ccfd35a69c4ada4adf3c38b89042879ebecc3b8d97c57.svg', 0),
(627, '0QjiJy3O', 'karlsoy.kommune.no', 'Karlsøy', 'Troms og Finnmark', 5423, 0, '', 'Kommune', '29295e247e5adaba5d658d27acd2a4b25fb124faaf2966bccc8495939320fabc.svg', 0),
(628, 'Ge00Gwya', 'lyngen.kommune.no', 'Lyngen', 'Troms og Finnmark', 5424, 0, '', 'Kommune', '2afd24e4baa2aefc7e238d203198b2f41becf552c78c3320f77b497c61a29493.svg', 0),
(629, 'RRxzo32o', 'storfjord.kommune.no', 'Storfjord  Omasvuotna  Omasvuono', 'Troms og Finnmark', 5425, 0, '', 'Kommune', 'd4d552c1b406fef1225fefe4e5803ab08a1ea2b0e670b440ba6c34dfc39aec76.svg', 0),
(630, '1Oq1ey55', 'kafjord.kommune.no', 'Gáivuotna  Kåfjord  Kaivuono', 'Troms og Finnmark', 5426, 0, '', 'Kommune', '9ca81aa8f373aa85819b886bd2a6224178272b15bf3d04be22e208c71f55a18f.svg', 0),
(631, 'ZNpN1g9O', 'skjervoy.kommune.no', 'Skjervøy', 'Troms og Finnmark', 5427, 0, '', 'Kommune', '83afcfc7db90850669d2c51eb4d94038c6069f6fd75c11459597ecaac74c6d02.svg', 0),
(632, 'FH8vfYFy', 'nordreisa.kommune.no', 'Nordreisa', 'Troms og Finnmark', 5428, 0, '', 'Kommune', 'b277d36bd4026efa33425295b9dbe303d8e7622539d4cd2a6d33daff7b740b75.svg', 0),
(633, 'OThDInd', 'kvanangen.kommune.no', 'Kvænangen', 'Troms og Finnmark', 5429, 0, '', 'Kommune', '71c03c74f90bef341c2e3c5e5d308a06e98a4cc876ac97b7f54921e6d4f512d1.svg', 0),
(634, 'Fo6F96M9', 'kautokeino.kommune.no', 'Guovdageaidnu  Kautokeino', 'Troms og Finnmark', 5430, 0, '', 'Kommune', 'af47256181af4b02ff89714eec99c8e6a08ad4e08ec48a3e0faf4d29d90a59cd.svg', 0),
(635, '6qMy0qQK', 'loppa.kommune.no', 'Loppa', 'Troms og Finnmark', 5432, 0, '', 'Kommune', 'c9b57de987f08fbe8585dd8f09af4d71425188eb0767feb1801b60c6981bf590.svg', 0),
(636, 'QIObR2Jn', 'hasvik.kommune.no', 'Hasvik', 'Troms og Finnmark', 5433, 0, '', 'Kommune', 'b67bc0549f3d9ea705f0d5e0a46b9ab7c35c5a0e3aa9881ff4e035a9cbc846c1.svg', 0),
(637, 'i5AojjF6', 'masoy.kommune.no', 'Måsøy', 'Troms og Finnmark', 5434, 0, '', 'Kommune', 'b5a1834a324e9d2a433f7547d306b5290a954b3ad47c756192733b065aa6fd35.svg', 0),
(638, '6hu5k3pG', 'nordkapp.kommune.no', 'Nordkapp', 'Troms og Finnmark', 5435, 0, '', 'Kommune', '42a481c8b9222eeb397c39a2bcfb22521e02b7f0af60dccb650d1759f68366c3.svg', 0),
(639, 'Kc9jhzbq', 'porsanger.kommune.no', 'Porsanger  Porsángu  Porsanki', 'Troms og Finnmark', 5436, 0, '', 'Kommune', 'ef19ea710fed37b0c5c1fc378ebb3ff51fa1f683589def240f52501478741b8d.svg', 0),
(640, 'qTrMVCmg', 'karasjok.kommune.no', 'Kárásjohka  Karasjok', 'Troms og Finnmark', 5437, 0, '', 'Kommune', '2651384dde234061b27f5d6e1fda0f045283711408059a14527545a67ab55424.svg', 0),
(641, 'L7SLms3a', 'lebesby.kommune.no', 'Lebesby', 'Troms og Finnmark', 5438, 0, '', 'Kommune', '37d7ba2a70723b5cebb0ba1209e64a3c32f2cb8d7d2a375acaa9d57831f8422a.svg', 0),
(642, 'T8b0cRuF', 'gamvik.kommune.no', 'Gamvik', 'Troms og Finnmark', 5439, 0, '', 'Kommune', '8a6e0b43829f7e8cfbf30e40a94c81531f442fa5eb0fc1b72f0e26055cfba75a.svg', 0),
(643, 'EyRjH3Xv', 'berlevag.kommune.no', 'Berlevåg', 'Troms og Finnmark', 5440, 0, '', 'Kommune', '3c0611302ba59e5b21ce3a8fa3c479e074049b59a5674ad3d34cb69622301d90.svg', 0),
(644, 'QflCjEM9', 'tana.kommune.no', 'Deatnu-Tana', 'Troms og Finnmark', 5441, 0, '', 'Kommune', '9db66a57dd0e1299cf9f5c8b3f7608cf4ad73cb971e71c0b55e27bd77d518a4f.svg', 0),
(645, 'gYjKCtaG', 'nesseby.kommune.no', 'Unjárga-Nesseby', 'Troms og Finnmark', 5442, 0, '', 'Kommune', 'f8b2bdb8c72126f28f4ffe5d03331a0556ffa29e33bcce95e3aeb10d2d560725.svg', 0),
(646, '9GFlOwba', 'batsfjord.kommune.no', 'Båtsfjord', 'Troms og Finnmark', 5443, 0, '', 'Kommune', 'a7ec8eb738d26c873ef5b7a7a66532c38035e15264fe7753dcd261cd6639693d.svg', 0),
(647, 'QttnwoIY', 'sor-varanger.kommune.no', 'Sør-Varanger', 'Troms og Finnmark', 5444, 0, '', 'Kommune', '13f45cd6f1f3944049f50944789ca80a7fda74b87f42d13b6ec6de527a5fcd84.svg', 0),
(648, 'lhxAesY', '', 'Oslo', '', 3, 958935420, 'https://www.oslo.kommune.no/', 'Fylke', '72e59daffee7fb46c4507d3afc2b798fdc6dc09f63f5d2bec3c5136bb0b4d5b2.svg', 0),
(649, 'nRwV2N4', 'rogfk.no', 'Rogaland', '', 11, 971045698, 'https://www.rogfk.no/', 'Fylke', 'da186da33a8729d94d14f20cca480ad9e9d6747abc1fd0cb055e96c110c533b9.svg', 0),
(650, 'VkKaVFY6', 'mrfylke.no', 'Møre og Romsdal', '', 15, 944183779, 'https://mrfylke.no/', 'Fylke', '0441ce68db2383db24fa972ecfb983ab3ccf1852392585d1a34d955aee3c8553.svg', 0),
(651, '6GLmZoWp', 'nfk.no', 'Nordland', '', 18, 964982953, 'https://www.nfk.no/', 'Fylke', 'c44bb5da3a1433b8fec226c23513264435e0b034ce73944ff469f968aa202a80.svg', 0),
(652, 'l92dlcoL', 'viken.no', 'Viken', '', 30, 921693230, 'https://viken.no/', 'Fylke', '789ddff771dbca113bf67cd73ebc4589b5420304ec24c2053fdd89f682190f30.svg', 0);
INSERT INTO `company` (`id`, `public_id`, `domain`, `title`, `county`, `type_id`, `org_numb`, `website`, `type`, `logo`, `access`) VALUES
(653, 'CZLzOlj', 'innlandetfylke.no', 'Innlandet', '', 34, 920717152, 'https://innlandetfylke.no/', 'Fylke', '1bb33b8b4e4851863460f2f8c0c9504ad106aa577a0e92de6f024b3db3ec95c4.svg', 0),
(654, 'Q7YyiDOb', 'vtfk.no', 'Vestfold og Telemark', '', 38, 821227062, 'https://www.vtfk.no/', 'Fylke', '095309e078d6146defdddb1e01c64f0496d8dbcdfa7c85b7c2f8a4fbae90d498.svg', 0),
(655, 'JDd5i3aM', 'agderfk.no', 'Agder', 'Agder', 42, 921707134, 'https://agderfk.no/', 'Fylke', 'a080bf6ea48a8e69fc9eddabe2167f1900a5610da7bd1d8e6ca7c98e0c159d19.svg', 0),
(656, 'Z2AneFWF', 'vlfk.no', 'Vestland', '', 46, 821311632, 'https://www.vestlandfylke.no/', 'Fylke', 'f8628c292c634aadbb956bfa5d36ecff782574a57920ed8f4bc36fcc3ee83a44.svg', 0),
(657, 'THavgULe', 'trondelagfylke.no', 'Trøndelag', '', 50, 817920632, 'https://www.trondelagfylke.no/', 'Fylke', '8080fb9025ed207767e5d194b72c325247fd91764c9f3974975f9f80e4c825b3.svg', 0),
(658, 'ieNBOwRO', 'tffk.no', 'Troms og Finnmark', '', 54, 0, 'https://www.tffk.no/', 'Fylke', '2dd61dcee7ca3b23147a1ea3076d682643ee5d472fa313ccdb089e76e31394cc.svg', 0),
(660, 'tVk1wGix', 'iktorkide.no', 'IKT ORKidé', '', 0, 964981604, 'https://www.iktorkide.no/', 'Interkommunalt samarbeid', '9ceb6e48e7f66bab5fd54734db5babdd3bef550106dbc5d529c5d95770942625.png', NULL),
(661, 'Pcn9SEM', 'ddv.no', 'Det Digitale Vestre Agder (DDV)', '', 0, 914006813, 'https://www.ddv.no/', 'Interkommunalt samarbeid', 'be520ffc5931b6377f6d76a2fd7b099f1171d0a13d3bc2121097a94574d22f63.png', NULL),
(662, 'GLi3cSSm', 'ikt-agder.no', 'IKT Agder', '', 0, 985359385, 'https://www.ikt-agder.no/', 'Interkommunalt samarbeid', 'b6d8efaf1fa52fc3539430d644352d61ac57154d876ddb00bc6b3b870fb2a9fe.png', NULL),
(663, 'Rdm0AOEP', 'ror-ikt.no', 'ROR-IKT', '', 0, 0, 'https://www.ror-ikt.no/', 'Interkommunalt samarbeid', '93273199cdeadcd6d037a34e6ec94509b4dad62feff7606512636b3a27665cfb.jpg', NULL),
(664, 'av1E98mF', 'jarlsberg-ikt.no', 'Jarlsberg IKT', '', 0, 0, 'https://www.jarlsberg-ikt.no/', 'Interkommunalt samarbeid', '692cbdc41b2bf1ea40b5bd01a811311e5db7ac3952cee3b4c66e7404459e06fd.svg', NULL),
(665, 'ywpc6zfP', 'hedmark-ikt.no', 'Hedemark IKT', '', 0, 989141147, 'https://www.hedmark-ikt.no/', 'Interkommunalt samarbeid', '3ef8b81fc5846e3cdc4cfb000f848adeb63beddf728ce17cbca1aa8e4146a78f.svg', NULL),
(666, '08JFWAoj', 'k-ikt.no', 'K-IKT (Kongsbergregionen-IKT)', '', 0, 915958109, 'https://k-ikt.no/', 'Interkommunalt samarbeid', '16c58a2047106206cfe8e6277167a7b515ed5d0ae2f75024a492c16aeb17168d.png', NULL),
(667, 'ZWGk1wOX', 'iktnh.no', 'IKT Nordhordland', '', 0, 0, 'https://iktnh.no/', 'Interkommunalt samarbeid', '41ed1e2ad41ed37c446a3bee40f22929ae23d51015a2876fdfb8e9c612f48542.png', NULL),
(668, 'dhmez4Fm', 'iktin.no', 'IKT Indre Namdal IKS', '', 0, 0, 'http://iktin.no/', 'Interkommunalt samarbeid', '14a0866dd120f0a9577ab7b9b0c84db63a5daabb2953fdde7cc04c85721d1071.png', NULL),
(669, 'rAH9FB2V', 'nordfjordnett.no', 'Nordfjordnett', '', 0, 812536982, 'https://www.nordfjordnett.no/', 'Interkommunalt samarbeid', '592f6cb8178a11fa1d40c570f756da2cf6d8facb3907def9bfb645a3c5dafe78.png', NULL),
(670, 'WEr3cjb5', 'dgi.no', 'Digitale Gardermoen (DGI)', '', 0, 915498582, 'https://www.dgi.no/', 'Interkommunalt samarbeid', 'eadd3368f35a0f21ccf66996575176cdb11e5e1d47169a9eb58fa5932a0dfe22.png', NULL);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `idevent` int NOT NULL AUTO_INCREMENT,
  `company_id` varchar(256) COLLATE utf8mb4_bin DEFAULT NULL,
  `time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  `domain` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `event_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `entity_id` int DEFAULT NULL,
  `entity_tag` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `event_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  PRIMARY KEY (`idevent`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `app_id` int NOT NULL,
  `time_uploaded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `filename` varchar(256) NOT NULL,
  `size` double NOT NULL,
  `type` varchar(32) NOT NULL,
  `path` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `licenses`
--

CREATE TABLE IF NOT EXISTS `licenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `link` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `license_details`
--

CREATE TABLE IF NOT EXISTS `license_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('permission','condition','limitation') NOT NULL,
  `title` varchar(256) NOT NULL,
  `description` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `license_has_details`
--

CREATE TABLE IF NOT EXISTS `license_has_details` (
  `license_id` int NOT NULL,
  `details_id` int NOT NULL,
  PRIMARY KEY (`license_id`,`details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `login_keys`
--

CREATE TABLE IF NOT EXISTS `login_keys` (
  `id` int NOT NULL AUTO_INCREMENT,
  `time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_expires` datetime NOT NULL,
  `user_id` int NOT NULL,
  `pincode` varchar(16) NOT NULL,
  `hash` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `log_errors`
--

CREATE TABLE IF NOT EXISTS `log_errors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_agent` varchar(256) NOT NULL,
  `ip_address` varchar(64) NOT NULL,
  `user_id` int DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `domain` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `title` varchar(256) NOT NULL,
  `message` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `severity` enum('low','medium','high') NOT NULL,
  `error_id` varchar(32) DEFAULT NULL,
  `entity_id` int DEFAULT NULL,
  `event_data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `mail_out`
--

CREATE TABLE IF NOT EXISTS `mail_out` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recipient` varchar(256) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `notification_msg`
--

CREATE TABLE IF NOT EXISTS `notification_msg` (
  `id` int NOT NULL AUTO_INCREMENT,
  `department_id` int NOT NULL,
  `notification_sid` varchar(64) NOT NULL,
  `title` varchar(256) NOT NULL,
  `push_message` varchar(256) NOT NULL,
  `mail_subject` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mail_message` text NOT NULL,
  `method` enum('email','in_app','sms','push') NOT NULL,
  `recipient` int NOT NULL,
  `link` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `last_try` datetime DEFAULT NULL,
  `time_sent` datetime DEFAULT NULL,
  `status` enum('pending','sent','read','failed') NOT NULL,
  `response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`id`),
  KEY `notification_id` (`notification_sid`),
  KEY `recipient` (`recipient`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `notification_templates`
--

CREATE TABLE IF NOT EXISTS `notification_templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sid` varchar(256) NOT NULL COMMENT 'String ID',
  `lang` varchar(8) NOT NULL COMMENT 'Language',
  `department_id` int NOT NULL,
  `time_created` datetime NOT NULL,
  `time_updated` datetime NOT NULL,
  `updated_by` int NOT NULL,
  `variables` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `title` varchar(256) NOT NULL,
  `push_message` varchar(256) NOT NULL,
  `mail_subject` varchar(256) NOT NULL,
  `mail_message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`),
  KEY `sid` (`sid`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dataark for tabell `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `sid`, `lang`, `department_id`, `time_created`, `time_updated`, `updated_by`, `variables`, `active`, `title`, `push_message`, `mail_subject`, `mail_message`) VALUES
(1, 'user_reset_pw_link', '', 0, '2020-04-15 00:00:00', '2020-04-15 00:00:00', 1, 'recipient_name, user_id, firstname, lastname, link', 1, 'Nytt passord', 'Lenke for nullstille passord sendt', 'Nullstille passord', 'Hei %%recipient_name%%!&lt;br /&gt;&lt;br /&gt;Klikk p&amp;aring; lenken for &amp;aring; nullstille passord: &lt;a href=&quot;%%link%%&quot;&gt;%%link%%&lt;/a&gt;&lt;br /&gt;&lt;br /&gt;Med vennlig hilsen&lt;br /&gt;FIPO Helpdesk'),
(2, 'default', 'nb', 0, '2020-09-10 00:00:00', '2020-09-10 00:00:00', 1, 'recipient_name', 1, 'Ingen mal', 'En sak har blitt oppdatert (Ingen mal)', '[SAK:%%department_id%%-%%ticket_id%%] Sak oppdatert', '&lt;strong&gt;Hei %%recipient_name%% !&lt;/strong&gt;&lt;br /&gt;&lt;br /&gt;En sak har blitt oppdatert.&lt;br /&gt;&lt;br /&gt;Men vi mangler tydeligvis en fornuftig e-postmal for denne hendelsen, s&amp;aring; vi har ikke mulighet til &amp;aring; forklare deg hva som ble oppdatert.&lt;br /&gt;&lt;br /&gt;Vennligst logg inn for &amp;aring; se. Gi gjerne en tilbakemelding p&amp;aring; at mal mangler, slik at vi kan gi deg en mer informativ melding neste gang.');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `service_areas`
--

CREATE TABLE IF NOT EXISTS `service_areas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `title` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dataark for tabell `service_areas`
--

INSERT INTO `service_areas` (`id`, `parent_id`, `title`) VALUES
(1, NULL, 'Administrasjon og IKT'),
(2, NULL, 'Arbeid og næringsliv'),
(3, NULL, 'Helse og velferd'),
(4, NULL, 'Kultur, idrett og fritid'),
(5, NULL, 'Natur og miljø'),
(6, NULL, 'Plan, bygg og geodata'),
(7, NULL, 'Skatt og avgift'),
(8, NULL, 'Skole og oppvekst'),
(9, NULL, 'Trafikk og samferdsel'),
(10, NULL, 'Vann og avløp');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `stats`
--

CREATE TABLE IF NOT EXISTS `stats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('app','file','company_profile','user_profile') NOT NULL,
  `user_id` int NOT NULL,
  `entity_id` varchar(32) NOT NULL COMMENT 'app_id, file_id, etc...',
  `entity_id2` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `o365_id` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `firstname` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `lastname` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `initials` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `mail` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `mobile` varchar(32) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `company_role` varchar(128) DEFAULT NULL,
  `password` varchar(256) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `o365_token` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `status` enum('active','deactivated','system') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `photo` longtext,
  `color` varchar(32) DEFAULT NULL,
  `login_token` varchar(256) DEFAULT NULL,
  `reset_token` varchar(256) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `token_created` datetime DEFAULT NULL,
  `last_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `system_user` tinyint(1) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `o365_id` (`o365_id`),
  KEY `firstname` (`firstname`),
  KEY `lastname` (`lastname`),
  KEY `mail` (`mail`),
  KEY `status` (`status`),
  KEY `customer_id` (`customer_id`),
  KEY `admin` (`admin`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user_sessions`
--

CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(128) NOT NULL COMMENT 'Title if longlived token',
  `token` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_expires` datetime NOT NULL,
  `time_last_active` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_agent` varchar(256) NOT NULL,
  `ip_address` varchar(128) NOT NULL,
  `status` enum('active','logged_out','expired','blocked') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;