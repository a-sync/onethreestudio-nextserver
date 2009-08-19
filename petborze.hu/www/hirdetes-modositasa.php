<?php
include 'functions.php';

$hiid = mysql_real_escape_string($_GET['hirdetes']);
$hirdetes = false;
$hibak = false;

if(is_numeric($hiid)) {
  $hirdetes = mysql_query("SELECT * FROM `hirdetesek` WHERE `hiid` = '{$hiid}' LIMIT 1");
  $hirdetes = mysql_fetch_assoc($hirdetes);
  $hirdetes3 = $hirdetes;
  
  foreach($hirdetes as $i => $val) {
    $hirdetes[$i] = htmlspecialchars($val);
  }
}

if(check_admin() && $hirdetes != false) {
  header("Location: hirdetes-admin.php?hirdetes={$hirdetes['hiid']}");
  exit;
}
elseif(check_mod($hirdetes3['hiid']) && $hirdetes != false) {
  if($_POST['kuldes'] != '') {
    
    $hirdetes = adatok_szurese($_POST, $hibak);
    $hirdetes['hirdetes_cime'] = $hirdetes3['hirdetes_cime'];
    $hirdetes['ervenyes_datum'] = $hirdetes3['ervenyes_datum'];
    $hirdetes['kiemelt_datum'] = $hirdetes3['kiemelt_datum'];
    $hirdetes['datum'] = $hirdetes3['datum'];
    $hirdetes['statusz'] = $hirdetes3['statusz'];
    //$hirdetes['uzletkoto'] = $hirdetes3['uzletkoto'];
    
    if($hibak == false) {
      $hirdetes2 = adatok_szurese($_POST, $hibak, true);
      $q = hirdetes_modositasa($hirdetes2);
      header("Location: hirdetes-modositasa.php?hirdetes={$hirdetes['hiid']}");
      exit;
    }
  }
}
/*
elseif($hirdetes != false) {
  // e: illetéktelen
  header("Location: index.php");
  exit;
}
*/

if($hirdetes != false && !check_mod($hirdetes3['hiid'])) {
  header("Location: belepes.php?hirdetes={$hirdetes3['hiid']}");
  exit;
}

include 'head.php';

if($hirdetes == false) {
?>

      <div id="content_frame">
        
        <div id="content_head">
          <h1 id="content_title">
            Hirdetés szerkesztése
            <span></span>
          </h1>
          <div id="content_title_right">
            
          </div>
        </div><!--/content_head-->
        
        <div id="content">
          
          Nincs hirdetés ezzel az azonosítóval.
          
        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}
elseif(check_mod($hirdetes3['hiid'])) {
$time = time();

  if($_POST['request'] == 'ervenyesseget') {
    uj_esemeny('Érvényesség meghosszabbítását kérem! (Jelenleg '.date('Y.m.d', $hirdetes3['ervenyes_datum']).'-ig érvényes.)', $_SESSION['admin']['hiid'], 2, 3);
    $_SESSION['admin']['req_ervenyes'] = $time;
  }
  elseif($_POST['request'] == 'kiemeltseget') {
    if($hirdetes3['kiemelt_datum'] > $time) uj_esemeny('Kiemeltség meghosszabbítását kérem! (Jelenleg '.date('Y.m.d', $hirdetes3['kiemelt_datum']).'-ig kiemelt.)', $_SESSION['admin']['hiid'], 2, 3);
    else uj_esemeny('Kiemeltség beállítását kérem!', $_SESSION['admin']['hiid'], 2, 3);
    $_SESSION['admin']['req_kiemelt'] = $time;
  }

  $request_center_report = '';
  if(is_numeric($_SESSION['admin']['req_ervenyes']) && is_numeric($_SESSION['admin']['req_kiemelt'])) {
    $request_center_report = 'A kérését elmentettük, és hamarosan kapcsolatba lépünk önnel. Addig pedig nem szükéges újra az érvényesség / kiemeltség meghosszabbítását kérnie.';
  }
  elseif(is_numeric($_SESSION['admin']['req_ervenyes'])) {
    $request_center_report = 'A kérését elmentettük, és hamarosan kapcsolatba lépünk önnel. Addig pedig nem szükéges újra az érvényesség meghosszabbítását kérnie.';
  }
  elseif(is_numeric($_SESSION['admin']['req_kiemelt'])) {
    if($hirdetes3['kiemelt_datum'] > $time) $request_center_report = 'A kérését elmentettük, és hamarosan kapcsolatba lépünk önnel. Addig pedig nem szükéges újra a kiemeltség meghosszabbítását kérnie.';
    else $request_center_report = 'A kérését elmentettük, és hamarosan kapcsolatba lépünk önnel. Addig pedig nem szükéges újra a kiemeltség kérését jeleznie.';
  }
?>

      <div id="content_frame">
        
        <div id="content_head">
          <h1 id="content_title">
            Hirdetés szerkesztése
            <span></span>
          </h1>
          <div id="content_title_right">
            
          </div>
        </div><!--/content_head-->
        
        <div id="content">
          <div class="reg_row reg_row_alt">
            <center class="request_center">
              <?php echo ($request_center_report == '') ? '' : '<center class="request_center_report">'.$request_center_report.'</center>'; ?>
              <form class="request_form" action="" method="post">
                <input type="hidden" name="request" value="ervenyesseget" />
                <input class="giant_request_button" type="submit" value="Érvényesség meghosszabbítása!" <?php if(is_numeric($_SESSION['admin']['req_ervenyes'])) echo 'disabled="disabled" style="display: none;" '; ?>/>
              </form>
              <form class="request_form" action="" method="post">
                <input type="hidden" name="request" value="kiemeltseget" />
                <input class="giant_request_button" type="submit" value="Kiemeltség <?php echo ($hirdetes3['kiemelt_datum'] > $time) ? 'meghosszabbítása' : 'kérése'; ?>!" <?php if(is_numeric($_SESSION['admin']['req_kiemelt'])) echo 'disabled="disabled" style="display: none;" '; ?>/>
              </form>
            </center>
          </div>
          
          <?php adatlap($hirdetes, $hibak, 'Hirdetés módosítása!'); ?>
          
        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}

include 'foot.php';
?>