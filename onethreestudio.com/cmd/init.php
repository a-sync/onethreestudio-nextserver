<?php
//functions
function e($str = '', $s = 0, $l = false, $q = ENT_QUOTES) {
  if($str == '') { global $i; $str = $i; }
  if($l === false) { $l = strlen($str); }
  return htmlspecialchars(substr($str, $s, $l), $q);
}

function l($w = '', $s = 0, $l = false) {
  global $i;
  if($l === false) { $l = strlen($w); }
  if(strtolower(substr($i, $s, $l)) == strtolower($w)) { return true; }
  else { return false; }
}

function q($s = '') {
  global $i;
  if(strtolower($s) == strtolower($i)) return true;
  else return;
}

function _query($q) {
  $r = @mysql_query($q) or die('Failed to get the work entry\'s from the database. Sorry.<br/>');
  return $r;
}

function p0($s = '', $p = false) {
  if($p === false) $p = $s;
  return '<a href="javascript:p0(\''.$p.'\')">'.$s.'</a>';
}

function p1($s = '', $p = false) {
  if($p === false) $p = $s;
  return '<a href="javascript:p1(\''.$p.'\')">'.$s.'</a>';
}

function p2($s = '', $p = false) {
  if($p === false) $p = $s;
  return '<a href="javascript:p2(\''.$p.'\')">'.$s.'</a>';
}

//initialization
$i = urldecode($_POST['i']);
$e = '';
?>