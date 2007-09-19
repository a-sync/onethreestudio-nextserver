<?php
$ots_db_host = 'localhost';
$ots_db_user = 'onethreestudio';
$ots_db_pass = 'va1chaiV';
$ots_db_name = 'onethreestudio';

$connection = @mysql_connect($ots_db_host, $ots_db_user, $ots_db_pass) or die('Failed to connect to the database server. Sorry.<br/>');
@mysql_select_db($ots_db_name, $connection) or die('Failed to connect to the database. Sorry.<br/>');
?>