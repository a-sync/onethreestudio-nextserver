<?php
// adatbázs
$db['dbhost'] = 'localhost';// sql szerver
$db['dbuser'] = 'hv_pertl';// adatbázis felhasználó
$db['dbpass'] = 'Zai3ohb8ae';// felhasználó jelszó
$db['dbname'] = 'hv_pertl';//adatbázis neve

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

$admins[2]['name'] = 'pertl';
$admins[2]['pass'] = 'szobraszmuvesz';



//funkciók alap változók
error_reporting(E_ALL ^ E_NOTICE);//normál
//error_reporting(0);//nemkellenek hibaüzenetek: 0
//set_time_limit(0);
$rpl_connection = @mysql_connect($db['dbhost'], $db['dbuser'], $db['dbpass']) or die('Nem sikerült csatlakozni a szerverhez! (Ellenőrizd a config.php fájlt.)');
@mysql_select_db($db['dbname'], $rpl_connection) or die('Nem sikerült csatlakozni az adatbázishoz! (Ellenőrizd a config.php fájlt.)');


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

function ident($name, $rpass, $salt = '*Nagy0N - Tit0K*') {
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
 * makeThumbnail() Thumbnail készítő funkció.
 *
 * @param (string) $originalImage // eredeti fájl címe
 * @param (string) $thumbnailImage // célfájl címe
 * @param (integer) $toWidth // maximum szélesség
 * @param (integer) $toHeight // maximum magasság
 * @param (integer) $resamplingQ // kép minősége (1 - 100)
 * @param (mixed) $debug // debug infó küldése (false: nemküld, true: hiba esetén küld, 'forced': mindig küld)
 * @return (mixed) // debug infótól függően tömb (Array()), vagy true / false (bool)
 */
function makeThumbnail($originalImage, $thumbnailImage, $toWidth = 200, $toHeight = 200, $resamplingQ = 100, $debug = false) {
  // szedjük ki a forrás és a célfájl kiterjesztését
  $o_ext = strtolower(substr(strrchr($originalImage, '.'), 1));
  $t_ext = strtolower(substr(strrchr($thumbnailImage, '.'), 1));

  // állapítsuk meg a forrásfájl méreteit, és az új méretekhez szükséges tömörítés arányát
  list($width, $height) = getimagesize($originalImage);
  $xscale = $width / $toWidth;
  $yscale = $height / $toHeight;

  // állapítsuk meg a tömörítési arányból a legmegfelelőbb új méreteket
  if($yscale > $xscale){
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
  elseif($o_ext == 'gif') $imageTmp = imagecreatefromgif($originalImage);
  else $imageTmp = imagecreatefromjpeg($originalImage);

  $error['imagecopyresampled'] = imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

  // írjuk az új képet a helyére
  if($t_ext == 'png') $error['imagepng/gif/jpeg'] = imagepng($imageResized, $thumbnailImage, $resamplingQ);
  elseif($t_ext == 'gif') $error['imagepng/gif/jpeg'] = imagegif($imageResized, $thumbnailImage, $resamplingQ);
  else $error['imagepng/gif/jpeg'] = imagejpeg($imageResized, $thumbnailImage, $resamplingQ);

  // töröljük a képobjektumokat
  $error['imagedestroy($imageTmp)'] = imagedestroy($imageTmp);
  $error['imagedestroy($imageResized)'] = imagedestroy($imageResized);

  // debug infók begyüjtése
  if($debug !== false) {
    $error['data']['$originalImage'] = $originalImage;
    $error['data']['$thumbnailImage'] = $thumbnailImage;
    $error['data']['$toWidth'] = $toWidth;
    $error['data']['$toHeight'] = $toHeight;
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
  if($error['imagecopyresampled'] === false || $error['imagepng/gif/jpeg'] === false) {//hiba esetén
    if($debug !== false) return $error;
    else return false;
  }
  else {//siker esetén
    if($debug == 'forced') return $error;
    else return true;
  }
}

$error = '';

?>