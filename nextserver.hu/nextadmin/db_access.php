<?php

$sqllink = mysql_connect('localhost', 'nextserv_admin', '13swork') or die('Cant connect to the database because: ' . mysql_error());
mysql_select_db('nextserv_nextadmin') or die('No such db');
mysql_query("SET NAMES 'utf8'");

?>