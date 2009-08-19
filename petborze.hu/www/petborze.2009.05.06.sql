-- phpMyAdmin SQL Dump
-- version 2.10.0.2
-- http://www.phpmyadmin.net
-- 
-- Hoszt: localhost
-- Létrehozás ideje: 2009. Máj 06. 07:32
-- Szerver verzió: 5.0.27
-- PHP Verzió: 4.3.11RC1-dev

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Adatbázis: `petborze`
-- 

-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `esemenyek`
-- 

CREATE TABLE `esemenyek` (
  `esid` int(10) unsigned NOT NULL auto_increment,
  `hiid` int(10) unsigned NOT NULL default '0',
  `text` text collate utf8_unicode_ci NOT NULL,
  `statusz` tinyint(1) unsigned NOT NULL default '0',
  `prioritas` tinyint(1) unsigned NOT NULL default '0',
  `datum` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`esid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tábla adatok: `esemenyek`
-- 


-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `hirdetesek`
-- 

CREATE TABLE `hirdetesek` (
  `hiid` int(10) unsigned NOT NULL auto_increment,
  `hirdetes_cime` tinytext collate utf8_unicode_ci NOT NULL,
  `regio` int(10) unsigned NOT NULL,
  `telepules` int(10) unsigned NOT NULL,
  `varosresz` int(10) unsigned NOT NULL,
  `faj` int(10) unsigned NOT NULL,
  `fajta` int(10) unsigned NOT NULL,
  `kor` int(10) unsigned NOT NULL,
  `ar` int(10) unsigned NOT NULL,
  `pedigre` int(10) unsigned NOT NULL,
  `nem` int(10) unsigned NOT NULL,
  `megjegyzes` text collate utf8_unicode_ci NOT NULL,
  `cim` text collate utf8_unicode_ci NOT NULL,
  `statusz` int(10) unsigned NOT NULL default '0',
  `datum` int(10) unsigned NOT NULL,
  `ervenyes_datum` int(10) unsigned NOT NULL default '0',
  `email` tinytext collate utf8_unicode_ci NOT NULL,
  `jelszo` tinytext collate utf8_unicode_ci NOT NULL,
  `nev` tinytext collate utf8_unicode_ci NOT NULL,
  `telefon` tinytext collate utf8_unicode_ci NOT NULL,
  `kiemelt_datum` int(10) unsigned NOT NULL default '0',
  `uzletkoto` tinytext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`hiid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Tábla adatok: `hirdetesek`
-- 


-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `stat`
-- 

CREATE TABLE `stat` (
  `hiid` int(10) unsigned NOT NULL,
  `szamlalo` int(10) unsigned NOT NULL default '1',
  `datum_ev` int(4) unsigned NOT NULL,
  `datum_honap` tinyint(2) unsigned NOT NULL,
  `datum_nap` tinyint(2) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Tábla adatok: `stat`
-- 

