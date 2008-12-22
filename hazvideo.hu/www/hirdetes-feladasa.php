<?php
include 'functions.php';


$hirdetes = adatok_szurese($_POST, $hibak, false, true);


include 'head.php';
//dbg($_POST); // debug
//if($_GET['lepes'] == 3 && $_POST['kuldes'] != '' && $hibak == false) {
if($_POST['kuldes'] != '' && $hibak == false || (count($hibak) == 1 && $hibak['hiid'] != '')) {
  $hirdetes2 = adatok_szurese($_POST, $hibak, true, true);
  $q = hirdetes_feladasa($hirdetes2);

  $targy = 'www.hazvideo.hu - Sikeres regisztráció!';
  $uzenet = 'Üdvözlöm.'."\r\n"
           .'Ön sikeresen regisztrálta hirdetését a www.hazvideo.hu oldalon.'."\r\n"
           .'Email cím: '.$hirdetes['email']."\r\n"
           .'Jelszó: '.$hirdetes['jelszo']."\r\n"
           ."\r\n"
           .'Valamelyik adminisztrátorunk hamarosan kapcsolatba lép önnel, hogy egyeztessék a hirdetés aktiválásának további lépéseit.'."\r\n"
           .'Addigis bármikor kapcsolatba léphet velünk a http://hazvideo.hu/kapcsolat.php oldalon megadott elérhetőségek valamelyikén ha kérdése vagy problémája lenne.'."\r\n"
           .'Ha módosítani szeretné a hirdetéseit, kérem lépjen be az oldalra a http://hazvideo.hu/belepes.php címen.'."\r\n"
           .'Ha több hirdetése is lenne ezzel az email címmel regisztrálva, a Belépés oldalon kiválaszthatja, hogy melyik hirdetését szeretné adminisztrálni.'."\r\n";
      
  mail_kuldo($_POST['email'], $targy, $uzenet);

  uj_esemeny('Új hirdetés lett feltöltve.', $q, 2, 1);
?>

      <div id="content_frame">
      
        <div id="content_head">
          <h1 id="content_title">
            Hirdetés feladása
            <span></span>
          </h1>
          <div id="content_title_right">
            (3. Lépés)
          </div>
        </div><!--/content_head-->
        
        <div id="content">

            <div class="reg_row">
            <?php
              if($q == false) {
                echo 'Az adatok felvitele közben hiba történt, kérlek próbáld újra később.';
                //echo mysql_error();
              }
              else {
            ?>
              <h2>A regisztráció sikeres volt!</h2>
              <br/>
              A hirdetését bármikor módosíthatja az alábbi címen bejelentkezve:<br/>
              <strong><a target="_blank" href="hirdetes-modositasa.php?hirdetes=<?php echo $q; ?>">http://hazvideo.hu/belepes.php?hirdetes=<?php echo $q; ?></a></strong>
              <br/><br/>
              <strong>E-mail:</strong> <?php echo $hirdetes['email']; ?>
              <br/>
              <strong>Jelszó:</strong> <?php echo $hirdetes['jelszo']; ?>
              <br/><br/>
              (A bejelentkezéshez szükséges információkat elküldtük emailben is!)
              <br/><br/>
              Hamarosan kapcsolatba lépünk önnel.
              <br/>
              
            <?php } ?>
            </div>

        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}
elseif($_GET['lepes'] == 2 || $_POST['kuldes'] != '') {

  if($_POST['kuldes'] == '') {
    $bejelentkezett_adatok = check_mod($_GET['hirdetes'], true);
    if($bejelentkezett_adatok != false) {
      $hirdetes['nev'] = $bejelentkezett_adatok['nev'];
      $hirdetes['telefon'] = $bejelentkezett_adatok['telefon'];
      $hirdetes['email'] = $bejelentkezett_adatok['email'];
      $hirdetes['jelszo'] = $bejelentkezett_adatok['jelszo'];
    }
  }

?>

      <div id="content_frame">
        
        <div id="content_head">
          <h1 id="content_title">
            Hirdetés feladása
            <span></span>
          </h1>
          <div id="content_title_right">
            (2. Lépés)
          </div>
        </div><!--/content_head-->
        
        <div id="content">
          
          <?php adatlap($hirdetes, $hibak); ?>
          
        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}
else {
?>

      <div id="content_frame">
      
        <div id="content_head">
          <h1 id="content_title">
            Hirdetés feladása
            <span></span>
          </h1>
          <div id="content_title_right">
            (1. Lépés)
          </div>
        </div><!--/content_head-->
        
        <div id="content">
          <form method="post" action="?lepes=2">
            <div class="reg_row">
                Hirdetési / regisztrációs feltételek.
                <br/>
				<br/>
				<span class="toggler" onclick="toggle_szabalyzat('szabalyzat', event);">Összecsuk / lenyit</span>
				<div id="szabalyzat">
					<?php echo nl2br(htmlspecialchars(file_get_contents('szabalyzat.txt'))); ?>
					<br/>
				</div>
				
                <input id="reg_submit" type="submit" value="Elfogadom a feltételeket!" />
            </div>
          </form>
        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}
$right_bar = 'ads';
include 'foot.php';
?>