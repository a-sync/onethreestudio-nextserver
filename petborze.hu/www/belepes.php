<?php
include 'functions.php';

//$ne_listazzon = is_numeric($_GET['hirdetes']); // mindig mutassa a hirdetéseim listáját
//$ne_listazzon = true; // csak akkor mutassa, ha egynél több hirdetésem van
$ne_listazzon = (is_numeric($_SESSION['admin']['hiid'])) ? is_numeric($_GET['hirdetes']) : true; // csak akkor mutassa, ha egynél több hirdetésem van, vagy ha bejelentkezve nézem

$hirdeteseim_szama = 0;
if($_GET['kilepes'] != '' && is_numeric($_SESSION['admin']['hiid'])) {
  uj_esemeny('Kijelentkezés.', $_SESSION['admin']['hiid'], 0, 0);
  unset($_SESSION['admin']);

  header("Location: index.php");
  exit;
}
elseif(is_numeric($_GET['hirdetes'])) {
  $hiid = mysql_real_escape_string($_GET['hirdetes']);
  
  if(check_mod($hiid)) {
    header("Location: hirdetes-modositasa.php?hirdetes={$hiid}");
    exit;
  }
  
  $hirdeteseim = mysql_query("SELECT * FROM `hirdetesek` WHERE `hiid` = '{$hiid}' LIMIT 1");
  $hirdeteseim_szama = mysql_num_rows($hirdeteseim);
}
elseif($_GET['email'] != '' || $_SESSION['admin']['email'] != '') {
  if($_GET['email'] != '') $email = mysql_real_escape_string(urldecode($_GET['email']));
  else $email = mysql_real_escape_string($_SESSION['admin']['email']);
  
  $hirdeteseim = mysql_query("SELECT * FROM `hirdetesek` WHERE `email` = '{$email}' ORDER BY `datum` DESC");
  $hirdeteseim_szama = mysql_num_rows($hirdeteseim);
}


// bejelentkezés
if($hirdeteseim_szama == 1 && $ne_listazzon) {
  $hirdetes = mysql_fetch_assoc($hirdeteseim);
  
  if($_POST['rpass'] == $hirdetes['jelszo']) {
    
    $_SESSION['admin']['hiid'] = $hirdetes['hiid'];
    $_SESSION['admin']['email'] = $hirdetes['email'];
    uj_esemeny('Bejelentkezés.', $_SESSION['admin']['hiid'], 0, 0);
    
    header("Location: hirdetes-modositasa.php?hirdetes={$hirdetes['hiid']}");
    exit;
  }
  elseif($_POST['rpass'] != '') {
    uj_esemeny('Sikertelen bejelentkezési kísérlet. (IP: '.$_SERVER['REMOTE_ADDR'].')', $hirdetes['hiid'], 1, 0);
  }
}

$time = time();
include 'head.php';
/*
if(1==3) {
?>

      <div id="content_frame">
        
        <div id="content_head">
          <h1 id="content_title">
            
            <span></span>
          </h1>
          <div id="content_title_right">
            
          </div>
        </div><!--/content_head-->
        
        <div id="content">
          
          
          
        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}
else*/if($hirdeteseim_szama == 1 && $ne_listazzon) {

?>

      <div id="content_frame">
        
        <div id="content_head">
          <h1 id="content_title">
            Belépés
            <span></span>
          </h1>
          <div id="content_title_right">
            
          </div>
        </div><!--/content_head-->
        <div id="content">
        
        <?php
          $class = ($class == '') ? ' video_row_alt' : '';
          $qbutton_class = ($_SESSION['qlist'][$hirdetes['hiid']]) ? 'video_row_quick_remove' : 'video_row_quick_add';
        ?>
          <div class="video_row<?php echo $class; ?>">
            <img <?php if($hirdetes['ervenyes_datum'] > $time) echo 'onclick="open_box('.$hirdetes['hiid'].');" '; ?>id="video_pic-<?php echo $hirdetes['hiid']; ?>" alt=" " src="<?php echo 'hirdetes-kep.php?hirdetes='.$hirdetes['hiid']; ?>" class="video_row_pic" />
            <div class="video_row_data">
              <h3 id="video_title-<?php echo $hirdetes['hiid']; ?>" class="video_row_title"><?php echo $hirdetes['hirdetes_cime']; ?></h3>
              <div class="video_row_info1">
                <p>
                  Faj: <?php echo $data['faj'][$hirdetes['faj']]; ?>
                </p>
                Ár: <?php echo number_format($hirdetes['ar'], 0, '', ' '); ?> Ft
              </div>
              <div class="video_row_info2">
                <p>
                <?php echo $data['regio'][$hirdetes['regio']]; ?>
                </p>
                <?php echo $data['telepules'][$hirdetes['regio']][$hirdetes['telepules']].' / '.$data['varosresz'][$hirdetes['regio']][$hirdetes['telepules']][$hirdetes['varosresz']]; ?>
              </div>
              <div class="video_row_buttons">
                <div style="visibility: hidden;" id="video_qbutton-<?php echo $hirdetes['hiid']; ?>" class="<?php echo $qbutton_class; ?>"></div>
                <div <?php if($hirdetes['ervenyes_datum'] <= $time) echo 'style="visibility: hidden; "'; ?>class="video_row_play" onclick="open_box(<?php echo $hirdetes['hiid']; ?>);"></div>
              </div>
            </div>
          </div>
          
          <div class="reg_row">
            <form action="" method="post">
                  <div class="reg_col_left">
                    <div class="reg_col_left_row">Jelszó:</div>
                    <div class="reg_col_left_row"> </div>
                    <div class="reg_col_left_row"> </div>
                  </div>
                  <div class="reg_col_right">
                    <div class="reg_col_right_row"><input type="password" name="rpass"/></div>
                    <div class="reg_col_right_row"><input type="submit" value="Bejelentkezés"/></div>
                    <?php if($_POST['rpass'] != '') { ?>
                      <div class="reg_col_right_row reg_error_text">Helytelen jelszó.<?php //echo $hirdetes['jelszo']; ?></div>
                    <?php } ?>
                  </div>
            </form>
          </div>
          
        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}
elseif($hirdeteseim_szama > 0) {

?>

      <div id="content_frame">
        
        <div id="content_head">
          <h1 id="content_title">
            Belépés
            <span></span>
          </h1>
          <div id="content_title_right">
            
          </div>
        </div><!--/content_head-->
        <div id="content">
        
          <div class="reg_row">
            <strong>Kérem válassza ki, hogy melyik hirdetéséhez szeretne bejelentkezni.</strong>
            <br/>
            <br/>
          </div>
          
        <?php
            $class = '';
            while($hirdetes = mysql_fetch_assoc($hirdeteseim)) {
              $class = ($class == '') ? ' video_row_alt' : '';
              $qbutton_class = ($_SESSION['qlist'][$hirdetes['hiid']]) ? 'video_row_quick_remove' : 'video_row_quick_add';
              $button_value = (check_mod($hirdetes['hiid'])) ? 'value="Szerkesztés" rel="tip:Jelenleg ezzel a hirdetéssel vagy bejelentkezve." ' : 'value="Kiválaszt" ';
        ?>
          <div class="video_row<?php echo $class; ?>">
            <img <?php if($hirdetes['ervenyes_datum'] > $time) echo 'onclick="open_box('.$hirdetes['hiid'].');" '; ?>id="video_pic-<?php echo $hirdetes['hiid']; ?>" alt=" " src="<?php echo 'hirdetes-kep.php?hirdetes='.$hirdetes['hiid']; ?>" class="video_row_pic" />
            <div class="video_row_data">
              <h3 id="video_title-<?php echo $hirdetes['hiid']; ?>" class="video_row_title"><?php echo $hirdetes['hirdetes_cime']; ?></h3>
              <div class="video_row_info1">
                <p>
                  Faj: <?php echo $data['faj'][$hirdetes['faj']]; ?>
                </p>
                Ár: <?php echo number_format($hirdetes['ar'], 0, '', ' '); ?> Ft
              </div>
              <div class="video_row_info2">
                <p>
                <?php echo $data['regio'][$hirdetes['regio']]; ?>
                </p>
                <?php echo $data['telepules'][$hirdetes['regio']][$hirdetes['telepules']].' / '.$data['varosresz'][$hirdetes['regio']][$hirdetes['telepules']][$hirdetes['varosresz']]; ?>
              </div>
              <div class="video_row_buttons">
                <div style="visibility: hidden;" id="video_qbutton-<?php echo $hirdetes['hiid']; ?>" class="<?php echo $qbutton_class; ?>"></div>
                <?php /*<!--<div class="video_row_play" onclick="open_box(<?php //echo $hirdetes['hiid']; ?>);"></div>-->*/ ?>
                <form action="" method="get">
                  <input type="hidden" name="hirdetes" value="<?php echo $hirdetes['hiid']; ?>" />
                  <?php /*<!--<input type="hidden" name="email" value="<?php //echo htmlspecialchars($hirdetes['email']); ?>" />-->*/ ?>
                  <input class="login_select_submit" type="submit" <?php echo $button_value; ?>/>
                </form>
              </div>
            </div>
          </div>
        <?php
          }
        ?>
          
        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}
else {
//die('bug 2'); // dbg
?>

      <div id="content_frame">
        
        <div id="content_head">
          <h1 id="content_title">
            Belépés
            <span></span>
          </h1>
          <div id="content_title_right">
            
          </div>
        </div><!--/content_head-->
        <div id="content">
          <div class="reg_row reg_row_alt">
            <form action="" method="get">
                  <div class="reg_col_left">
                    <div class="reg_col_left_row">Email:</div>
                    <div class="reg_col_left_row"> </div>
                    <div class="reg_col_left_row"> </div>
                  </div>
                  <div class="reg_col_right">
                    <div class="reg_col_right_row"><input type="text" name="email"/></div>
                    <div class="reg_col_right_row"><input type="submit" value="Mehet"/></div>
                    <?php if($_GET['hirdetes'] != '') { ?>
                      <div class="reg_col_right_row reg_error_text">Nincs hirdetés ezzel az azonosítóval.</div>
                    <?php } elseif($_GET['email'] != '') { ?>
                      <div class="reg_col_right_row reg_error_text">Nincs hirdetés ezzel az email címmel.</div>
                    <?php } ?>
                  </div>
            </form>
          </div>
        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}
include 'foot.php';
?>