<?php
include 'functions.php';

if(check_admin() == false) {
  header("Location: admin.php");
  exit;
}

$uzletkoto = trim(strip_tags(urldecode($_GET['uzletkoto'])));
$kereses = trim(strip_tags(urldecode($_GET['kereses'])));
$hirdetesek_szama = false;

$query = "SELECT `hiid`, `datum`, `statusz`, `email`, `nev`, `telefon`, `ervenyes_datum`, `uzletkoto` FROM `hirdetesek` WHERE";

if($uzletkoto != '') {
  $uzletkoto = mysql_real_escape_string($uzletkoto);
  $hirdetesek = mysql_query("{$query} `uzletkoto` = '{$uzletkoto}' ORDER BY `datum` DESC");
  $hirdetesek_szama = mysql_num_rows($hirdetesek);
}
elseif($kereses != '') {
  $like_uzletkoto = '%'.mysql_real_escape_string(str_replace(' ', '_', $kereses)).'%';
  $hirdetesek = mysql_query("{$query} `uzletkoto` LIKE '{$like_uzletkoto}' ORDER BY `datum` DESC");
  $hirdetesek_szama = mysql_num_rows($hirdetesek);
}


include 'head.php';
if($hirdetesek_szama != false) {
  if($uzletkoto != '') $kereses = htmlspecialchars($uzletkoto);
  else $kereses = htmlspecialchars($kereses);
?>

      <div id="content_frame">
      
        <div id="content_head">
          <h1 id="content_title">
            "<?php echo $kereses; ?>"
            <span>(<?php echo $hirdetesek_szama; ?>db)</span>
          </h1>
          <div id="content_title_right">
            <a href="admin-uzletkoto.php">Üzletkötők</a>
          </div>
        </div><!--/content_head-->
        
        <div id="content">
        <div class="reg_row reg_row_alt">
          <div class="admin_col2">Dátum</div>
          <div class="admin_col1">Név</div>
          <div class="admin_col7">Email</div>
          <div class="admin_col6">Telefon</div>
          <div class="admin_col3">Státusz</div>
        </div>

<?php
$time = time();
$class = ' reg_row_alt';
while($hirdetes = mysql_fetch_assoc($hirdetesek)) {
  $class = ($class == '') ? ' reg_row_alt' : '';
  $statusz = ($time > $hirdetes['ervenyes_datum']) ? '<u>'.$data['statusz'][$hirdetes['statusz']].'</u>' : $data['statusz'][$hirdetes['statusz']];
  $tip_uzletkoto = ' rel="tip:Üzletkötő: '.htmlspecialchars($hirdetes['uzletkoto']).'"';
  
  echo '<div class="reg_row'.$class.'"'.$tip_uzletkoto.'>'
      .'<div class="admin_col2" title="'.date('Y.m.d - H:i:s', $hirdetes['datum']).'"><a href="hirdetes-admin.php?hirdetes='.$hirdetes['hiid'].'">'.date('Y.m.d', $hirdetes['datum']).'</a></div>'
      .'<div class="admin_col1">'.$hirdetes['nev'].'</div>'
      .'<div class="admin_col7">'.$hirdetes['email'].'</div>'
      .'<div class="admin_col6">'.$hirdetes['telefon'].'</div>'
      .'<div class="admin_col3">'.$statusz.'</div>'
      .'</div>';
  
}
?>

        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}
else {
?>

      <div id="content_frame">
      
        <div id="content_head">
          <h1 id="content_title">
            Üzletkötők
            <span></span>
          </h1>
          <div id="content_title_right">
            <a href="admin-uzletkoto.php">Üzletkötők</a>
          </div>
        </div><!--/content_head-->
        
        <div id="content">
        <div class="reg_row reg_row_alt">
          <form action="" method="get">
            Név: <input type="text" name="kereses" /> 
            <input type="submit" value="Keresés" />
          </form>
        </div>
        
        <div class="reg_row reg_row_alt">
          <div class="admin_col7">Üzletkötő</div>
          <div class="admin_col1">Hirdetések (db)</div>
          <div class="admin_col1"><span title="óra:perc:mp (ID)">Legutóbbi hirdetés</span></div>
        </div>
<?php
$uzletkotok = mysql_query("SELECT `uzletkoto`, COUNT(`hiid`) as `hirdetesek_szama`, MAX(`hiid`) as `legutobbi_hirdetes`, MAX(`datum`) as `legutobbi_hirdetes_datum` FROM `hirdetesek` WHERE `uzletkoto` != '' GROUP BY `uzletkoto` ORDER BY `uzletkoto` ASC");

$time = time();
$class = ' reg_row_alt';
while($uzletkoto = mysql_fetch_assoc($uzletkotok)) {
  $class = ($class == '') ? ' reg_row_alt' : '';
  
  echo '<div class="reg_row'.$class.'">'
      .'<div class="admin_col7"><a href="admin-uzletkoto.php?uzletkoto='.urlencode($uzletkoto['uzletkoto']).'">'.htmlspecialchars($uzletkoto['uzletkoto']).'</a></div>'
      .'<div class="admin_col1">'.$uzletkoto['hirdetesek_szama'].'</div>'
      .'<div class="admin_col1"><a title="'.date('H:i:s', $uzletkoto['legutobbi_hirdetes_datum']).'  (ID: '.$uzletkoto['legutobbi_hirdetes'].')" href="hirdetes-admin.php?hirdetes='.$uzletkoto['legutobbi_hirdetes'].'">'.date('Y.m.d', $uzletkoto['legutobbi_hirdetes_datum']).'</a></div>'
      .'</div>';
  
}
?>
        
        </div><!--/content-->
        
      </div><!--/content_frame-->
<?php
}

include 'foot.php';
?>