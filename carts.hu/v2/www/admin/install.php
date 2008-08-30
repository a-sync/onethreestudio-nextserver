<?php
define('_OTS_ADMIN_', 135);
require('config.php');

if(file_exists('installed.txt')) { die('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>die!die!die! - töröld az `installed.txt` fájlt és újra próbálkozhatsz.</body></html>'); }
@touch('installed.txt');

$charset = 'utf8';
$collate = 'utf8_general_ci';

mysql_query("CREATE TABLE `galeria` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `parent_id` int(10) unsigned NOT NULL,
  `name` tinytext collate $collate NOT NULL,
  `priority` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=$charset COLLATE=$collate AUTO_INCREMENT=1;")
 or die('<html><body>++ Error: ' . mysql_error() . '<br/>++ File: ' . __FILE__ . '<br/>++ Line: ' . __LINE__ . '</body></html>');

mysql_query("CREATE TABLE `hirek` (
  `hir_id` int(10) unsigned NOT NULL auto_increment,
  `title` tinytext collate $collate NOT NULL,
  `text` mediumtext collate $collate NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `priority` smallint(5) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `meta` tinytext collate $collate NOT NULL,
  PRIMARY KEY  (`hir_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=$charset COLLATE=$collate AUTO_INCREMENT=1;")
 or die('<html><body>++ Error: ' . mysql_error() . '<br/>++ File: ' . __FILE__ . '<br/>++ Line: ' . __LINE__ . '</body></html>');

mysql_query("CREATE TABLE `kepek` (
  `pic_id` int(10) unsigned NOT NULL auto_increment,
  `cat_id` int(10) unsigned NOT NULL,
  `title` tinytext collate $collate NOT NULL,
  `desc` text collate $collate NOT NULL,
  `tags` text collate $collate NOT NULL,
  `file` text collate $collate NOT NULL,
  PRIMARY KEY  (`pic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=$charset COLLATE=$collate AUTO_INCREMENT=1;")
 or die('<html><body>++ Error: ' . mysql_error() . '<br/>++ File: ' . __FILE__ . '<br/>++ Line: ' . __LINE__ . '</body></html>');

$other = (file_exists('installed.txt')) ? 'Az admin mappában van egy installed.txt fájl ami meggátolja az install indítását. (alap esetben sem írja felül az adatbázist)' : 'Hozz létre az admin mappában egy installed.txt fájlt, hogy meggátold az install újabb indítását. (alap esetben sem írja felül az adatbázist)';

die('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>++ Status: Database structure uploaded.<br/>++ Other: ' . $other . '<br/>++ Error: ' . (mysql_error() ? mysql_error() : 'none') . '</body></html>');

?>