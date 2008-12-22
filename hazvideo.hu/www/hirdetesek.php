<?php
include 'functions.php';

$kivalasztottak_uritese = true; // a kiválasztottak listáját ürítse ki ha a gyorslistát nézzük, vagy ha kerestünk
$time = time();
$search_link = '';

$right_bar = 'qlist';

// where
$where = " WHERE `statusz` > '1' AND `ervenyes_datum` > '{$time}'";

$hiid = false;
if(is_numeric($_GET['hirdetes'])) {
  $hiid = mysql_real_escape_string($_GET['hirdetes']);
  $where .= " AND `hiid` = '$hiid'";
  
  $search_link = '&amp;hirdetes='.$hiid;
}
elseif($_GET['hirdetes'] == 'kedvencek') {
  $right_bar = false;

  $where .= " AND `hiid` IN(";
  foreach($_SESSION['qlist'] as $hiid => $qhirdetes) {
	  $where .= "'$hiid', ";
  }
  $where .= "'0')";
  
  $search_link = '&amp;hirdetes=kedvencek';
}
else {
  $where = kereses_szurese($_GET, $search_link, $where);
}


// rendezés
$rend = strtolower($_GET['rendezes']);
$rend_sql = '';
switch($rend) {
  //case 'datum_le': $rend_sql = '`datum` DESC'; break;
  case 'datum_fel': $rend_sql = '`datum` ASC'; break;
  case 'ar_le': $rend_sql = '`ar` DESC, `datum` DESC'; break;
  case 'ar_fel': $rend_sql = '`ar` ASC, `datum` DESC'; break;
  
  default: $rend_sql = '`datum` DESC';
           $rend = 'datum_le';
}
//$search_link = '?rendezes='.$rend.$search_link;


// limit
$limit = 10;
if($kivalasztottak_uritese == true && $_GET['hirdetes'] == 'kedvencek' && count($_SESSION['qlist']) > 0) {
  $limit = count($_SESSION['qlist']); // ne kelljen lapozni, mert ürítés után elvesznek a kiválasztottak
}

$jelenlegi = (is_numeric($_GET['oldal'])) ? $_GET['oldal'] : 1;
if($jelenlegi < 1) $jelenlegi = 1;
$start = ($jelenlegi - 1) * $limit;


// query
//$hirdetesek = mysql_query("SELECT `hiid`, `hirdetes_cime`, `alapterulet`, `ar`, `regio`, `telepules`, `varosresz` FROM `hirdetesek`$where ORDER BY `datum` DESC LIMIT $start, $limit");
$hirdetesek = mysql_query("SELECT `hiid`, `hirdetes_cime`, `alapterulet`, `ar`, `regio`, `telepules`, `varosresz` FROM `hirdetesek`{$where} ORDER BY {$rend_sql} LIMIT {$start}, {$limit}");


// hirdetesek szama
$hirdetesek_szama = mysql_fetch_assoc(mysql_query("SELECT COUNT(`hiid`) as 'osszes' FROM `hirdetesek`{$where}"));
$hirdetesek_szama = $hirdetesek_szama['osszes'];


// ha éppen keresett vagy a kiválasztottakat nézi, ürítse ki a kiválasztott ingatlanok listát
if($kivalasztottak_uritese && (($_ref == 'kereses.php' && $_GET['regio'] != '') || $_GET['hirdetes'] == 'kedvencek')) $_SESSION['qlist'] = array();


include 'head.php';
?>

      <div id="content_frame">
      
        <div id="content_head">
          <h1 id="content_title">
            <?php
              if($_GET['hirdetes'] == 'kedvencek') echo 'Kiválasztott hirdetések';
              else echo 'Hirdetések';
            ?>
            <span>(<?php echo $hirdetesek_szama; ?> db)</span>
          </h1>
          <div id="content_title_right">
            <?php
              if($_GET['hirdetes'] != 'kedvencek') {
                if($rend == 'datum_le') echo '<a href="?rendezes=datum_fel'.$search_link.'">Legrégebbi</a>'; // alap
                else echo '<a href="?rendezes=datum_le'.$search_link.'">Legújabb</a>';
                
                echo ' &nbsp; ';
                
                if($rend == 'ar_le') echo '<a href="?rendezes=ar_fel'.$search_link.'">Legolcsóbb</a>';
                else echo '<a href="?rendezes=ar_le'.$search_link.'">Legdrágább</a>'; // alap
              }
            ?>
          </div>
        </div><!--/content_head-->
        
        <div id="content">
        
        <?php
            $class = '';
            while($hirdetes = mysql_fetch_assoc($hirdetesek)) {
              $class = ($class == '') ? ' video_row_alt' : '';
              $qbutton_class = ($_SESSION['qlist'][$hirdetes['hiid']]) ? 'video_row_quick_remove' : 'video_row_quick_add';
        ?>
          <div id="video_row-<?php echo $hirdetes['hiid']; ?>" class="video_row<?php echo $class; ?>">
            <?php /*<img onclick="open_box(<?php echo $hirdetes['hiid']; ?>);" id="video_pic-<?php echo $hirdetes['hiid']; ?>" alt=" " src="<?php echo 'hirdetesek/'.$hirdetes['hiid'].'/kicsi.jpg'; ?>" class="video_row_pic" />*/ ?>
            <img onclick="open_box(<?php echo $hirdetes['hiid']; ?>);" id="video_pic-<?php echo $hirdetes['hiid']; ?>" alt=" " src="<?php echo 'hirdetes-kep.php?hirdetes='.$hirdetes['hiid']; ?>" class="video_row_pic" />
            <div class="video_row_data">
              <h3 id="video_title-<?php echo $hirdetes['hiid']; ?>" class="video_row_title"><?php echo $hirdetes['hirdetes_cime']; ?></h3>
              <div class="video_row_info1">
                <p>
                  Alapterület: <?php echo $hirdetes['alapterulet']; ?> m<sup>2</sup>
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
                <?php if($_GET['hirdetes'] == 'kedvencek') {
                  $qbutton_class = 'video_row_quick_remove';
                ?>
                  <div id="video_qbutton-<?php echo $hirdetes['hiid']; ?>" onclick="document.getElementById('video_row-<?php echo $hirdetes['hiid']; ?>').style.display = 'none';" class="<?php echo $qbutton_class; ?>"></div>
                <?php } else { ?>
                  <div id="video_qbutton-<?php echo $hirdetes['hiid']; ?>" onclick="qlist_set(<?php echo $hirdetes['hiid']; ?>);" class="<?php echo $qbutton_class; ?>"></div>
                <?php } ?>
                <div class="video_row_play" onclick="open_box(<?php echo $hirdetes['hiid']; ?>);"></div>
              </div>
            </div>
          </div>

        <?php
          }
        ?>
        
        
        
        </div><!--/content-->
        
        <?php lapozo($hirdetesek_szama, 'hirdetesek.php?rendezes='.$rend.$search_link.'&amp;', $limit); ?>
        
      </div><!--/content_frame-->

<?php

include 'foot.php';
?>