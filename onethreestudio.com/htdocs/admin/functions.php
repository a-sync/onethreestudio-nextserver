<?php
//debug E_ALL
error_reporting(E_ALL ^ E_NOTICE);//nemkellenek hibaüzenetek: 0
//error_reporting(0);//nemkellenek hibaüzenetek: 0

require('config.php');

$connection = /*@*/mysql_connect($ots_db_host, $ots_db_user, $ots_db_pass) or die('Nem sikerült kapcsolódni az adatbázishoz!');
mysql_select_db($ots_db_name, $connection);

/* - - - - - HEADER/MENU/FOOTER - - - - - */
function head($public = 'no') {
  if($public == 'no') {
    reqLogin();
  }
  echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2"></head><body>';
}

function menu($menuRoot = '') {
  $spacing = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';//debug

  echo '<a href="'.$menuRoot.'info.php">Portfolio info kezelése</a>'.$spacing;
  echo '<a href="'.$menuRoot.'references.php">Referencia lista kezelése</a>'.$spacing;
  echo '<a href="'.$menuRoot.'upload.php">Fájlok kezelése, fájl feltöltése</a>'.$spacing;
  echo '<a href="'.$menuRoot.'jsgd/index.php">Kép vágása, feltöltése</a>'.$spacing;
  echo '<a href="'.$menuRoot.'logout.php">Kijelentkezés!</a>'.$spacing;

  echo '<br /><br />';
}

function foot() {
  echo '</body></html>';
}

/* - - - - - USER CHECK - - - - - */
function reqLogin($loginRedir = 'login.php') {
  if(checkLogin() != 'logged_in') { redir($loginRedir); }
}

function checkLogin() {
  if(isset($_COOKIE['c_ots_uid']) && isset($_COOKIE['c_ots_pass'])) {
    $ident = checkIdent($_COOKIE['c_ots_uid'], $_COOKIE['c_ots_pass']);
    if($ident != 'logged_in') { delCookie(); }
    return $ident;
  }
  else {
    return 'no_cookie';
  }
}

function checkIdent($ots_uid, $ots_pass) {
  include('config.php');//fix
  for($i=0; $i < count($ots_userName); $i++) {
    if(mb_strtolower($ots_userName[$i]) == mb_strtolower($ots_uid)) {
      for($j=0; $j < count($ots_userPass); $j++) {
        if($ots_userPass[$i] == $ots_pass) {
          return 'logged_in';
        }
      }
      return 'bad_pass';
    }
  }
  return 'bad_name';
}

/*
function ident($name, $pass) {
	$passB64hex = bin2hex(base64_encode($pass));
	$nameB64hex = bin2hex(base64_encode($name));
	$SaltedHash = sha1($nameB64hex.$passB64hex.$nameB64hex);
	$ident = md5($SaltedHash);
	return $ident;
}
*/

/* - - - - - DIRECTORY LISTS - - - - - */
function dirList($theDir) {
  if(is_dir($theDir)) {
    if($dh = opendir($theDir)) {
      while(($file = readdir($dh)) !== false) {
        $type = is_dir($theDir.$file) ? 'dir' : 'file';
        $data[] = array('name'=>$file, 'type'=>$type, 'size'=>filesize($theDir.$file), 'modded'=>filemtime($theDir.$file));
      }
      return $data;
      closedir($dh);
    }
  }
  else { die('Hiba: helytelen mappa elérés fájllistázásnál: '.$theDir); }
}

function fs($size) {
  $count = 0;
  $format = array('b','kB','MB','GB');

  for($i=0; ($size / 1024) > 1 && $i <= 3; $i++) {
    $size = $size / 1024;
  }
  if($i != 0) { $dec = 2; }
  else { $dec = 0; }

  return number_format($size,$dec,'.',' ').'&nbsp;'.$format[$i];
}

/* - - - - - MISC - - - - - */
function tag_esc($str) {
  $str = str_replace('<', '&lt;', $str);
  $str = str_replace('>', '&gt;', $str);
  $str = str_replace('"', '&quot;', $str);
//  $str = str_replace('  ', ' &nbsp;', $str);
//  $str = str_replace('`', '\'', $str);

  return stripslashes($str);
}

function esc($str) {
  $str = str_replace('  ', ' &nbsp;', $str);
//  $str = str_replace("'", "&quot;", $str);
  return $str;//debug
//  return mysql_real_escape_string($str);
}

function redir($redir, $trusted = 'yes') {
  if($trusted != 'yes') { $redir = rawurlencode($redir); }
  echo '<script type="text/javascript">document.location="'.$redir.'";</script>';
  exit;
}

/* - - - - - COOKIE HANDLING - - - - - */
function addCookie($ots_uid, $ots_pass) {
	$time = time() + 60 * 60 * 12;//12 órán át érvényes a süti

	setcookie('c_ots_uid', $ots_uid, $time, '/', $_SERVER['HTTP_HOST']);
	setcookie('c_ots_pass', $ots_pass, $time, '/', $_SERVER['HTTP_HOST']);
}

function delCookie() {
	$time = time() - 60 * 60 * 24 * 1;

	setcookie('c_ots_uid', '', $time, '/', $_SERVER['HTTP_HOST']);
	setcookie('c_ots_pass', '', $time, '/', $_SERVER['HTTP_HOST']);
}
?>