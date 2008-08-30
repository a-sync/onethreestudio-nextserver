-- phpMyAdmin SQL Dump
-- version 2.10.0.2
-- http://www.phpmyadmin.net
-- 
-- Hoszt: localhost
-- Létrehozás ideje: 2008. Szept 29. 21:12
-- Szerver verzió: 5.0.27
-- PHP Verzió: 4.3.11RC1-dev

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Adatbázis: `carts`
-- 

-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `galeria`
-- 

CREATE TABLE `galeria` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `parent_id` int(10) unsigned NOT NULL,
  `name` tinytext collate utf8_hungarian_ci NOT NULL,
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `hirek`
-- 

CREATE TABLE `hirek` (
  `hir_id` int(10) unsigned NOT NULL auto_increment,
  `title` tinytext collate utf8_hungarian_ci NOT NULL,
  `text` mediumtext collate utf8_hungarian_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `priority` smallint(5) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`hir_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=9 ;


-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `kepek`
-- 

CREATE TABLE `kepek` (
  `pic_id` int(10) unsigned NOT NULL auto_increment,
  `cat_id` int(10) unsigned NOT NULL,
  `title` tinytext collate utf8_hungarian_ci NOT NULL,
  `desc` text collate utf8_hungarian_ci NOT NULL,
  `file` text collate utf8_hungarian_ci NOT NULL,
  PRIMARY KEY  (`pic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=5 ;

