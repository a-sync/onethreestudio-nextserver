<?php
include 'functions.php';

$search_link = array();

// hiid (ha van)
$hiid = false;
if(is_numeric($_GET['hirdetes'])) {
  // ha egy konkrét hirdetéshez kell
  $hiid = mysql_real_escape_string($_GET['hirdetes']);
}


// ha admin
if(check_admin()) {
  $where = " WHERE";

  if($_GET['tipus'] == 'ellenorzottre') {
    
    $e = mysql_query("UPDATE `esemenyek` SET `statusz` = '0'");
    uj_esemeny('Minden esemény státusza ellenőrzöttre lett állítva. ('.$_COOKIE['name'].')', 0, 1);
  }

  if(is_numeric($hiid)) {
    $where .= " `hiid` = '{$hiid}' AND";
    $search_link['hirdetes'] = 'hirdetes='.$hiid;
  }
}
// ha mod
/*
elseif(is_numeric($_SESSION['admin']['hiid'])) {
  $hiid = mysql_real_escape_string($_SESSION['admin']['hiid']);
  $where = " `hiid` = '{$hiid}' AND";
}
*/
// ha egyik sem
else {
  header("Location: index.php");
  exit;
}


// feltétel
if($_GET['tipus'] == 'log') {
  $where .= " `prioritas` = '0'";
  $search_link['tipus'] = 'tipus=log';
}
else {
  $where .= " `prioritas` > '0'";
  $search_link['tipus'] = 'tipus=';
}

if($data['e_statusz'][$_GET['statusz']] != '') {
  $e_statusz = mysql_real_escape_string($_GET['statusz']);
  
  $where .= " AND `statusz` = '{$e_statusz}'";
  $search_link['statusz'] = 'statusz='.$e_statusz;
}


// rendezés
$rend = strtolower($_GET['rendezes']);
$rend_sql = ' ORDER BY ';
switch($rend) {
  //case 'datum_le': $rend_sql .= '`datum` DESC'; break;
  case 'datum_fel': $rend_sql .= '`datum` ASC'; break;
  case 'prioritas_le': $rend_sql .= '`prioritas` DESC, `datum` DESC'; break;
  case 'prioritas_fel': $rend_sql .= '`prioritas` ASC, `datum` DESC'; break;
  
  default: $rend_sql .= '`datum` DESC, `statusz` DESC';
           $rend = 'datum_le';
}


// limit
$limit = 20;
$jelenlegi = (is_numeric($_GET['oldal'])) ? $_GET['oldal'] : 1;
if($jelenlegi < 1) $jelenlegi = 1;
$start = ($jelenlegi - 1) * $limit;


// kérés
//die("SELECT * FROM `esemenyek`{$where}{$rend_sql} LIMIT {$start}, {$limit}");
$esemenyek = mysql_query("SELECT * FROM `esemenyek`{$where}{$rend_sql} LIMIT {$start}, {$limit}");
$esemenyek_szama = end(mysql_fetch_assoc(mysql_query("SELECT COUNT(`esid`) FROM `esemenyek`{$where}{$rend_sql}")));

include 'head.php';
?>

      <div id="content_frame">
      
        <div id="content_head">
          <h1 id="content_title">
            <?php if($_GET['tipus'] == 'log') echo 'Log'; else echo 'Események'; ?>
            <span>(<?php echo $esemenyek_szama; ?>db)</span>
          </h1>
          <div id="content_title_right">
            <?php if(check_admin()) { ?>
              <a href="hirdetes-esemenyek.php?tipus=ellenorzottre" rel="tip:Az összes esemény és log státuszát állítsa ellenőrzöttre.">Ellenőrzöttre mindet</a>
            <?php } ?>
          </div>
        </div><!--/content_head-->
        
        <div id="content">
          <div class="reg_row reg_row_alt">
            <div class="admin_col2"><a title="óra:perc:mp (ID)" href="hirdetes-esemenyek.php?rendezes=<?php echo (($rend == 'datum_le') ? 'datum_fel' : 'datum_le').'&amp;'.implode('&amp;', $search_link); ?>">Dátum</a></div>
            <div class="admin_col4">Szöveg</div>
            <div class="admin_col5"><a href="hirdetes-esemenyek.php?rendezes=<?php echo (($rend == 'prioritas_le') ? 'prioritas_fel' : 'prioritas_le').'&amp;'.implode('&amp;', $search_link); ?>">Prioritás</a></div>
            <div class="admin_col6">
              <a href="hirdetes-esemenyek.php?rendezes=<?php echo $rend.'&amp;'.$search_link['hirdetes'].'&amp;'.$search_link['tipus']; ?>" title="Minden státuszban lévőt mutasson.">Státusz</a>
              <br/>
              <a href="hirdetes-esemenyek.php?rendezes=<?php echo $rend.'&amp;'.$search_link['hirdetes'].'&amp;'.$search_link['tipus']; ?>&amp;statusz=0">0</a> /
              <a href="hirdetes-esemenyek.php?rendezes=<?php echo $rend.'&amp;'.$search_link['hirdetes'].'&amp;'.$search_link['tipus']; ?>&amp;statusz=1">1</a> /
              <a href="hirdetes-esemenyek.php?rendezes=<?php echo $rend.'&amp;'.$search_link['hirdetes'].'&amp;'.$search_link['tipus']; ?>&amp;statusz=2">2</a>
            </div>
          </div>

<?php
  if($esemenyek != false) {
    $time = time();
    $class = ' reg_row_alt';
    $is_admin = check_admin();
    
    while($esemeny = mysql_fetch_assoc($esemenyek)) {
      $class = ($class == '') ? ' reg_row_alt' : '';
      $text = htmlspecialchars($esemeny['text']);
      $tip = '';
      if(strlen($esemeny['text']) > 80) {
        $text = substr($esemeny['text'], 0, 80).'<strong>...</strong>';
        $tip = ' rel="tip:'.$esemeny['text'].'"';
      }
      
      if($is_admin) {  
        $statusz = '<select class="event_status_select event_status'.$esemeny['statusz'].'" onchange="set_event_status(event, '.$esemeny['esid'].');">';
        //$statusz = '<select class="event_status_select">';
        foreach($data['e_statusz'] as $n => $s) {
          $selected = ($esemeny['statusz'] == $n) ? ' selected="selected"' : '';
          $statusz .= '<option class="event_status'.$n.'" value="'.$n.'"'.$selected.'>'.$s.'</option>';
        }
        $statusz .= '</select>';
      }
      else {
        $statusz = '<select class="event_status_select event_status'.$esemeny['statusz'].'">';
        $statusz .= '<option class="event_status'.$esemeny['statusz'].'">'.$data['e_statusz'][$esemeny['statusz']].'</option>';
        $statusz .= '</select>';
      }
      
      echo '<div class="reg_row'.$class.'">'
          .'<div class="admin_col2" title="'.date('H:i:s', $esemeny['datum']).'  (ID: '.$esemeny['hiid'].')"><a href="hirdetes-admin.php?hirdetes='.$esemeny['hiid'].'">'.date('Y.m.d', $esemeny['datum']).'</a></div>'
          .'<div class="admin_col4"'.$tip.'>'.$text.'</div>'
          .'<div class="admin_col5 event_priority'.$esemeny['prioritas'].'">'.$data['e_prioritas'][$esemeny['prioritas']].'</div>'
          .'<div class="admin_col6">'.$statusz.'</div>'
          .'</div>';
      
    }
  }
?>

        </div><!--/content-->
        
        <?php lapozo($esemenyek_szama, 'hirdetes-esemenyek.php?rendezes='.$rend.'&amp;'.implode('&amp;', $search_link).'&amp;', $limit); ?>
        
      </div><!--/content_frame-->

<?php
include 'foot.php';
?>