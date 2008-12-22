<?php
include 'functions.php';

// hónapok
$honapok_lista[0] = 'Összes';
$honapok_lista[1] = 'Január';
$honapok_lista[2] = 'Február';
$honapok_lista[3] = 'Március';
$honapok_lista[4] = 'Április';
$honapok_lista[5] = 'Május';
$honapok_lista[6] = 'Június';
$honapok_lista[7] = 'Július';
$honapok_lista[8] = 'Augusztus';
$honapok_lista[9] = 'Szeptember';
$honapok_lista[10] = 'Október';
$honapok_lista[11] = 'November';
$honapok_lista[12] = 'December';

// hiid (ha van)
$hiid = false;
if(is_numeric($_GET['hirdetes'])) {
  // ha egy konkrét hirdetéshez kell
  $hiid = mysql_real_escape_string($_GET['hirdetes']);
}

// év és hónap
$jelenlegi_ev = date('Y');
$jelenlegi_honap = date('n');

if(is_numeric($_GET['ev']) && $_GET['ev'] >= 2009 && $_GET['ev'] <= $jelenlegi_ev) $ev = mysql_real_escape_string($_GET['ev']);
else $ev = $jelenlegi_ev;

if(is_numeric($_GET['honap']) && $_GET['honap'] >= 1 && $_GET['honap'] <= 12) $honap = mysql_real_escape_string($_GET['honap']);
//else $honap = false; // év / hónapot mutasson alapból
elseif($_GET['honap'] == '0') $honap = false;
else $honap = $jelenlegi_honap; // évet mutasson alapból


$stat = array();
if(check_admin() || check_mod($hiid)) {
  $admin_hiid = ($hiid === false) ? 0 : $hiid;

  if($honap == false) { // éves
    $query0 = mysql_query("SELECT MAX(`szamlalo`) as `max`, MIN(`szamlalo`) as `min`, AVG(`szamlalo`) as `atlag`, SUM(`szamlalo`) as `osszes` FROM `stat` WHERE `hiid` = '{$admin_hiid}' AND `datum_ev` = '{$ev}' GROUP BY `hiid`");
    $query1 = mysql_query("SELECT SUM(`szamlalo`) as `szamlalo`, `datum_honap` as `stat_idopont` FROM `stat` WHERE `hiid` = '{$admin_hiid}' AND `datum_ev` = '{$ev}' GROUP BY `datum_honap`");
  }
  else { // havi
    $query0 = mysql_query("SELECT MAX(`szamlalo`) as `max`, MIN(`szamlalo`) as `min`, AVG(`szamlalo`) as `atlag`, SUM(`szamlalo`) as `osszes` FROM `stat` WHERE `hiid` = '{$admin_hiid}' AND `datum_ev` = '{$ev}' AND `datum_honap` = '{$honap}' GROUP BY `hiid`");
    $query1 = mysql_query("SELECT `szamlalo`, `datum_nap` as `stat_idopont` FROM `stat` WHERE `hiid` = '{$admin_hiid}' AND `datum_ev` = '{$ev}' AND `datum_honap` = '{$honap}'"); 
  }

  while($stat_adatok = mysql_fetch_assoc($query1)) {
    $stat['adatok'][$stat_adatok['stat_idopont']] = $stat_adatok['szamlalo'];
  }
  
  $stat = @array_merge($stat, mysql_fetch_assoc($query0));

  if($stat['osszes'] < 1) {
    $stat['adatok'][1] = 0;
    $stat['osszes'] = 0;
  }
  
  if($hiid === false) $stat['cim'] = 'Oldalbetöltések ('.$ev;
  else $stat['cim'] = 'Hirdetés megtekintések ('.$ev;

  if($honap != false) $stat['cim'] .= ' '.$honapok_lista[$honap];
  $stat['cim'] .= ') Összesen: '.$stat['osszes'];
  
//die('<pre>'.print_r($stat, true).'</pre>');
}
else {
  // e: illetéktelen
  header("Location: index.php");
  exit;
}

include 'head.php';
if(check_admin() || check_mod($hiid)) {
?>

      <div id="content_frame">
        
        <div id="content_head">
          <h1 id="content_title">
          <?php
            if($hiid === false) echo 'Oldal statisztika';
            else echo 'Hirdetés statisztika';
          ?>
            <span></span>
          </h1>
          <div id="content_title_right">
            
          </div>
        </div><!--/content_head-->
        
        <div id="content">
          <div class="reg_row">
            <center class="stat_center">
              <form action="" method="get">
                <?php if($hiid !== false) echo '<input type="hidden" name="hirdetes" value="'.$hiid.'" />'; ?>
                Év: 
                <?php
                  echo '<select name="ev" class="stat_ev_select">';
                  for($i = $jelenlegi_ev; $i >= 2009; $i--) {
                    $selected = ($i == $ev) ? ' selected="selected"' : '';
                    echo '<option'.$selected.'>'.$i.'</option>';
                  }
                  echo'</select>';
                ?>
                &nbsp; Hónap: 
                <?php
                  echo '<select name="honap" class="stat_honap_select">';
                  foreach($honapok_lista as $kulcs => $ertek) {
                    $selected = ($honap == $kulcs) ? ' selected="selected"' : '';
                    echo '<option value="'.$kulcs.'"'.$selected.'>'.$ertek.'</option>';
                  }
                  echo '</select>';
                ?>
                &nbsp; <input type="submit" value="Mehet" />
                
              </form>
            </center>
            
            <img alt="Statisztika betöltése..." src="<?php echo google_chart_stat($stat['cim'], $stat['adatok'], $ev, $honap, $stat['osszes'], $stat['max'], $stat['min'], $stat['atlag']); ?>" />
            
          </div>
        </div><!--/content-->
        
      </div><!--/content_frame-->

<?php
}

include 'foot.php';
?>