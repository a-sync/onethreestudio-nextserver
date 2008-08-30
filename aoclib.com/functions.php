<?php
/* FUNCTIONS.PHP */
//Role Player's Library

//error_reporting(E_ALL);//debug: E_ALL
error_reporting(E_ALL ^ E_NOTICE);//normál
//error_reporting(0);//nemkellenek hibaüzenetek: 0
//set_time_limit(0);

//a multiple selectbox-os infok foreach-es kipörgetése helyett egybõl mehet az implode funkcioba,
// és preg matchal utána megnézni h csak szám / | jel van-e benne, ha nem, akkor error vissza
// - ellenõrizni az adatbázisból kikérve hogy a küldött választások érvényesek-e egyáltalán

require('config.php');

$rpl_connection = /*@*/mysql_connect($rpl['dbhost'], $rpl['dbuser'], $rpl['dbpass']) or die('Culd not connect to the database server!');
/*@*/mysql_select_db($rpl['dbname'], $rpl_connection) or die('Culd not connect to the database!');

$_HOST = 'http://'.$_SERVER['HTTP_HOST'].'/';

/* - - - - - Document Functions - - - - - */
function head($title = false, $content_title = false, $_USER = 'NULL') {
  global $_HOST;

  if($_USER === 'NULL') { $_USER = checkLogin(); }
  $css = $_HOST.'default.css';
  $menu_css = $_HOST.'vec_jsmenu.css';
  $menu_js = $_HOST.'vec_jsmenu.js';

  $title = ($title !== false) ? esc($title, 2) : 'www.AoCLib.com - the Age of Conan database!';
  $navigation = navigation($_USER);
  $content_title = ($content_title !== false) ? '<div id="content_title">'.$content_title.'</div>' : '<div id="content_title" style="height: 5px;"></div>';

  header("Content-Type: text/html; charset=iso-8859-2");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2002/REC-xhtml1-20020801/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />

<?php /*
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="description" content="www.AoCLib.com - the age of conan database!" />
<meta name="keywords" content="age of conan, database, conan, db, aoc, lib, library, quests, items, npcs, zones, maps" />
<meta name="copyright" content="www.aoclib.com" />
*/ ?>

<script type="text/javascript" src="<?php echo $menu_js; ?>"></script>
<link href="<?php echo $css; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo $menu_css; ?>" rel="stylesheet" type="text/css" />

<title><?php echo $title; ?></title>

<!--[if lte IE 7]>
<link href="ie_fix.css" rel="stylesheet" type="text/css" />
<![endif]-->

</head>
<body>

<div id="container">
  <div id="header">

    <div id="header_nav" class="va_table">
      <span class="va_cell">
        <?php echo $navigation; ?>
      </span>
      <!--[if lte IE 7]><b class="va_ie"></b><![endif]-->
    </div>

    <div id="header_logo"><div id="logo" onclick="window.location='http://www.aoclib.com'"></div></div>

    <div id="header_menu">
      <div id="menu">

<!-- Menu start -->
<?php include('jsmenu.php'); ?>
<!-- Menu end -->

        <?php /*<div id="mini_search"><input type="text"/></div>*/ ?>

      </div>
    </div>

  </div>

  <div id="content">
    <?php echo $content_title; ?>

<?php
  //return cached catlist?
}

function navigation($_USER) {
  global $_HOST;
  $nav = '';
  $sep = ' &nbsp; ';

  if($_USER === false) {
    $nav .= '[<a href="'.$_HOST.'login.php">Login</a>]'.$sep;
    $nav .= '[<a href="'.$_HOST.'register.php">Register</a>]';
  }
  else {
    if(minClass($_USER, 10)) {
      $nav .= 'Admin:'.$sep;
      if(minClass($_USER, 20)) { $nav .= '[<a href="'.$_HOST.'icons.php">Icons</a>]'.$sep; }
      if(minClass($_USER, 20)) { $nav .= '[<a href="'.$_HOST.'quick_infos.php">Quick infos</a>]'.$sep; }
      if(minClass($_USER, 30)) { $nav .= '[<a href="'.$_HOST.'users.php">Users</a>]'.$sep; }
      if(minClass($_USER, 30)) { $nav .= '[<a href="'.$_HOST.'categories.php">Categories</a>]'.$sep; }

      $nav .= '|'.$sep;
    }

    $nav .= 'Welcome '.esc($_USER['username'], 2).'!'.$sep;
    $nav .= '[<a href="'.$_HOST.'logout.php">Logout</a>]';
  }

  return $nav;
}

function foot() {
?>

  </div>

  <div id="footer">

    <a href="policy.php">Privacy policy</a> &nbsp;
    <a href="faq.php">FAQ</a> &nbsp;
    <a href="about.php">About us</a> &nbsp;
    <a href="advertise.php">Advertise</a> &nbsp;
    <a href="contact.php">Contact</a> &nbsp;
    <br /> 
    <br />
    <br />
    &copy; 2008 <a href="http://www.aoclib.com">www.aoclib.com</a>
  </div>               

</div>

</body>
</html>

<?php
}

function box($p, $text = false) {
  if($p == 0) {
    //if($text !== false) { echo '<div class="boxhead">'.$text.'</div>'; } //egyenlõre nincs boxhead
    echo '<div class="box">';
  }
  elseif($p == 1) {
    echo '</div>';
  }
}

function redir($redir = 'index.php', $delay = false, $exit = false) {
  global $_HOST;
  $redirLoc = $redir;//szûrés?

  if(substr($redirLoc, 0, 7) != 'http://') {
    //$redirLoc = $_HOST.rtrim(dirname($_SERVER['PHP_SELF']), '/\\').'/'.$redirLoc;
    $redirLoc = $_HOST.$redirLoc;//nincsenek php-k mappákban
  }

  if(is_numeric($delay)) {
    echo '<script type="text/javascript">setTimeout("window.location=\''.$redirLoc.'\'", '.$delay.');</script>';
    if($exit) { exit; }
  }
  else {
    header("Location: $redirLoc");
    exit;
  }
}

function timeout($seconds) {
  usleep(number_format(($seconds * 1000000), 0, '.', ''));
}




/* - - - - - user Functions - - - - - */
function checkLogin($exit = false) {
  if(isset($_COOKIE['rpl_usid']) && isset($_COOKIE['rpl_password'])) {
    $usid = esc($_COOKIE['rpl_usid'], 1);
    $password = esc($_COOKIE['rpl_password'], 1);

    $query = "SELECT * FROM `rpl_users` WHERE `usid` = '$usid' AND `password` = '$password'";
    $result = sql_query($query);
    $resultnum = mysql_num_rows($result);

    if($resultnum < 1 || $usid == '' || $password == '') {//log
      if($exit) {
        delCookie();
        redir('login.php?error=3');
      }
      return false;
    }
    else {
      //if $resultnum > 1 //log // die
      $_USER = mysql_fetch_array($result, MYSQL_ASSOC);

      /*if($_USER['status'] == '0') {
        if($exit) {
          delCookie();
          redir('login.php?error=2', true);
        }
        return false;
      }
      elseif($_USER['verif'] != '') {
        if($exit) {
          delCookie();
          redir('login.php?error=3', true);
        }
        return false;
      }*/
      //else {
        return $_USER;
      //}
    }
  }
  else {
    if($exit) { redir('login.php'); }
    return false;
  }
}

function addCookie($uid, $password) {
  $time = time() + 60 * 60 * 24 * 1;//1 napon át érvényes a süti

  setcookie('rpl_usid', $uid, $time, '/', $_SERVER['SERVER_NAME']);
  setcookie('rpl_password', $password, $time, '/', $_SERVER['SERVER_NAME']);
}

function delCookie() {
  $time = time() - 60 * 60 * 24 * 1;

  setcookie('rpl_usid', '', $time, '/', $_SERVER['SERVER_NAME']);
  setcookie('rpl_password', '', $time, '/', $_SERVER['SERVER_NAME']);
}

function ident($name, $rpass, $salt = 'árvíztûrõ * tükörfúrógép') {
  $nameB64hex = bin2hex(base64_encode(strtolower($name).$salt));
  $rpassB64hex = bin2hex(base64_encode($rpass.$salt));

  return md5($nameB64hex.$rpassB64hex.$nameB64hex);
}

function minClass($_USER, $class = 40) {
  if(is_numeric($_USER['class']) && $_USER['class'] >= $class) { return true; }
  else { return false; }
}














/* - - - - - article Functions - - - - - */
function add_related($into, $what, $list_back = true) {
  if(is_array($into)) {

  }
  else {
    $into = (substr($into, 0, 1) == '|') ? substr($into, 1) : $into;
    $into = (substr($into, -1) == '|') ? substr($into, 0, -1) : $into;
    $into = explode('|', $into);
  }
  if(!is_numeric($what)) { die('a beillesztendõ arid szám legyen'); }
  if(in_array($what, $into)) { die('a beillesztendõ arid már szerepel a tömbben'); }

  if(!$into[0]) { unset($into); }
  $into[] = $what;
  return '|'.implode('|', $into).'|';
}

function del_related($from, $what, $list_back = true) {
  if(is_array($from)) {

  }
  else {
    $from = (substr($from, 0, 1) == '|') ? substr($from, 1) : $from;
    $from = (substr($from, -1) == '|') ? substr($from, 0, -1) : $from;
    $from = explode('|', $from);
  }
  if(!is_numeric($what)) { die('a kivonandó arid szám legyen'); }
  if(!in_array($what, $from)) { die('a kivonandó arid (már) nem szerepel a tömbben'); }

  $key = array_search($what, $from);

  if($key === false) { die('a kivonandó arid keresésére nem érkezett találat'); }
  else { unset($from[$key]); }

  if($from) { return '|'.implode('|', $from).'|'; }
  else { return ''; }
}

//$inln elõállítása funkcióba
//$array-ban levo id-ket tartalmazó sql kérés elõállítása //kezelése //futtatása //csoportositasa //or helyett IN(x, y, z, ... n) hasznalata?





/* - - - - - info Functions - - - - - */
function getArtInfo($arid, $perma = false, $status_e = '=', $status = 1) {
  $arid = esc($arid, 1);
  $status = esc($status, 1);

  $query0 = "SELECT * FROM `rpl_articles`";

  if($perma !== false) { $where0 = " WHERE `perma` = '$arid'"; }
  elseif(is_numeric($arid)) { $where0 = " WHERE `arid` = '$arid'"; }
  else { return false; }
  if($status_e !== false) { $where0 .= " AND `status` $status_e '$status'"; }

  $result0 = sql_query($query0.$where0);
  return mysql_fetch_array($result0, MYSQL_ASSOC);
}

function getCatArticles($caid, $status_e = '=', $status = 1) {
  $caid = esc($caid, 1);
  $status = esc($status, 1);

  $query0 = "SELECT * FROM `rpl_articles`";

  if(!is_numeric($caid)) { return false; }
  $where0 = " WHERE `caid` = '$caid'";
  if($status_e !== false) { $where0 .= " AND `status` $status_e '$status'"; }

  $result0 = sql_query($query0.$where0);
  return sql_fetch_all($result0);
}

function getRelatedStuff($_ARTICLE) {
  if(!is_array($_ARTICLE)) { return false; }

  //related articles method1
  $related_arids = explode('|', substr($_ARTICLE['related'], 1, -1));

  $query0 = "SELECT * FROM `rpl_articles`";
  $where0 = " WHERE `status` = '1' AND (1 = 2";
  foreach($related_arids as $related_arid) { $where0 .= " OR `arid` = '$related_arid'"; }

  $result0 = sql_query($query0.$where0.")");
  //related articles method1


  //related articles method2
  //  $query0 = "SELECT * FROM `rpl_articles` WHERE `status` = '1' AND `related` LIKE '%|{$_ARTICLE['arid']}|%'";
  //
  //  $result0 = sql_query($query0);
  //related articles method2

  //safe mode-ban ellenõrizni hogy a két kérés numbere egyezik-e


  //$resultnum0 = mysql_num_rows($result0);
  //if($resultnum0 < 1) { return false; }

  while($r_article = mysql_fetch_array($result0, MYSQL_ASSOC)) { $RELATED['articles'][] = $r_article; }


  $query1 = "SELECT * FROM `rpl_relations` WHERE `caid` = '{$_ARTICLE['caid']}' AND `status` = '1'";
  $result1 = sql_query($query1);
  //$resultnum1 = mysql_num_rows($result1);
  //if($resultnum1 < 1) { return false; }

  while($r_relation = mysql_fetch_array($result1, MYSQL_ASSOC)) { $RELATED['relations'][] = $r_relation; }


  //related categories
  $query2 = "SELECT * FROM `rpl_categories`";
  $where2 = " WHERE `status` = '1' AND (`caid` = '{$_ARTICLE['caid']}'";
  foreach($RELATED['relations'] as $relation) { $where2 .= " OR `caid` = '{$relation['related_caid']}'"; }

  //$result2 = sql_query($query2.$where2." OR `content` = '0')");// szülõ kategóriák levezetéséhez
  $result2 = sql_query($query2.$where2.")");
  //related categories

  //ha van lekérve kategórialista / akár átküldve, abból is kinyerhetõ

  //$resultnum2 = mysql_num_rows($result2);
  //if($resultnum2 < 1) { return false; }

  while($r_category = mysql_fetch_array($result2, MYSQL_ASSOC)) { $RELATED['categories'][] = $r_category; }


  return $RELATED;
}

function getCatChilds($parent_caid, $status_e = '=', $status = 1) {
  $parent_caid = esc($parent_caid, 1);
  $status = esc($status, 1);

  $cache = true;
  if($cache) {
    $status_e = ($status_e == '=') ? '==' : $status_e;
    $filters[] = array('parent_caid', '==', $parent_caid);
    if($status_e !== false) { $filters[] = array('status', $status_e, $status); }

    return filterArrays(getCatList(), $filters);
  }

  $query0 = "SELECT * FROM `rpl_categories`";

  if(!is_numeric($parent_caid)) { return false; }
  $where0 = " WHERE `parent_caid` = '$parent_caid'";
  if($status_e !== false) { $where0 .= " AND `status` $status_e '$status'"; }

  $result0 = sql_query($query0.$where0);
  return sql_fetch_all($result0);
}

function  getCatInfo($caid, $perma = false, $status_e = '=', $status = 1) {//cache?
  $caid = esc($caid, 1);
  $status = esc($status, 1);

  $query0 = "SELECT * FROM `rpl_categories`";

  if($perma !== false) { $where0 = " WHERE `perma` = '$caid'"; }
  elseif(is_numeric($caid)) { $where0 = " WHERE `caid` = '$caid'"; }
  else { return false; }
  if($status_e !== false) { $where0 .= " AND `status` $status_e '$status'"; }

  $result0 = sql_query($query0.$where0);
  return mysql_fetch_array($result0, MYSQL_ASSOC);
}

function getPicInfo($piid, $perma = false) {
  $piid = esc($piid, 1);

  $query0 = "SELECT * FROM `rpl_pictures`";

  if($perma !== false) { $where0 = " WHERE `perma` = '$piid'"; }
  elseif(is_numeric($piid)) { $where0 = " WHERE `piid` = '$piid'"; }
  else { return false; }

  $result0 = sql_query($query0.$where0);
  return mysql_fetch_array($result0, MYSQL_ASSOC);
}

function getQuickInfo($quid, $perma = false) {
  $quid = esc($quid, 1);

  $query0 = "SELECT * FROM `rpl_quick_infos`";

  if($perma !== false) { $where0 = " WHERE `title` = '$quid'"; }
  elseif(is_numeric($quid)) { $where0 = " WHERE `quid` = '$quid'"; }
  else { return false; }

  $result0 = sql_query($query0.$where0);
  return mysql_fetch_array($result0, MYSQL_ASSOC);
}

function getUserInfo($usid, $perma = false) {
  $usid = esc($usid, 1);

  $query0 = "SELECT * FROM `rpl_users`";

  if($perma !== false) { $where0 = " WHERE `username` = '$usid'"; }
  elseif(is_numeric($usid)) { $where0 = " WHERE `usid` = '$usid'"; }
  else { return false; }

  $result0 = sql_query($query0.$where0);
  return mysql_fetch_array($result0, MYSQL_ASSOC);
}

function catTree($caid, $categories = false, $separator = ' &gt; ', $link = true, $key = 'title') {
  global $_HOST;
  $categories = (!is_array($categories)) ? getCatList() : groupArrays($categories, 'caid', true);

  $tree = esc($categories[$caid][$key], 2);
  if($link !== false) { $tree = '<a href="'.$_HOST.'category/'.$categories[$caid]['perma'].'">'.$tree.'</a>'; }
  $next_cat = $categories[$categories[$caid]['parent_caid']];

  while(is_array($next_cat) && $categories[$caid]['parent_caid'] != 0) {
    if($link !== false) { $tree = '<a href="'.$_HOST.'category/'.$next_cat['perma'].'">'.esc($next_cat[$key], 2).'</a>'.$separator.$tree; }
    else { $tree = esc($next_cat[$key], 2).$separator.$tree; }

    $next_cat = ($next_cat['parent_caid'] == 0) ? false : $categories[$next_cat['parent_caid']];
  }

  return $tree;
}

function getSubCats($_CATEGORY, $categories = false) {
  $catList = (!is_array($categories)) ? getCatList() : getCatList($categories);
  $child = false;

  foreach($catList as $cat) {
    if($child === true && $_CATEGORY['level'] >= $cat['level']) { $child = false; }//break
    elseif($child === true) { $childs[$cat['caid']] = $cat; }//$childs[] = $cat; //debug //a csoportosítás szükséges!
    elseif($cat['caid'] == $_CATEGORY['caid']) { $child = true; }
  }

  return $childs;
}

function permaHandling($plus = '') {
  $found_perma = false;

  if($_GET['id'] != '') { $perma_link = $_GET['id']; }
  else {
    $perma_link = ($_GET['p'] != '') ? $_GET['p'] : substr(strrchr($_SERVER["PATH_INFO"], '/'), 1);
  //& résztõl lecsapni, és $_GET[''] változókat bepakolni
    if($perma_link == '') {
      foreach($_GET as $key => $val) {
        if($key != '' && $found_perma === false) {
          $perma_link = $key;
          $found_perma = true;
          //break ?
        }
      }
    }
    else { $found_perma = true; }
  }

  return array(makePermalink($perma_link, $plus), $found_perma);
}


/* - - - - - Kategóriák kezelése - - - - - */
function getCategories() {
  //$query0 = "SELECT * FROM `rpl_categories` WHERE `status` = '1'";
  //$query0 = "SELECT * FROM `rpl_categories` ORDER BY `title` ASC";
  $query0 = "SELECT * FROM `rpl_categories`";
  $result0 = sql_query($query0);
  $resultnum0 = mysql_num_rows($result0);

  while($category = mysql_fetch_array($result0, MYSQL_ASSOC)) { $categories[] = $category; }

  return $categories;
}

function getCatList($categories = false, $cache = true) {//automatikus cache a végén? //ha nincs cache fájl
  if($cache === true && $categories === false && file_usable('categories.cache.php')) {
    include('categories.cache.php');

    return unserialize($_CACHE['catlist']);
  }
  $categories = (!is_array($categories)) ? getCategories() : $categories;

  $group_parents = groupArrays($categories, 'parent_caid');
  $categories = sortArrays($categories, 'level', false, true);

  foreach($group_parents as $key => $group_childs) {
    $parents[$key] = sortArrays($group_childs, 'title');
  }

  $catList = $parents[0];

  foreach($categories as $category) {
    $catList = squeezeArrays($catList, 'caid', $category['caid'], $parents[$category['caid']]);
  }

  return groupArrays($catList, 'caid', true);
}


/* - - - - - Tömbök manipulálása - - - - - */
//a //break jelölésnél belehúzható az összes alattalévõ,
// így a legalso foreach elmaradhat és szimplán az elsõ folytatódna
/*
function _squeezeArrays($inArray, $afterKey, $afterValue, $theArray) {
  $trigger1 = true;
  foreach($inArray as $thisKey) {
    if($trigger1) { $completeArray[] = $thisKey; }
    if($thisKey[$afterKey] == $afterValue) { $trigger1 = false; }//break
  }

  foreach($theArray as $thisKey) { $completeArray[] = $thisKey; }

  $trigger2 = false;
  foreach($inArray as $thisKey) {
    if($thisKey[$afterKey] == $afterValue) {
      unset($thisKey);
      $trigger2 = true;
    }
    if($trigger2 && $thisKey) { $completeArray[] = $thisKey; }
  }

  return $completeArray;
}
*/

function squeezeArrays($inArray, $afterKey, $afterValue, $theArray, $insertCount = 1) {
  $squeezed = 0;

  foreach($inArray as $thisKey) {
    $completeArray[] = $thisKey;

    if($thisKey[$afterKey] == $afterValue && ($insertCount > $squeezed || $insertCount == 'all')) {
      foreach($theArray as $thisKey) { $completeArray[] = $thisKey; }
      $squeezed++;
    }
  }

  return $completeArray;
}

//szamokkal miert nem mukodik rendesen? ellenorizni!
function sortArrays($arrays, $sort_key, $reverse = false, $numeric = false) {
  foreach($arrays as $key => $val) {
    $sorter[$key] = $val[$sort_key];//(string)
  }

  if($numeric) {
    if($reverse) { arsort($sorter); }
    else { asort($sorter); }
  }
  else {
    natcasesort($sorter);
    if($reverse) { rsort($sorter); }
  }

  foreach($sorter as $key => $val) {
    $sorter[$key] = $arrays[$key];
  }

  return $sorter;
}

function groupArrays($arrays, $key, $unique = false) {
  if($unique) {
    foreach($arrays as $array) {
      $group[$array[$key]] = $array;
    }
  }
  else {
    foreach($arrays as $array) {
      $group[$array[$key]][] = $array;
    }
  }

  return $group;
}

/**
 * filterArrays() több tömb szûrése a bennük lévõ értékeik alapján.
 *
 * @param array $arrays - a szûrni kívánt tömböket tartalmazó tömb
 * @param array $filters - szûrõ tömbök (több szûrõ tömb esetén az összes egyetlen nagy tömb része legyen)
 * @param array $filters eg.: array(
 *  '(string|int) ellenõrizendõ kulcs', 
 *  '(string) (<, >, <=, >=, !=, !==, ==, ===) ellenõrzõ operátor', 
 *  '(mixed) összehasonlító érték' 
 *  [, 'helyes eredmény (true|false) amivel átmegy a szûrõn (dafeault: true)']
 * );
 * 
 * @param bool $inverse - a szûrés alatt kiesett tömböket adja-e vissza
 * @param bool $all - true = mindegyik filternek megkell felelnie (AND), elég az egyik filternek megfelelnie (OR)
 * 
 * @return array - tömb a szõrõknek megfelelt tartalommal, vagy inverse = true esetén az ellenkezõkkel
 */
function filterArrays($arrays, $filters, $inverse = false, $all = true) {
  if(!is_array(current($filters))) { 
    $filtered_array = $filters;
    unset($filters);
    $filters[0] = $filtered_array;
  }
  $filtered_array = '';

  foreach($arrays as $a) {
    $passed = true;
    $or_passed = false;

    foreach($filters as $f) {
      if(!is_array($f)) { $f = $filters; }
      if($f[3] !== false) { $f[3] = true; }
      if($passed) {
        $passed = false;
        switch($f[1]) {
          case '<':
            if($a[$f[0]] < $f[2]) { $passed = true; }
            break;
          case '>':
            if($a[$f[0]] > $f[2]) { $passed = true; }
            break;
          case '<=':
            if($a[$f[0]] <= $f[2]) { $passed = true; }
            break;
          case '>=':
            if($a[$f[0]] >= $f[2]) { $passed = true; }
            break;
          case '!=':
            if($a[$f[0]] != $f[2]) { $passed = true; }
            break;
          case '!==':
            if($a[$f[0]] !== $f[2]) { $passed = true; }
            break;
          case '==':
            if($a[$f[0]] == $f[2]) { $passed = true; }
            break;
          case '===':
            if($a[$f[0]] === $f[2]) { $passed = true; }
            break;
          default:
            return htmlentities('Invalid filter: '.$f[0].' '.$f[1].' '.$f2);
            break;
        }
        if($f[3] === $passed) {
          $passed = true;
          $or_passed = true;
        }
        else { $passed = false; }
      }
    }
    if($inverse) { $passed = ($passed) ? false : true; }
    if($passed || (!$all && $or_passed)) { $filtered_array[] = $a; }
  }

  return $filtered_array;
}



/* - - - - - fájlok kezelése - - - - - */
/**
 * makeThumbnail() Thumbnail készítõ funkció.
 *
 * @param (string) $originalImage // eredeti fájl címe
 * @param (string) $thumbnailImage // célfájl címe
 * @param (integer) $toWidth // maximum szélesség
 * @param (integer) $toHeight // maximum magasság
 * @param (integer) $resamplingQ // kép minõsége (1 - 100)
 * @param (mixed) $debug // debug infó küldése (false: nemküld, true: hiba esetén küld, 'forced': mindig küld)
 * @return (mixed) // debug infótól függõen tömb (Array()), vagy true / false (bool)
 */
function makeThumbnail($originalImage, $thumbnailImage, $toWidth = 120, $toHeight = 120, $resamplingQ = 100, $debug = false) {//ha nincs(0) egyik oldal, akkor csak a másikho igazítani
  // szedjük ki a forrás és a célfájl kiterjesztését
  $o_ext = strtolower(substr(strrchr($originalImage, '.'), 1));
  $t_ext = strtolower(substr(strrchr($thumbnailImage, '.'), 1));

  // állapítsuk meg a forrásfájl méreteit, és az új méretekhez szükséges tömörítés arányát
  list($width, $height) = getimagesize($originalImage);
  $xscale = $width / $toWidth;
  $yscale = $height / $toHeight;

  // állapítsuk meg a tömörítési arányból a legmegfelelõbb új méreteket
  if ($yscale > $xscale){
    $new_width = round($width * (1 / $yscale));
    $new_height = round($height * (1 / $yscale));
  }
  else {
    $new_width = round($width * (1 / $xscale));
    $new_height = round($height * (1 / $xscale));
  }

  // készítsük el az átméretezett képet
  $imageResized = imagecreatetruecolor($new_width, $new_height);

  if($o_ext == 'png') $imageTmp = imagecreatefrompng($originalImage);
  else $imageTmp = imagecreatefromjpeg($originalImage);

  $error['imagecopyresampled'] = imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

  // írjuk az új képet a helyére
  if($t_ext == 'png') $error['imagepng / imagejpeg'] = imagepng($imageResized, $thumbnailImage, $resamplingQ);
  else $error['imagepng / imagejpeg'] = imagejpeg($imageResized, $thumbnailImage, $resamplingQ);

  // töröljük a képobjektumokat
  $error['imagedestroy($imageTmp)'] = imagedestroy($imageTmp);
  $error['imagedestroy($imageResized)'] = imagedestroy($imageResized);

  // debug infók begyüjtése
  if($debug !== false) {
    $error['data']['$originalImage'] = $originalImage;
    $error['data']['$thumbnailImage'] = $thumbnailImage;
    $error['data']['$toWidth'] = $toWidth;
    $error['data']['$toHeight'] = $toHeight;
    $error['data']['$toHeight'] = $asd;
    $error['data']['$resamplingQ'] = $resamplingQ;
    $error['data']['$o_ext'] = $o_ext;
    $error['data']['$t_ext'] = $t_ext;
    $error['data']['$width'] = $width;
    $error['data']['$height'] = $height;
    $error['data']['$xscale'] = $xscale;
    $error['data']['$yscale'] = $yscale;
    $error['data']['$new_width'] = $new_width;
    $error['data']['$new_height'] = $new_height;
  }

  // visszaadjuk a folyamat eredményét / debug infókat
  if($error['imagecopyresampled'] === false || $error['imagepng / imagejpeg'] === false) {//hiba esetén
    if($debug !== false) return $error;
    else return false;
  }
  else {//siker esetén
    if($debug == 'forced') return $error;
    else return true;
  }
}

function generate_jsmenu($c = false, $sort = false) {
  // rendezett kategória lista lekérése
  $catList = ($c !== false) ? $c : getCatlist();
  if($sort !== false && $c !== false) { $catList = getCatlist($catList); }

  // szükséges változók létrehozása
  $jsmenu_content = '';
  $jsmenu_file = 'jsmenu.php';

  $m = ' &gt;';
  $n = "";
  //$n = "\n\r";
  $last_cat = false;

  // kategóriák kipörgetése ha vannnak
  $jsmenu_content .= '<div id="vec_jsmenu">'.$n;
  if(is_array($catList)) {

    foreach($catList as $cat) {
      if($cat['status'] == 1) { // ha aktív

        if($last_cat['level'] > $cat['level']) { // ha az elõzõ szinthez képest feljebb lép
          for($i = 0; $i < ($last_cat['level'] - $cat['level']); $i++) {
            $jsmenu_content .= '</ul>'.$n.'</li>'.$n.$n;
          }
          if($cat['level'] == 0) { $jsmenu_content .= '</ul>'.$n.$n; }
        }

        if($cat['level'] == 0) { // ha fõkategória jön
          if($last_cat['level'] == $cat['level']) { $jsmenu_content .= '</ul>'.$n.$n; }
          $jsmenu_content .= '<ul class="main_menu cat-'.$cat['caid'].'">'.$n;
        }
        else {
          if($cat['level'] > $last_cat['level']) {
            $class = ($cat['level'] == 1) ? 'main_menu_subs' : 'sub_menus';
            $jsmenu_content .= '<ul class="'.$class.'" id="cat-'.esc($cat['parent_caid'], 2).'" style="visibility: hidden">'.$n;
          }
        }

        if($cat['content'] == 1) { // ha cikkeket tartalmaz
          $jsmenu_content .= '<li onmouseover="show(false, '.$cat['level'].')">'
          .'<a href="'.$_HOST.'category/'.esc($cat['perma'], 2).'">'.esc($cat['title'], 2).'</a></li>'.$n;
        }
        elseif($cat['content'] == 0) { // ha ketegóriákat tartalmaz és nem fõkategória
          $mark = ($cat['level'] != 0) ? $m : '';
          $jsmenu_content .= $n.'<li onmouseover="show(\'cat-'.$cat['caid'].'\', '.$cat['level'].')" onmouseout="hide(\'cat-'.$cat['caid'].'\')">'.$n;
          $jsmenu_content .= '<a href="'.$_HOST.'category/'.esc($cat['perma'], 2).'">'.esc($cat['title'], 2).$mark.'</a>'.$n;
        }

        $last_cat = $cat;
      }
    }

    for($i = 0; $i < $last_cat['level']; $i++) { // a lezáratlan listákat zárja le
      $jsmenu_content .= '</ul>'.$n.'</li>'.$n;
    }
  }
  $jsmenu_content .= '</ul>'.$n.'</div>'.$n;

  // fájlba írás
  $file = /*@*/fopen($jsmenu_file, 'w');
  $error['fwrite'] = @fwrite($file, $jsmenu_content);
  $error['fclose'] = @fclose($file);

  //$error['$catList'] = $catList;
  //$error['$jsmenu_content'] = $jsmenu_content;

  //return $error;
  return true;
}

function cacheCatList() {
  $cache_file = 'categories.cache.php';
  $cache_content = '<?php $_CACHE[\'catlist\'] = \''.str_replace("'", "\'", serialize(getCatList(false, false))).'\'; ?>';//&#039;

  // fájlba írás
  $file = fopen($cache_file, 'w');
  $error['fwrite'] = @fwrite($file, $cache_content);
  $error['fclose'] = @fclose($file);

  //$error['$cache_file'] = $cache_file;
  //$error['$cache_content'] = $cache_content;

  return true;
}

function file_usable($file, $is_readable = true, $is_writable = true, $not_empty = true) {
  $readable = ($is_readable == true) ? is_readable($file) : true;
  $writable = ($is_writable == true) ? is_writable($file) : true;
  $filesize = (($not_empty == true && @filesize($file) > 0) || $not_empty == false) ? true : false;

  if(file_exists($file) && is_file($file) && $readable && $writable && $filesize) {
    return true;
  }
  else { return false; }
}

function imagetype($file) {
  $imageTypes = array(
    1 => 'IMAGETYPE_GIF',
    2 => 'IMAGETYPE_JPEG',
    3 => 'IMAGETYPE_PNG',
    4 => 'IMAGETYPE_SWF',
    5 => 'IMAGETYPE_PSD',
    6 => 'IMAGETYPE_BMP',
    7 => 'IMAGETYPE_TIFF_II',
    8 => 'IMAGETYPE_TIFF_MM',
    9 => 'IMAGETYPE_JPC',
    10 => 'IMAGETYPE_JP2',
    11 => 'IMAGETYPE_JPX',
    12 => 'IMAGETYPE_JB2',
    13 => 'IMAGETYPE_SWC',
    14 => 'IMAGETYPE_IFF',
    15 => 'IMAGETYPE_WBMP',
    16 => 'IMAGETYPE_XBM',
    17 => 'IMAGETYPE_ICO'
  );

  if(filesize($file) > 11) { $typeKey = exif_imagetype($file); }
  else { $typeKey = false; }

  if($typeKey !== false && isset($imageTypes[$typeKey])) { return $imageTypes[$typeKey]; }
  else { return false; }
}


/* - - - - - Log Handling Functions - - - - - */
/**
 * Logoló funkció.
 *
 * @param integer $type (0=SQL, 1=User, 2=Mail)
 * @param integer $class (0-4)
 * @param string or array $message
 * @param integer $status (0=Rendben, 1=Ellenõrizetlen, 2=Kiemelt)
 * @param bool $die
 */
/*
function logger($type, $class = 0, $message = '', $status = 1, $die = false) {

  $t = esc($type, 1);
  $c = esc($class, 1);
  $m = (is_array($message)) ? esc(var_dump($message), 1) : esc($message, 1);
  $s = esc($status, 1);

  $_USER = checkLogin();
  $lu = ($_USER === false) ? 0 : $_USER['usid'];
  $lt = time();
  $li = esc($_SERVER['REMOTE_ADDR'], 1);

  $query0 = "INSERT INTO `rpl_logs` (`loid`, `type`, `class`, `message`, `status`, `log_uid`, `log_time`, `log_ip`) VALUES (NULL, '$t', '$c', '$m', '$s', '$lu', '$lt', '$li')";
  sql_query($query0);

  if($die !== false) { die($t.'::'.$c.' - '.$m); }
}
*/

/* - - - - - Data Handling Functions - - - - - */
function entities_code($string, $type = 0) {
  if($string != '') {
    if($type == 1) {//encoding
      $decoded = array('|', '<', '>', '$', '"', '\'');
      $encoded = array('&#124;', '&lt;', '&gt;', '&#36;', '&quot;', '&#39;');

      $string = str_replace($decoded, $encoded, $string);
    }
    elseif($type == 2) {//decoding
      $encoded = array('&#124;', '&lt;', '&#60;', '&#060;', '&gt;', '&#62;', '&#062;', '&#36;', '&#036;', '&quot;', '&#34;', '&#034;', '&#39;', '&#039;', '&apos;');
      $decoded = array('|', '<', '<', '<', '>', '>', '>', '$', '$', '"', '"', '"', '\'', '\'', '\'');

      $string = str_replace($encoded, $decoded, $string);
    }
  }

  return $string;
}

function esc($string, $type = 0, $fix_amp = true) {
  if($string != '') {
    if(get_magic_quotes_gpc()) {
      $string = stripslashes($string);
    }
    if($type == 1) {
      $string = entities_code($string, 1);
      $string = mysql_real_escape_string($string);
    }
    elseif($type == 2) {//html entities
      if($fix_amp !== true) { $string = str_replace('&', '&amp;', $string); }
      $string = entities_code($string, 1);
    }
  }
  return $string;
}

function rand_str($l = 32, $t = 2, $p = '') {
  $c[0] = '0123456789';
  $c[1] = 'abcdefghijklmnopqrstuvwxyz';
  $c[2] = '0123456789abcdefghijklmnopqrstuvwxyz';
  $c[3] = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $c[4] = '0123456789abcdef';//hexadecimal hash style

  $s = (is_numeric($t)) ? $c[$t].$p : $t.$p;

  $rs = '';
  for($i = 0; $i < $l; $i++) {
    $rs .= $s{mt_rand(0, strlen($s) - 1)};
  }

  return $rs;
}

function _date($u = false, $t = 'Y.m.d - H:i:s') {
  if($u === false) { return date($t); }
  else { return date($t, $u); }
}

function cutString($str, $max = 32, $end = '...', $cut = false, $start = 0) {//ezt nem a wrapper helyettesíti?
  //szavak félbevágásának tiltása, X karakter toleranciával + és -;
  // szóközöknél rak $end-et amig a köv. szó strlen-je nem haladja meg a max-ot (toleranciával együtt)
  $max = (is_numeric($max)) ? $max : 32;
  $cut = ($cut !== false && is_numeric($cut)) ? $cut : $max;
  if(0 < $cut) { $cut = $cut-1; }
  elseif(0 > $cut) { $cut = $cut; }
  if($cut == 0) { return ''; }

  if(strlen($str) > $max) { return substr($str, $start, $cut).$end; }
  else { return $str; }
}

function hex2bin($hex) {
  return pack('H*', $hex);
}

function wrapper($string, $maxwidth = 20, $entities = true, $wrapper = '<br/>', $explode = ' ', $implode = ' ') {
  //if($entities) { $string = html_entity_decode($string); }
  if($entities) { $string = entities_code($string, 2); }
  $string_chunks = explode($explode, $string);

  foreach($string_chunks as $chunk) {
    $chunk = str_replace('&nbsp;', ' ', $chunk);
    $chunk_length = strlen($chunk);

    if($chunk_length > $maxwidth) {
      $chunk_parts = number_format(($chunk_length / $maxwidth), 0, '', '');
      if($chunk_length % $maxwidth > 0) { $chunk_parts++; };

      $wrapped_chunks = '';
      for($i = 0; $i < $chunk_parts; $i++) {
        $wrapped_chunk = substr($chunk, ($i * $maxwidth), $maxwidth);
        //$wrapped_chunks[] = ($entities) ? htmlentities($wrapped_chunk) : $wrapped_chunk;
        $wrapped_chunk = str_replace(' ', '&nbsp;', $wrapped_chunk);
        $wrapped_chunks[] = ($entities) ? entities_code($wrapped_chunk, 1) : $wrapped_chunk;
      }

      $wrapped_string_chunks[] = implode($wrapper, $wrapped_chunks);
    }
    else {
      //$wrapped_chunk = ($entities) ? htmlentities($chunk) : $chunk;
      $chunk = str_replace(' ', '&nbsp;', $chunk);
      $wrapped_chunk = ($entities) ? entities_code($chunk, 1) : $chunk;
      $wrapped_string_chunks[] = $wrapped_chunk;
    }
  }

  return implode($implode, $wrapped_string_chunks);
}

function makePermalink($string, $maxlength = 200, $plus = '') {
  $string = makeTitle($string, $maxlength);

  $pf = array(' ', '_', '--');
  $string = str_replace($pf, '-', $string);

  $chars = str_split($string);

  $permalink = '';
  $last_char = '';
  //dupla kötõjel veszélyes, mert megszakítja az sql parancsot,
  // bár zárt aposztrófban nem, de akkor sem szép

  foreach($chars as $char) {
    if(preg_match('/^[0-9A-Za-z-'.$plus.']$/', $char)) {
      if($char != '-' || $char != $last_char) { $permalink .= $char; }
    }
  }

  return $permalink;
}

function makeTitle($string, $maxlength = 200) {
  return substr(trim($string), 0, 200);
}

function wc($s) {
  $wc = array(' ', '*', '?');
  $lc = array('%', '%', '_');

  return str_replace($wc, $lc, $s);
}


/* - - - - - MySQL Handling Functions - - - - - */
function sql_query($query, $ins_id = false) {
  if($ins_id) {
    $result = mysql_query($query);
    if($result !== false) { return mysql_insert_id(); }
    else { return $result; }
  }
  else { return mysql_query($query); }
}

function sql_fetch_all($result, $group = false, $unique = false, $type = MYSQL_ASSOC) {
  if($result === false) { return false; }
  else {
    while($data = mysql_fetch_array($result, $type)) { $datas[] = $data; }
    if($group !== false) { $datas = groupArrays($datas, $group, $unique); }

    return $datas;
  }
}



/* - - - - - Missing Function Handling Functions - - - - - */
if(!function_exists('str_split')) {
  function str_split($string, $split_length = 1) {
    $array = explode("\r\n", chunk_split($string, $split_length));
    array_pop($array);
    return $array;
  }
}

if(!function_exists('exif_imagetype')) {
  function exif_imagetype($file) {
    $imageData = /*@*/getimagesize($file);
    if($imageData !== false) {
      return $imageData[2];
    }
    else { return false; }
  }
}

function _microtime($round = false) {//PHP 5 microtime(true);
  if($round === false) { return array_sum(explode(' ', microtime())); }
  else { return round(array_sum(explode(' ', microtime())), $round); }
}

/* - - - - - Debug Handling Functions - - - - - */
function dbg($a) {
  //debug---
  echo '<pre style="text-align: left;">';
  print_r($a);
  echo '</pre>';
  die('<br/><br/><br/>dbg eof');
  //debug---
}
?>