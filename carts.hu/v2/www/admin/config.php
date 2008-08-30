<?php
// adatbázs
$db['dbhost'] = 'localhost';// sql szerver
$db['dbuser'] = 'nextserv_carts';// adatbázis felhasználó
$db['dbpass'] = '13swork';// felhasználó jelszó
$db['dbname'] = 'nextserv_carts';//adatbázis neve

$twitter_link = 'http://twitter.com/kocsmy';
$twitter_rss = 'http://twitter.com/statuses/user_timeline/14844137.rss';
$twitter_posts = 5;

// galéria (külön mappában kell lennie a képeknek és a thumbnaileknek mert a nevük azonos lesz a fájloknak!)
// ezeket létre kell hozni, és chmod 0777-et állítani rajtuk
// admin mappához képest
$gal['mappa'] = 'galeria/';
$gal['thumb'] = 'galeria/thumbnails/';

// oldalhoz képest
$gal['kepek_mappa'] = 'galeria/';
$gal['kepek_thumb'] = 'galeria/thumbnails/';

// adminok
$admins[0]['name'] = 'vector';
$admins[0]['pass'] = '13swork';

$admins[1]['name'] = 'zsolti';
$admins[1]['pass'] = '13swork';

$admins[2]['name'] = 'gyuri';
$admins[2]['pass'] = '13valami';


//funkciók alap változók
error_reporting(E_ALL ^ E_NOTICE);//normál
//error_reporting(0);//nemkellenek hibaüzenetek: 0
//set_time_limit(0);
$the_connection = @mysql_connect($db['dbhost'], $db['dbuser'], $db['dbpass']) or die('Nem sikerült csatlakozni a szerverhez! (Ellenőrizd a config.php fájlt.)');
@mysql_select_db($db['dbname'], $the_connection) or die('Nem sikerült csatlakozni az adatbázishoz! (Ellenőrizd a config.php fájlt.)');

function dbg($a) {
  echo '<pre align="left" title="'.date('H:i:s ').microtime(true).'">'.print_r($a, true).'</pre>';    
}

function addCookie($name, $passhash) {
  $time = time() + 60 * 60 * 24 * 1;//1 napon át érvényes a süti

  setcookie('name', $name, $time, '/', $_SERVER['SERVER_NAME']);
  setcookie('passhash', $passhash, $time, '/', $_SERVER['SERVER_NAME']);
}

function delCookie() {
  $time = time() - 60 * 60 * 24 * 1;

  setcookie('name', '', $time, '/', $_SERVER['SERVER_NAME']);
  setcookie('passhash', '', $time, '/', $_SERVER['SERVER_NAME']);
}

function ident($name, $rpass, $salt = '# * Nagy0N - Tit0K * #') {
  $nameB64hex = bin2hex(base64_encode(strtolower($name).$salt));
  $rpassB64hex = bin2hex(base64_encode($rpass.$salt));

  return md5($nameB64hex.$rpassB64hex.$nameB64hex);
}

function checkLogin($name = false, $passhash = false) {
  if($name == false || $passhash == false) {
    $name = mysql_real_escape_string($_COOKIE['name']);
    $passhash = mysql_real_escape_string($_COOKIE['passhash']);
  }

  if($name != '' && $passhash != '') {
    global $admins;

    foreach($admins as $admin) //array search?
    {
      if(strtolower($name) == strtolower($admin['name']))
      {
        if($passhash == ident($name, $admin['pass']))
        {
          return true;
        }
      }
    }

    delCookie();
    return false;
  }
  else {
    return false;
  }
}

/**
 * makeThumbnail() Thumbnail készítő és képméretező funkció.
 *
 * @param (string) $originalImage // eredeti fájl címe
 * @param (string) $thumbnailImage // célfájl címe
 * @param (integer) $toWidth // maximum szélesség (0 esetén az új magassághoz lesz arányos)
 * @param (integer) $toHeight // maximum magasság (0 esetén az új szélességhez lesz arányos)
 * @param (integer) $fixWidth // fix szélesség (aránytól függetlenül)
 * @param (integer) $fixHeight // fix magasság (aránytól függetlenül)
 * @param (integer) $resamplingQ // kép minősége (1 - 100)
 * @param (mixed) $debug // debug infó küldése (false: nemküld, true: hiba esetén küld, 'forced': mindig küld)
 * @return (mixed) // debug infótól függően tömb (Array()), vagy true / false (bool)
 **/
function makeThumbnail($originalImage, $thumbnailImage, $toWidth = 150, $toHeight = 0, $fixWidth = false, $fixHeight = false, $resamplingQ = 96, $debug = false) {
  $error = array();
  // szedjük ki a forrás és a célfájl kiterjesztését
  $o_ext = strtolower(end(explode('.', $originalImage)));
  $t_ext = strtolower(end(explode('.', $thumbnailImage)));

  // sima másolás ha a GDlib nem elérhető (és a debug szint minimum)
  if(!function_exists('imagecreatetruecolor') && $debug === false) {
    // akadályozzuk meg, nehogy ezt próbálja valaki kihasználni
    if($o_ext != 'png' && $o_ext != 'gif' && $o_ext != 'jpeg' && $o_ext != 'jpg') return false; // nem támogatott fájltípus
    else return copy($originalImage, $thumbnailImage);
  }

  // állapítsuk meg a forrásfájl méreteit, és az új méretekhez szükséges tömörítés arányát
  list($width, $height) = getimagesize($originalImage);

  // állapítsuk meg a tömörítési arányból a legmegfelelőbb új méreteket
  if($toWidth == 0) $scale = $height / $toHeight;
  elseif($toHeight == 0) $scale = $width / $toWidth;
  else {
    if($xscale > $yscale) $scale = $width / $toWidth;
    else $scale = $height / $toHeight;
  }
  
  $new_width = round($width * (1 / $scale));
  $new_height = round($height * (1 / $scale));

  if(is_numeric($fixWidth)) $new_width = $fixWidth;
  if(is_numeric($fixHeight)) $new_height = $fixHeight;

  // készítsük el az átméretezett nyers képet
  $imageResized = imagecreatetruecolor($new_width, $new_height);

  if($o_ext == 'png') $imageTmp = imagecreatefrompng($originalImage);
  elseif($o_ext == 'gif') $imageTmp = imagecreatefromgif($originalImage);
  else $imageTmp = imagecreatefromjpeg($originalImage);

  $error['imagecopyresampled'] = imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

  // írjuk az új képet a helyére
  if($t_ext == 'png') $error['imagepng/gif/jpeg'] = imagepng($imageResized, $thumbnailImage, round($resamplingQ / 11.111111));
  elseif($t_ext == 'gif') $error['imagepng/gif/jpeg'] = imagegif($imageResized, $thumbnailImage);
  else $error['imagepng/gif/jpeg'] = imagejpeg($imageResized, $thumbnailImage, $resamplingQ);

  // debug infók begyüjtése
  if($debug !== false) {
    $error['$originalImage'] = $originalImage;
    $error['$thumbnailImage'] = $thumbnailImage;
    $error['$toWidth'] = $toWidth;
    $error['$toHeight'] = $toHeight;
    $error['$fixWidth'] = $fixWidth;
    $error['$fixHeight'] = $fixHeight;
    $error['$resamplingQ'] = $resamplingQ;
    $error['$o_ext'] = $o_ext;
    $error['$t_ext'] = $t_ext;
    $error['$width'] = $width;
    $error['$height'] = $height;
    $error['$xscale'] = $xscale;
    $error['$yscale'] = $yscale;
    $error['$scale'] = $scale;
    $error['$new_width'] = $new_width;
    $error['$new_height'] = $new_height;
    $error['$imageResized'] = $imageResized;
    $error['$imageTmp'] = $imageTmp;
  }

  // töröljük a képobjektumokat
  $error['imagedestroy($imageTmp)'] = imagedestroy($imageTmp);
  $error['imagedestroy($imageResized)'] = imagedestroy($imageResized);

  // visszaadjuk a folyamat eredményét / debug infókat
  if($error['imagecopyresampled'] === false || $error['imagepng/gif/jpeg'] === false) {//hiba esetén
    $error['success'] = false;
    if($debug !== false) return $error;
    else return false;
  }
  else {//siker esetén
    $error['success'] = true;
    if($debug == 'forced') return $error;
    else return true;
  }
}

$error = '';

?>