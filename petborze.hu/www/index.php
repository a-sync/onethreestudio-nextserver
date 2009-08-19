<?php
include 'functions.php';

$kiemelt_db = 4;
$limit = 50;
$start = 0;

$time = time();
$where = " WHERE `statusz` = '3' AND `ervenyes_datum` > '{$time}' AND `kiemelt_datum` > '{$time}'";
$hirdetesek = mysql_query("SELECT `hiid`, `hirdetes_cime`, `faj`, `fajta`, `ar`, `regio`, `telepules`, `varosresz` FROM `hirdetesek`{$where} ORDER BY RAND() LIMIT {$start}, {$limit}");
$hirdetesek_szama = end(mysql_fetch_assoc(mysql_query("SELECT COUNT(`hiid`) as 'osszes' FROM `hirdetesek`{$where}")));

// kiemeltek megjelenítésének módja
if($_GET['kiemeltek'] != '') {
  if($_GET['kiemeltek'] == 'lapozas') {
    setcookie('kiemeltek', 'lapozas', (time() + 60 * 60 * 24 * 365));
    $_COOKIE['kiemeltek'] = 'lapozas';
  }
  else {
    setcookie('kiemeltek', 'gorgetes', (time() + 60 * 60 * 24 * 365));
    $_COOKIE['kiemeltek'] = 'gorgetes';
  }
}

if($_COOKIE['kiemeltek'] == 'lapozas') $kiemeltek = 'lapozas';
else $kiemeltek = 'gorgetes';

include 'head.php';
?>

      <div id="content_frame">
      
        <div id="content_head">
          <h1 id="content_title">
            Kiemelt hirdetések
            <span>(<?php echo $hirdetesek_szama; ?> db)</span>
          </h1>
          <div id="content_title_right">
            <?php
              if($kiemeltek == 'lapozas') echo '(<a href="?kiemeltek=gorgetes">Görgetés</a>)';
              else echo '(<a href="?kiemeltek=lapozas">Lapozás</a>)';
            ?>
          </div>
        </div><!--/content_head-->
        
<?php if($kiemeltek == 'lapozas') { ?>

        <div id="content" class="home_content" onmouseover="fade_control(true);" onmouseout="fade_control();">
        
        <?php
            $class = '';
            $n = 0;
            while($hirdetes = mysql_fetch_assoc($hirdetesek)) {
              $n++;
              $class = ($class == '') ? ' video_row_alt' : '';
              $qbutton_class = ($_SESSION['qlist'][$hirdetes['hiid']]) ? 'video_row_quick_remove' : 'video_row_quick_add';
			  
              if($n == 1) echo '<div class="home_fade">';
        ?>

            <div class="video_row<?php echo $class; ?>">
              <?php /*<img onclick="open_box(<?php echo $hirdetes['hiid']; ?>);" id="video_pic-<?php echo $hirdetes['hiid']; ?>" alt=" " src="<?php echo 'hirdetesek/'.$hirdetes['hiid'].'/kicsi.jpg'; ?>" class="video_row_pic" />*/ ?>
              <img onclick="open_box(<?php echo $hirdetes['hiid']; ?>);" id="video_pic-<?php echo $hirdetes['hiid']; ?>" alt=" " src="<?php echo 'hirdetes-kep.php?hirdetes='.$hirdetes['hiid']; ?>" class="video_row_pic" />
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
                  <div style="visibility: hidden;" id="video_qbutton-<?php echo $hirdetes['hiid']; ?>" onclick="qlist_set(<?php echo $hirdetes['hiid']; ?>);" class="<?php echo $qbutton_class; ?>"></div>
                  <div class="video_row_play" onclick="open_box(<?php echo $hirdetes['hiid']; ?>);"></div>
                </div>
              </div>
            </div>

        <?php
            if($n == $kiemelt_db) {
              echo '</div>';
              $n = 0;
            }
          }
          if($n != 0) echo '</div>';
        ?>
        
        </div><!--/content-->
        
        <?php if($hirdetesek_szama > $kiemelt_db || true) { ?>
        <div id="content_foot">
          <div id="pager_frame_right">
            <!--[if IE]><span id="pager_frame"><![endif]-->
            <div id="pager" onmouseover="fade_control(true);" onmouseout="fade_control();">
            <?php
              for($i = 0; $i < ceil($hirdetesek_szama / $kiemelt_db); $i++) {
                echo '<div class="pager_num" onclick="fade_control(false, '.$i.');">'.($i + 1).'</div>';
              }
            ?>
            </div>
            <!--[if IE]></span><![endif]-->
          </div>
          
        </div><!--/content_foot-->
        <?php } ?>

<?php } else { ?>

        <div id="content" onmouseover="" onmouseout="">
        
          <div id="home_scroll" onmouseover="start_scroll();" onmouseout="start_scroll(true);">
            <?php
                $class = '';
                while($hirdetes = mysql_fetch_assoc($hirdetesek)) {
                  $class = ($class == '') ? ' video_row_alt' : '';
                  $qbutton_class = ($_SESSION['qlist'][$hirdetes['hiid']]) ? 'video_row_quick_remove' : 'video_row_quick_add';
            ?>
            
            <div class="video_row<?php echo $class; ?>">
              <?php /*<img onclick="open_box(<?php echo $hirdetes['hiid']; ?>);" id="video_pic-<?php echo $hirdetes['hiid']; ?>" alt=" " src="<?php echo 'hirdetesek/'.$hirdetes['hiid'].'/kicsi.jpg'; ?>" class="video_row_pic" />*/ ?>
              <img onclick="open_box(<?php echo $hirdetes['hiid']; ?>);" id="video_pic-<?php echo $hirdetes['hiid']; ?>" alt=" " src="<?php echo 'hirdetes-kep.php?hirdetes='.$hirdetes['hiid']; ?>" class="video_row_pic" />
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
                <div style="visibility: hidden;" id="video_qbutton-<?php echo $hirdetes['hiid']; ?>" onclick="qlist_set(<?php echo $hirdetes['hiid']; ?>);" class="<?php echo $qbutton_class; ?>"></div>
                <div class="video_row_play" onclick="open_box(<?php echo $hirdetes['hiid']; ?>);"></div>
              </div>
              </div>
            </div>
            
            <?php
              }
            ?>
          </div>
          
        </div><!--/content-->

<?php } ?>
      </div><!--/content_frame-->

<?php
include 'foot.php';
?>