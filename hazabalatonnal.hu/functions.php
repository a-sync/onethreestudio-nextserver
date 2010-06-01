<?php
define('_HAB_', 1);
$admin = '';
$error = '';

require('config.php');

//error_reporting(0);
error_reporting(E_ALL ^ E_NOTICE);

mysql_connect($db['host'],$db['user'],$db['pass'], false) or sql_error();
mysql_select_db($db['db']) or sql_error();

@session_start();


/* funkciok */
function sql_error($q)
{
  if($q != '') $q = ' ('.htmlspecialchars($q).')';
  //die('#'.mysql_errno());
  die(mysql_errno().': '.mysql_error().$q);
}

function sql($q)
{
  return mysql_query($q) or sql_error($q);
}

function e($s)
{
  return mysql_real_escape_string($s);
}
?>