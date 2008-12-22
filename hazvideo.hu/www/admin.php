<?php
include 'functions.php';

if(check_admin() == false) {
  $admin = false;
  if($_POST['kuldes']) {
    $passhash = ident($_POST['name'], $_POST['rpass']);
    $admin = check_admin($_POST['name'], $passhash);
  
    if($admin == true) {
      uj_esemeny('Admin bejelentkezett. ('.$_POST['name'].')');
      addCookie($_POST['name'], $passhash);
	    header("Location: admin.php");
      exit;
    }
    else uj_esemeny('Sikertelen admin bejelentkezési kisérlet. (IP: '.$_SERVER['REMOTE_ADDR'].')', 0, 1, 0);
  }
  if($admin == false) {
    include 'head.php';
?>

      <div id="content_frame">
        
        <div id="content_head">
          <h1 id="content_title">
            Admin bejelentkezés
            <span></span>
          </h1>
          <div id="content_title_right">
            
          </div>
        </div><!--/content_head-->
        <div id="content">
          <div class="reg_row reg_row_alt">
            <form action="" method="post">
                  <div class="reg_col_left">
                    <div class="reg_col_left_row">Név:</div>
                    <div class="reg_col_left_row">Jelszó:</div>
                    <div class="reg_col_left_row"> </div>
                  </div>
                  <div class="reg_col_right">
                    <div class="reg_col_right_row"><input type="text" name="name"/></div>
                    <div class="reg_col_right_row"><input type="password" name="rpass"/></div>
                    <div class="reg_col_right_row"><input type="submit" name="kuldes" value="Bejelentkezés"/></div>
                  </div>
            </form>
          </div>
        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
    include 'foot.php';
    exit;
  }
}
elseif($_GET['kilepes'] != '') {
  uj_esemeny('Admin kijelentkezett. ('.$_COOKIE['name'].')');
  delCookie();
  header("Location: index.php");
  exit;
}


$hirdetesek = mysql_query("SELECT `hiid`, `datum`, `statusz`, `email`, `nev`, `telefon`, `ervenyes_datum` FROM `hirdetesek` WHERE `statusz` = '0' ORDER BY `datum` DESC");

include 'head.php';
?>

      <div id="content_frame">
      
        <div id="content_head">
          <h1 id="content_title">
            Új hirdetések
            <span></span>
          </h1>
          <div id="content_title_right">
            <a href="admin-hirdetesek.php">Hirdetések</a>
          </div>
        </div><!--/content_head-->
        
        <div id="content">
        <div class="reg_row reg_row_alt">
          <div class="admin_col2"><span title="óra:perc:mp (ID)">Dátum</span></div>
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
        
      </div><!--/content_frame-->

<?php
include 'foot.php';
?>