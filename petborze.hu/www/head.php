<?php
// stílusok beállítása
if($_GET['stilus'] != '' && $styles[$_GET['stilus']] != '') {
  $stylesheet = htmlspecialchars($_GET['stilus']);
  setcookie('stilus', $stylesheet, (time() + 60 * 60 * 24 * 365)); // 1 évig jegyezzük meg
}
elseif($styles[$_COOKIE['stilus']] != '') {
  $stylesheet = htmlspecialchars($_COOKIE['stilus']);
}
else {
  $stylesheet = key($styles);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="language" content="hu" />

  <meta name="copyright" content="www.onethreestudio.com" />
  <meta name="Author" content="One Three Studio" />

  <meta name="googlebot" content="index, follow, archive" />
  <meta name="robots" content="all, index, follow" />
  <meta name="msnbot" content="all, index, follow" />

  <meta name="rating" content="general" />
  <meta name="resource-type" content="document" />
  <meta name="distribution" content="Global" />
  <meta name="revisit-after" content="1 day" />

  <meta name="page-type" content="Ingatlan" />
  <meta name="subject" content="ingatlan adás, vétel hirdetés" />
  <meta name="description" content="Ingatlan hirdetés, és böngészés videókkal és képekkel. Hirdesse lakását biztonságosan és jutalékmentesen." />
  <meta name="keywords" content="<?php echo implode(', ', $data['faj']).', '.implode(', ', $data['kor']).' hirdetés, videók, képek, jutalék nélkül'; ?>" />

  <title>Szlogen.</title>

  <link href="default_images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <link href="default_images/favicon.ico" rel="icon" type="image/x-icon" />

  <link rel="stylesheet" type="text/css" href="main.css" />
  
  <link rel="stylesheet" type="text/css" href="<?php echo $stylesheet; ?>" />

  <!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" href="main_lte_ie6.css" />
  <![endif]-->
  <!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="main_lte_ie7.css" />
  <![endif]-->
  
	<link type="text/css" rel="stylesheet" href="/floatbox/floatbox.css" />
	<script type="text/javascript" src="/floatbox/floatbox.js"></script>
  
  <script type="text/javascript" src="default_main.js"></script>
  <script type="text/javascript" src="tooltip.js"></script>
  <?php /*<!--<script type="text/javascript" src="dbg_040.js"></script>
  <script type="text/javascript" src="darth_fader-1.11b.js"></script>-->*/ ?>
  <script type="text/javascript" src="swfobject.js"></script>
</head>
<body onload="init();">
<!--<no script>Az oldal teljeskörű használatához, javascript engedélyezése szükséges!</no script>-->

  <div id="container_frame">
  <div id="container">
    
    <div id="head">
      <div id="left_head">
        <a id="logo" href="index.php"> </a>
      </div>
      
      <div id="right_head">
        <div id="banner"><div></div></div>
        
        <div id="top_menu"><div><div>
          <a class="top_menu_item" href="index.php">
            <img src="default_images/menu_fooldal.png" alt="Főoldal" />
          </a>
          <a class="top_menu_item" href="rolunk.php">
            <img src="default_images/menu_rolunk.png" alt="Rólunk" />
          </a>
          <a class="top_menu_item" href="utmutato.php">
            <img src="default_images/menu_utmutato.png" alt="Útmutató" />
          </a>
          <a class="top_menu_item" href="arak.php">
            <img src="default_images/menu_arak.png" alt="Árak" />
          </a>
          <a class="top_menu_item" href="kapcsolat.php">
            <img src="default_images/menu_kapcsolat.png" alt="Kapcsolat" />
          </a>
          <a class="top_menu_item" href="/forum/">
            <img src="default_images/menu_forum.png" alt="Fórum" />
          </a>
          <a class="top_menu_item top_menu_item_high" href="belepes.php">
            <img src="default_images/menu_belepes.png" alt="Belépés" />
          </a>
        </div></div></div>
      </div>
      
    </div><!--/head-->
    
    <div id="body">
      <div id="left_bar">
        
        <div id="corner_button"><a href="kereses.php"> </a></div>
      <?php
      /*
        echo '<h2>Kis kedvencek</h2>';
		    $_osszes = kategoriak_kiirasa();
      */
      ?>
      <?php
      /*
      <embed id="left_menu" wmode="transparent" width="196" height="400" allowscriptaccess="never" allowfullscreen="false" quality="high" bgcolor="#FFFFFF" src="default_images/left_menu.swf" type="application/x-shockwave-flash" />
      */
      ?>
      
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="196" height="400">
  <param name="movie" value="default_images/left_menu.swf" />
  <param name="quality" value="high" />
  <param name="wmode" value="transparent" />
  <embed wmode="transparent" src="default_images/left_menu.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="196" height="400"></embed>
</object>
      
        <?php 
          if(is_numeric($_SESSION['admin']['hiid'])) { 
            echo '<h2><a class="adminlink" href="hirdetes-feladasa.php?lepes=2&hirdetes='.$_SESSION['admin']['hiid'].'">Új hirdetés feladása</a></h2>';
          }
          else {
        ?>
          <h2><a href="hirdetes-feladasa.php">Hirdetés feladása</a></h2>
        <?php
          }
        ?>
		    <h2 class="elvalaszto">&nbsp;</h2>
        <h2><a href="allatorvosi-segitseg.php">Állatorvosi segítség</a></h2>
        <h2><a href="tulajdonosi-lapok.php">Tulajdonosi lapok</a></h2>
		<?php
		  if(check_admin() != false) {
        echo '<br/>'
			  .'<h2><a class="adminlink" href="admin.php">Új hirdetések</a></h2>'
				.'<h2><a class="adminlink" href="admin-hirdetesek.php">Hirdetések</a></h2>'
				.'<h2><a class="adminlink" href="hirdetes-esemenyek.php">Események</a></h2>'
				.'<h2><a class="adminlink" href="hirdetes-esemenyek.php?tipus=log">Log</a></h2>'
				.'<h2><a class="adminlink" href="hirdetes-statisztika.php">Statisztika</a></h2>'
				.'<h2><a class="adminlink" href="admin-uzletkoto.php">Üzletkötő</a></h2>'
				.'<h2><a class="adminlink" href="admin.php?kilepes='.time().'">Kilépés</a></h2>';
		  }
      // sql-ben megnezni h ervenyesen van-e meg ien
      if(is_numeric($_SESSION['admin']['hiid'])) {
        echo '<br/>'
			  .'<h2><a class="adminlink" href="hirdetes-modositasa.php?hirdetes='.$_SESSION['admin']['hiid'].'">Hirdetés szerkesztése</a></h2>'
			  .'<h2><a class="adminlink" href="belepes.php?email='.urlencode($_SESSION['admin']['email']).'">Többi hirdetés</a></h2>'
				//.'<h2><a class="adminlink" href="hirdetes-esemenyek.php">Események</a></h2>'
				//.'<h2><a class="adminlink" href="hirdetes-esemenyek.php?tipus=log">Log</a></h2>'
				.'<h2><a class="adminlink" href="hirdetes-statisztika.php?hirdetes='.$_SESSION['admin']['hiid'].'">Statisztika</a></h2>'
				.'<h2><a class="adminlink" href="belepes.php?kilepes='.time().'&hirdetes='.$_SESSION['admin']['hiid'].'">Kilépés</a></h2>';
      }
		?>
      
      </div><!--/left_bar-->

