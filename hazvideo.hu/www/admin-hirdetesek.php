<?php
include 'functions.php';

if(check_admin() == false) {
  header("Location: admin.php");
  exit;
}

$limit = 20;
$jelenlegi = (is_numeric($_GET['oldal'])) ? $_GET['oldal'] : 1;
if($jelenlegi < 1) $jelenlegi = 1;
$start = ($jelenlegi - 1) * $limit;


$hirdetesek_szama = mysql_fetch_assoc(mysql_query("SELECT COUNT(`hiid`) as 'osszes' FROM `hirdetesek` ORDER BY `datum` DESC"));
$hirdetesek_szama = $hirdetesek_szama['osszes'];


// rendezés
$rend = strtolower($_GET['rendezes']);
$rend_sql = '';
$datum_irany = 'DESC';
switch($rend) {
  case 'datum_le': $rend_sql = ''; break;
  case 'datum_fel': $datum_irany = 'ASC'; break;
  case 'nev_le': $rend_sql = '`nev` DESC, '; break;
  case 'nev_fel': $rend_sql = '`nev` ASC, '; break;
  case 'email_le': $rend_sql = '`email` DESC, '; break;
  case 'email_fel': $rend_sql = '`email` ASC, '; break;
  case 'telefon_le': $rend_sql = '`telefon` DESC, '; break;
  case 'telefon_fel': $rend_sql = '`telefon` ASC, '; break;
  case 'statusz_le': $rend_sql = '`statusz` DESC, '; break;
  case 'statusz_fel': $rend_sql = '`statusz` ASC, '; break;
  
  default: $rend = 'datum_le';
}


$hirdetesek = mysql_query("SELECT `hiid`, `datum`, `statusz`, `email`, `nev`, `telefon`, `ervenyes_datum` FROM `hirdetesek` ORDER BY {$rend_sql}`datum` {$datum_irany} LIMIT {$start}, {$limit}");


include 'head.php';
?>

      <div id="content_frame">
      
        <div id="content_head">
          <h1 id="content_title">
            Hirdetések
            <span></span>
          </h1>
          <div id="content_title_right">
            <a href="admin.php">Új hirdetések</a>
          </div>
        </div><!--/content_head-->
        
        <div id="content">
        <div class="reg_row reg_row_alt">
          <div class="admin_col2"><a title="óra:perc:mp (ID)" href="?rendezes=<?php echo ($rend == 'datum_fel') ? 'datum_le' : 'datum_fel'; ?>">Dátum</a></div>
          <div class="admin_col1"><a href="?rendezes=<?php echo ($rend == 'nev_fel') ? 'nev_le' : 'nev_fel'; ?>">Név</a></div>
          <div class="admin_col7"><a href="?rendezes=<?php echo ($rend == 'email_fel') ? 'email_le' : 'email_fel'; ?>">Email</a></div>
          <div class="admin_col6"><a href="?rendezes=<?php echo ($rend == 'telefon_fel') ? 'telefon_le' : 'telefon_fel'; ?>">Telefon</a></div>
          <div class="admin_col3"><a href="?rendezes=<?php echo ($rend == 'statusz_fel') ? 'statusz_le' : 'statusz_fel'; ?>">Státusz</a></div>
        </div>

<?php
$time = time();
$class = ' reg_row_alt';
while($hirdetes = mysql_fetch_assoc($hirdetesek)) {
  $class = ($class == '') ? ' reg_row_alt' : '';
  
  $statusz = ($time > $hirdetes['ervenyes_datum']) ? '<u>'.$data['statusz'][$hirdetes['statusz']].'</u>' : $data['statusz'][$hirdetes['statusz']];
  
  echo '<div class="reg_row'.$class.'">'
      .'<div class="admin_col2"><a title="'.date('H:i:s', $hirdetes['datum']).'  (ID: '.$hirdetes['hiid'].')" href="hirdetes-admin.php?hirdetes='.$hirdetes['hiid'].'">'.date('Y.m.d', $hirdetes['datum']).'</a></div>'
      .'<div class="admin_col1">'.$hirdetes['nev'].'</div>'
      .'<div class="admin_col7">'.$hirdetes['email'].'</div>'
      .'<div class="admin_col6">'.$hirdetes['telefon'].'</div>'
      .'<div class="admin_col3">'.$statusz.'</div>'
      .'</div>';
  
}
?>

        </div><!--/content-->

        <?php lapozo($hirdetesek_szama, 'admin-hirdetesek.php?rendezes='.$rend.'&amp;', $limit); ?>

      </div><!--/content_frame-->

<?php
include 'foot.php';
?>