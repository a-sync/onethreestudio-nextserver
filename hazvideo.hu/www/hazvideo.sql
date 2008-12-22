-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb5
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2009. Ápr 23. 04:27
-- Szerver verzió: 5.0.51
-- PHP Verzió: 5.2.6-1+lenny2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `hazvideo`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet: `esemenyek`
--

CREATE TABLE IF NOT EXISTS `esemenyek` (
  `esid` int(10) unsigned NOT NULL auto_increment,
  `hiid` int(10) unsigned NOT NULL default '0',
  `text` text collate utf8_hungarian_ci NOT NULL,
  `statusz` tinyint(1) unsigned NOT NULL default '0',
  `prioritas` tinyint(2) unsigned NOT NULL default '0',
  `datum` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`esid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=71 ;

--
-- Tábla adatok: `esemenyek`
--

INSERT INTO `esemenyek` (`esid`, `hiid`, `text`, `statusz`, `prioritas`, `datum`) VALUES
(1, 7, 'Sikertelen bejelentkezÃ©si kÃ­sÃ©rlet. (IP: 89.134.199.200)', 0, 0, 1240079181),
(2, 1, 'Sikertelen bejelentkezÃ©si kÃ­sÃ©rlet. (IP: 89.223.161.146)', 0, 0, 1240092334),
(3, 1, 'BejelentkezÃ©s.', 0, 0, 1240092345),
(4, 1, 'Ã‰rvÃ©nyessÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem!', 0, 3, 1240092376),
(5, 1, 'KijelentkezÃ©s.', 0, 0, 1240092407),
(6, 13, 'BejelentkezÃ©s.', 0, 0, 1240122849),
(7, 13, 'KiemeltsÃ©g beÃ¡llÃ­tÃ¡sÃ¡t kÃ©rem!', 0, 3, 1240122929),
(8, 13, 'KijelentkezÃ©s.', 0, 0, 1240122965),
(9, 13, 'Sikertelen bejelentkezÃ©si kÃ­sÃ©rlet. (IP: 94.44.25.23)', 0, 0, 1240133271),
(10, 13, 'BejelentkezÃ©s.', 0, 0, 1240133286),
(11, 13, 'KijelentkezÃ©s.', 0, 0, 1240133345),
(12, 1, 'Sikertelen bejelentkezÃ©si kÃ­sÃ©rlet. (IP: 94.44.25.23)', 0, 0, 1240135296),
(13, 1, 'BejelentkezÃ©s.', 0, 0, 1240135310),
(14, 1, 'KiemeltsÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem!', 0, 3, 1240135363),
(15, 1, 'Ã‰rvÃ©nyessÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem!', 0, 3, 1240135375),
(16, 1, 'KijelentkezÃ©s.', 0, 0, 1240136374),
(17, 1, 'BejelentkezÃ©s.', 0, 0, 1240140017),
(18, 1, 'Ã‰rvÃ©nyessÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem!', 0, 3, 1240140022),
(19, 1, 'KiemeltsÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem!', 0, 3, 1240140032),
(20, 1, 'KiemeltsÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem!', 0, 3, 1240140040),
(21, 1, 'Ã‰rvÃ©nyessÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem!', 0, 3, 1240140044),
(22, 1, 'KijelentkezÃ©s.', 0, 0, 1240140050),
(23, 1, 'BejelentkezÃ©s.', 0, 0, 1240140070),
(24, 1, 'Ã‰rvÃ©nyessÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem!', 0, 3, 1240140075),
(25, 1, 'KiemeltsÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem!', 0, 3, 1240140082),
(26, 1, 'KijelentkezÃ©s.', 0, 0, 1240140095),
(27, 1, 'BejelentkezÃ©s.', 0, 0, 1240140116),
(28, 1, 'KiemeltsÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem!', 0, 3, 1240140121),
(29, 1, 'KijelentkezÃ©s.', 0, 0, 1240142201),
(30, 1, 'BejelentkezÃ©s.', 0, 0, 1240142536),
(31, 1, 'KijelentkezÃ©s.', 0, 0, 1240142661),
(32, 14, 'Sikertelen bejelentkezÃ©si kÃ­sÃ©rlet. (IP: 94.44.30.122)', 0, 0, 1240161479),
(33, 14, 'BejelentkezÃ©s.', 0, 0, 1240161485),
(34, 14, 'KijelentkezÃ©s.', 0, 0, 1240161511),
(35, 13, 'BejelentkezÃ©s.', 0, 0, 1240171968),
(36, 13, 'Ã‰rvÃ©nyessÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem! (Jelenleg 2009.04.30-ig Ã©rvÃ©nyes.)', 0, 3, 1240171975),
(37, 13, 'KijelentkezÃ©s.', 0, 0, 1240171980),
(38, 15, 'BejelentkezÃ©s.', 0, 0, 1240172164),
(39, 15, 'KijelentkezÃ©s.', 0, 0, 1240172192),
(40, 14, 'Sikertelen bejelentkezÃ©si kÃ­sÃ©rlet. (IP: 89.223.196.201)', 0, 0, 1240210387),
(41, 14, 'BejelentkezÃ©s.', 0, 0, 1240210394),
(42, 14, 'KijelentkezÃ©s.', 0, 0, 1240210427),
(43, 15, 'Sikertelen bejelentkezÃ©si kÃ­sÃ©rlet. (IP: 89.133.159.39)', 0, 0, 1240219638),
(44, 15, 'BejelentkezÃ©s.', 0, 0, 1240219644),
(45, 1, 'BejelentkezÃ©s.', 0, 0, 1240219679),
(46, 1, 'Ã‰rvÃ©nyessÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem! (Jelenleg 2010.01.31-ig Ã©rvÃ©nyes.)', 0, 3, 1240222589),
(47, 1, 'KiemeltsÃ©g meghosszabbÃ­tÃ¡sÃ¡t kÃ©rem! (Jelenleg 2010.01.31-ig kiemelt.)', 0, 3, 1240222593),
(48, 1, 'KijelentkezÃ©s.', 0, 0, 1240222600),
(49, 16, 'BejelentkezÃ©s.', 0, 0, 1240231207),
(50, 16, 'KijelentkezÃ©s.', 0, 0, 1240231847),
(51, 0, 'Admin kijelentkezett. (zoli)', 0, 0, 1240236925),
(52, 0, 'Admin bejelentkezett. (zoli)', 0, 0, 1240243306),
(53, 0, 'Admin kijelentkezett. (zoli)', 0, 0, 1240245032),
(54, 15, 'Sikertelen bejelentkezÃ©si kÃ­sÃ©rlet. (IP: 94.44.20.98)', 0, 0, 1240246677),
(55, 15, 'BejelentkezÃ©s.', 0, 0, 1240246682),
(56, 17, 'Ãšj hirdetÃ©s lett feltÃ¶ltve.', 0, 1, 1240246881),
(57, 15, 'KijelentkezÃ©s.', 0, 0, 1240246932),
(58, 0, 'Admin bejelentkezett. (zoli)', 0, 0, 1240246971),
(59, 0, 'Admin kijelentkezett. (zoli)', 0, 0, 1240248197),
(60, 0, 'Admin bejelentkezett. (zoli)', 0, 0, 1240301017),
(61, 15, 'BejelentkezÃ©s.', 0, 0, 1240305714),
(62, 0, 'Admin kijelentkezett. (zoli)', 0, 0, 1240305761),
(63, 17, 'BejelentkezÃ©s.', 0, 0, 1240305808),
(64, 17, 'KijelentkezÃ©s.', 0, 0, 1240305847),
(65, 15, 'BejelentkezÃ©s.', 0, 0, 1240316297),
(66, 15, 'KijelentkezÃ©s.', 0, 0, 1240318190),
(67, 0, 'Admin bejelentkezett. (vector)', 0, 0, 1240394354),
(68, 0, 'Admin kijelentkezett. (vector)', 0, 0, 1240402768),
(69, 0, 'Admin bejelentkezett. (vector)', 0, 0, 1240436067),
(70, 0, 'Minden esemÃ©ny stÃ¡tusza ellenÅ‘rzÃ¶ttre lett Ã¡llÃ­tva. (vector)', 1, 0, 1240452885);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `hirdetesek`
--

CREATE TABLE IF NOT EXISTS `hirdetesek` (
  `hiid` int(10) unsigned NOT NULL auto_increment,
  `hirdetes_cime` tinytext collate utf8_hungarian_ci NOT NULL,
  `regio` int(10) unsigned NOT NULL,
  `telepules` int(10) unsigned NOT NULL,
  `varosresz` int(10) unsigned NOT NULL,
  `kategoria` int(10) unsigned NOT NULL,
  `jelleg` int(10) unsigned NOT NULL,
  `szobak` float(10,1) unsigned NOT NULL default '1.0',
  `alapterulet` int(10) unsigned NOT NULL,
  `ar` int(10) unsigned NOT NULL,
  `szintek` float(10,1) unsigned NOT NULL default '1.0',
  `garazs` int(10) unsigned NOT NULL,
  `megjegyzes` text collate utf8_hungarian_ci NOT NULL,
  `cim` text collate utf8_hungarian_ci NOT NULL,
  `statusz` int(10) unsigned NOT NULL default '0',
  `datum` int(10) unsigned NOT NULL,
  `ervenyes_datum` int(10) unsigned NOT NULL default '0',
  `email` tinytext collate utf8_hungarian_ci NOT NULL,
  `jelszo` tinytext collate utf8_hungarian_ci NOT NULL,
  `nev` tinytext collate utf8_hungarian_ci NOT NULL,
  `telefon` tinytext collate utf8_hungarian_ci NOT NULL,
  `kiemelt_datum` int(10) unsigned NOT NULL default '0',
  `uzletkoto` tinytext collate utf8_hungarian_ci NOT NULL,
  PRIMARY KEY  (`hiid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=18 ;

--
-- Tábla adatok: `hirdetesek`
--

INSERT INTO `hirdetesek` (`hiid`, `hirdetes_cime`, `regio`, `telepules`, `varosresz`, `kategoria`, `jelleg`, `szobak`, `alapterulet`, `ar`, `szintek`, `garazs`, `megjegyzes`, `cim`, `statusz`, `datum`, `ervenyes_datum`, `email`, `jelszo`, `nev`, `telefon`, `kiemelt_datum`, `uzletkoto`) VALUES
(1, 'HasznÃ¡lt TÃ©gla lakÃ¡s, 2 szoba', 0, 0, 3, 1, 1, 2.0, 54, 9000000, 0.0, 1, 'Hidd el, jÃ³l jÃ¡rok!', 'PetÅ‘fi SÃ¡ndor u. 48.', 3, 1237896541, 1264978799, 'kindlazoltan@gmail.com', 'barack', 'Kindla ZoltÃ¡n', '202580066', 1264978799, ''),
(2, 'HasznÃ¡lt CsalÃ¡di hÃ¡z / Villa, 5 szoba', 0, 0, 2, 0, 1, 5.0, 145, 48000000, 2.0, 0, 'MarhÃ¡ra nincs mÃ©g befejezve, de jÃ³ befektetÃ©s.', '', 3, 1237972953, 1241128799, 'sparheltkifozde@t-online.hu', 'alma', 'Helmeci Levente', '309311519', 1241128799, ''),
(3, 'BÃ©relhetÅ‘ Telek, 3 szoba', 1, 1, 0, 4, 2, 3.0, 236, 80000, 1.0, 0, '', '', 3, 1237982979, 1241128799, 'barna@vipmail.hu', 'epu3c8yv', 'TÃ³th BarnabÃ¡s', '703849932', 1241128799, ''),
(4, 'HasznÃ¡lt CsalÃ¡di hÃ¡z / Villa, 5 szoba', 0, 0, 2, 0, 1, 5.5, 120, 65000000, 2.5, 0, 'kurva jÃ³', '', 3, 1238519122, 1241128799, 'sparheltkifozde@t-online.hu', 'levi', 'Levente', '456789', 1241128799, ''),
(5, 'Ãšj CsalÃ¡di hÃ¡z / Villa', 0, 0, 2, 0, 0, 0.0, 32, 233222, 0.0, 0, '', '', 3, 1238599890, 1241128799, 'zsa@sa.sa', '1233', 'Proba', '213124', 957131999, ''),
(6, 'Ãšj NyaralÃ³', 0, 0, 0, 4, 0, 0.0, 50, 10000000, 0.0, 1, 'JÃ³ fekvÃ©sÅ±, szÃ©p kilÃ¡tÃ¡ssal, boros pincÃ©vel meg egyebekkel\r\n\r\nbla bla', '', 3, 1239268646, 1241128799, 'valahol@valaki.hu', '1m9rnlzx', 'Teszt Lajos', '01234567', 957131999, 'BÃ¡rgyÃº BÃ©la'),
(7, 'Ãšj CsalÃ¡di hÃ¡z / Villa, 0 szoba', 0, 0, 0, 0, 0, 0.0, 50, 10, 0.0, 0, '', '', 3, 1239790689, 1241128799, 'sdy@gmil.com', 'cN2newT5', 'Alma Barack', '0233532', 1241128799, 'alma'),
(8, 'Ãšj CsalÃ¡di hÃ¡z / Villa, 0 szoba', 0, 0, 0, 0, 0, 0.0, 50, 10, 0.0, 0, '', '', 3, 1239790706, 1241128799, 'sdy@gmil.com', 'cN2newT5', 'Alma Barack', '0233532', 1241128799, ''),
(9, 'BÃ©relhetÅ‘ ÃœzlethelyisÃ©g, 0 szoba', 1, 8, 0, 6, 2, 0.0, 150, 125000, 2.0, 2, 'CÃ©geknek igazÃ¡n elÅ‘nyÃ¶s, kÃ¶nnyÅ± megkÃ¶zelÃ­thetÅ‘sÃ©g.', '', 3, 1239792431, 1241128799, 'dudika@huhu.hu', 'epu3c8yv', 'DudÃ¡s Ferenc', '202580066', 1241128799, ''),
(10, 'Ãšj CsalÃ¡di hÃ¡z / Villa, 0 szoba', 0, 0, 0, 0, 0, 0.0, 50, 10, 0.0, 0, '', '', 3, 1239793838, 1241128799, 'sdy@gmil.com', 'cN2newT5', 'Alma Barack', '0233532', 1241128799, ''),
(11, 'HasznÃ¡lt TÃ¡rsashÃ¡z, 3 szoba', 0, 0, 2, 3, 1, 3.0, 65, 18000000, 0.0, 0, 'tÃ¶k jÃ³', '', 3, 1239796978, 1241128799, 'profitli@freestart.hu', 'video', 'levente', '302005645', 1241128799, ''),
(12, 'Ãšj TÃ¡rsashÃ¡z, 5 szoba', 0, 0, 2, 3, 0, 5.0, 45, 16000000, 0.0, 1, 'Levi bÃ¡\\'' menÅ‘zik!', '', 3, 1239797073, 1241128799, 'antal@freemail.hu', 'video', 'nagy antal', '06203124334', 1241128799, 'Botya'),
(13, 'HasznÃ¡lt PanellakÃ¡s, 4 szoba', 0, 0, 2, 2, 1, 4.0, 65, 14000000, 0.0, 0, 'tÃ¶k jÃ³ vÃ³na', '', 3, 1240122803, 1242424799, 'sparheltkifozde@t-online.hu', 'liÃ¡na', 'levi', '305435656', 1241128799, ''),
(14, 'BÃ©relhetÅ‘ NyaralÃ³, 3 szoba', 1, 3, 0, 4, 2, 3.0, 135, 130000, 1.0, 1, 'KÃ¶zel a parthoz, stÃ©ggel egyÃ¼tt kiadÃ³.\r\nCsendes kÃ¶rnyezet, kikapcsolÃ³dni vÃ¡gyÃ³ emberek elÅ‘nyben!', 'vhol vmilyen utca 6.', 3, 1240142837, 1241128799, 'kindlazoltan@gmail.com', 'barack', 'Kindla ZoltÃ¡n', '202580066', 1241128799, ''),
(15, 'Ãšj Telek, 0 szoba', 1, 12, 0, 5, 0, 0.0, 1000, 25000000, 0.0, 0, 'OszthatÃ³ telek!', '', 2, 1240172124, 1241128799, 'kindlazoltan@gmail.com', 'epu3c8yv', 'Kindla ZoltÃ¡n', '202580066', 946767599, 'KovÃ¡cs ZoltÃ¡n'),
(16, 'HasznÃ¡lt PanellakÃ¡s, 1.5 szoba', 0, 0, 0, 2, 1, 1.5, 50, 90000, 0.0, 0, 'Valami, Ã©s blablabla', '', 2, 1240231125, 1241128799, 'gecman@gmail.com', 'alma', 'Kiss Pista', '02134657', 1241128799, 'KovÃ¡cs ZoltÃ¡n'),
(17, 'BÃ©relhetÅ‘ TÃ¡rsashÃ¡zi lakÃ¡s, 3 szoba', 0, 0, 7, 3, 2, 3.0, 65, 89000, 0.0, 0, 'HalÃ¡l nyugodt, csendes, megbÃ­zhatÃ³ kÃ¶rnyÃ©k.', 'Leonardo da Vinci u. 45.', 3, 1240246881, 1241128799, 'kindlazoltan@gmail.com', 'epu3c8yv', 'Kindla ZoltÃ¡n', '202580066', 1241128799, 'KovÃ¡cs ZoltÃ¡n');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `stat`
--

CREATE TABLE IF NOT EXISTS `stat` (
  `hiid` int(10) unsigned NOT NULL,
  `szamlalo` int(10) unsigned NOT NULL default '1',
  `datum_ev` int(4) unsigned NOT NULL,
  `datum_honap` tinyint(2) unsigned NOT NULL,
  `datum_nap` tinyint(2) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Tábla adatok: `stat`
--

INSERT INTO `stat` (`hiid`, `szamlalo`, `datum_ev`, `datum_honap`, `datum_nap`) VALUES
(7, 1, 2009, 4, 16),
(9, 3, 2009, 4, 17),
(2, 6, 2009, 4, 17),
(4, 9, 2009, 4, 17),
(5, 4, 2009, 4, 17),
(10, 4, 2009, 4, 17),
(7, 8, 2009, 4, 17),
(0, 95, 2009, 4, 17),
(8, 5, 2009, 4, 17),
(12, 1, 2009, 4, 17),
(11, 1, 2009, 4, 17),
(6, 3, 2009, 4, 17),
(3, 3, 2009, 4, 17),
(1, 3, 2009, 4, 17),
(0, 184, 2009, 4, 18),
(3, 4, 2009, 4, 18),
(7, 7, 2009, 4, 18),
(9, 6, 2009, 4, 18),
(8, 3, 2009, 4, 18),
(6, 3, 2009, 4, 18),
(4, 4, 2009, 4, 18),
(2, 4, 2009, 4, 18),
(5, 2, 2009, 4, 18),
(12, 6, 2009, 4, 18),
(11, 7, 2009, 4, 18),
(10, 7, 2009, 4, 18),
(1, 5, 2009, 4, 18),
(0, 379, 2009, 4, 19),
(2, 9, 2009, 4, 19),
(11, 6, 2009, 4, 19),
(1, 14, 2009, 4, 19),
(12, 12, 2009, 4, 19),
(10, 9, 2009, 4, 19),
(9, 8, 2009, 4, 19),
(8, 9, 2009, 4, 19),
(6, 9, 2009, 4, 19),
(7, 11, 2009, 4, 19),
(3, 7, 2009, 4, 19),
(5, 6, 2009, 4, 19),
(4, 6, 2009, 4, 19),
(13, 13, 2009, 4, 19),
(14, 5, 2009, 4, 19),
(15, 2, 2009, 4, 19),
(0, 1, 0, 0, 0),
(14, 6, 2009, 4, 20),
(0, 397, 2009, 4, 20),
(7, 7, 2009, 4, 20),
(9, 5, 2009, 4, 20),
(1, 6, 2009, 4, 20),
(15, 3, 2009, 4, 20),
(4, 8, 2009, 4, 20),
(3, 3, 2009, 4, 20),
(2, 3, 2009, 4, 20),
(13, 9, 2009, 4, 20),
(8, 5, 2009, 4, 20),
(11, 4, 2009, 4, 20),
(10, 6, 2009, 4, 20),
(12, 6, 2009, 4, 20),
(5, 3, 2009, 4, 20),
(16, 4, 2009, 4, 20),
(6, 1, 2009, 4, 20),
(17, 1, 2009, 4, 20),
(0, 87, 2009, 4, 21),
(2, 1, 2009, 4, 21),
(4, 2, 2009, 4, 21),
(8, 2, 2009, 4, 21),
(12, 1, 2009, 4, 21),
(1, 4, 2009, 4, 21),
(9, 4, 2009, 4, 21),
(7, 1, 2009, 4, 21),
(10, 2, 2009, 4, 21),
(17, 1, 2009, 4, 21),
(11, 1, 2009, 4, 21),
(15, 1, 2009, 4, 21),
(3, 1, 2009, 4, 21),
(0, 284, 2009, 4, 22),
(11, 4, 2009, 4, 22),
(8, 11, 2009, 4, 22),
(1, 4, 2009, 4, 22),
(13, 2, 2009, 4, 22),
(7, 6, 2009, 4, 22),
(5, 1, 2009, 4, 22),
(9, 4, 2009, 4, 22),
(17, 2, 2009, 4, 22),
(15, 11, 2009, 4, 22),
(4, 4, 2009, 4, 22),
(12, 2, 2009, 4, 22),
(3, 3, 2009, 4, 22),
(10, 9, 2009, 4, 22),
(2, 3, 2009, 4, 22),
(14, 3, 2009, 4, 22),
(16, 1, 2009, 4, 22),
(0, 141, 2009, 4, 23),
(8, 2, 2009, 4, 23),
(2, 3, 2009, 4, 23),
(16, 1, 2009, 4, 23),
(15, 1, 2009, 4, 23),
(14, 1, 2009, 4, 23),
(13, 1, 2009, 4, 23),
(4, 1, 2009, 4, 23),
(9, 1, 2009, 4, 23),
(10, 7, 2009, 4, 23);
