<?php
include 'functions.php';


if(check_admin() == false) {
  header("Location: admin.php");
  exit;
}


include 'head.php';


$hiid = mysql_real_escape_string($_GET['hirdetes']);
$hirdetes = false;


if($_POST['kuldes'] != '') {
  $hirdetes = adatok_szurese($_POST, $hibak);

  if($hibak == false) {
    $hirdetes2 = adatok_szurese($_POST, $hibak, true);
    $q = hirdetes_modositasa($hirdetes2);
    //oldal ujratoltese header locationnal
  }
}

if(is_numeric($hiid)) {
  $hirdetes = mysql_query("SELECT * FROM `hirdetesek` WHERE `hiid` = '$hiid' LIMIT 1");
  $hirdetes = mysql_fetch_assoc($hirdetes);
  
  $right_bar = $hirdetes['hiid'];
  
  foreach($hirdetes as $i => $val) {
    $hirdetes[$i] = htmlspecialchars($val);
  }
}

if($hirdetes == false) {
?>

      <div id="content_frame">
        
        <div id="content_head">
          <h1 id="content_title">
            Hirdetés adminisztrálása
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
else {
?>

      <div id="content_frame">
        
        <div id="content_head">
          <h1 id="content_title">
            Hirdetés adminisztrálása
            <span></span>
          </h1>
          <div id="content_title_right">
            
          </div>
        </div><!--/content_head-->
        
        <div id="content">
          
          <?php adatlap($hirdetes, $hibak, 'Hirdetés módosítása!'); ?>
          
        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}

include 'foot.php';
?>