<?php
include 'functions.php';

//die('00'.$_ref);//debug

//header("Content-Type: text/html; charset=utf-8");

if($_GET['type'] == 10) {
  // echo '10';
  $def = ($_ref == 'kereses.php') ? '(Mindegy...)' : 'Települések...';

  if($data['telepules'][$_POST['regio']]) {
    if(count($data['telepules'][$_POST['regio']]) > 1) echo '10';
	  else echo '11';
    
    echo '<select name="telepules" id="reg_telepules" onchange="telepules_valasztas(this);">';
    // echo '<option value="-">'.$def.'</option>';
    if(count($data['telepules'][$_POST['regio']]) > 1) echo '<option value="-">'.$def.'</option>';
    foreach($data['telepules'][$_POST['regio']] as $key => $val) {
      echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
  }
  else {
    echo '10';
	
    echo '<select name="telepules" id="reg_telepules" disabled="disabled">';
    echo '<option value="-">'.$def.'</option>';
    echo '</select>';
  }
}
elseif($_GET['type'] == 20) {
  echo '20';
  $def = ($_ref == 'kereses.php') ? '(Mindegy...)' : 'Városrészek...';
  
  if($data['varosresz'][$_POST['regio']][$_POST['telepules']]) {
    echo '<select name="varosresz" id="reg_varosresz" onchange="search_results(this);">';
    if(count($data['varosresz'][$_POST['regio']][$_POST['telepules']]) > 1) echo '<option value="-">'.$def.'</option>';
    foreach($data['varosresz'][$_POST['regio']][$_POST['telepules']] as $key => $val) {
      echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
  }
  else {
    echo '<select name="varosresz" id="reg_varosresz" disabled="disabled">';
    echo '<option value="-">'.$def.'</option>';
    echo '</select>';
  }
}
elseif($_GET['type'] == 30) {
  echo '30';
  
  $hiid = mysql_real_escape_string($_POST['hirdetes']);
  if(is_numeric($hiid)) {
    $time = time();
    $ervenyes = (check_mod($hiid) || check_admin()) ? '' : " AND `statusz` > '1' AND `ervenyes_datum` > '$time'";
    $hirdetes = mysql_query("SELECT * FROM `hirdetesek` WHERE `hiid` = '{$hiid}'{$ervenyes}");
    $hirdetes = mysql_fetch_assoc($hirdetes);
    
    if($hirdetes['hiid'] == '') echo '<h2>A keresett hirdetés nem elérhető, vagy nem létezik.</h2>';
    else {
      add_stat($hirdetes['hiid']);
    
      foreach($hirdetes as $i => $val) {
        $hirdetes[$i] = htmlspecialchars($val);
      }
      
?>
  
          <div id="video_box_left">
            <h2>Képek</h2>
            <div id="video_box_pictures">
              <?php
                $kepek = hirdetes_kepek($hirdetes['hiid']);
                foreach($kepek as $k => $kep) {
                  echo '<a rel="floatboxKepek-'.$hirdetes['hiid'].'" target="_blank" href="hirdetesek/'.$hirdetes['hiid'].'/'.$kep[0].'">'
                      .'<img id="video_pic-'.$hirdetes['hiid'].'-'.$k.'" class="admin_row_pic" src="hirdetesek/'.$hirdetes['hiid'].'/'.$kep[1].'?'.$time.'" alt=" "/>'// title="'.$kep[0].'"
                      .'</a><br/>';
                }
              ?>
            </div>
          </div>
          <div id="video_box_center">
            <h2>Videó</h2>
            <div id="player">
              <embed wmode="transparent" id="ply" width="478" height="320" flashvars="file=hirdetesek/<?php echo $hirdetes['hiid']; ?>/video.flv" allowscriptaccess="always" allowfullscreen="true" quality="high" bgcolor="#FFFFFF" name="ply" style="" src="player.swf" type="application/x-shockwave-flash" />
            </div>
            <div id="video_box_data">
              <h3><?php echo $hirdetes['hirdetes_cime']{0}.strtolower(substr($hirdetes['hirdetes_cime'], 1)); ?></h3>
              Megjegyzés:<br/>
              <?php echo nl2br($hirdetes['megjegyzes']); ?>
            </div>
          </div>
          <div id="video_box_right">
            <h2>Eladó adatai</h2>
            <?php if(check_admin() != false) { ?>
              <a class="admin_link" href="hirdetes-admin.php?hirdetes=<?php echo $hirdetes['hiid']; ?>">Hirdetés admin</a>
              <br/><br/>
            <?php } ?>
            Név: <?php echo $hirdetes['nev']; ?>
            <br/><br/>
            <span class="video_box_data_gray">Telefon: <?php echo $hirdetes['telefon']; ?></span>
            <br/><br/>
            Email: <?php echo $hirdetes['email']; ?>
            <br/><br/>
            Feladás dátuma: <?php echo date('Y.m.d', $hirdetes['datum']); ?>
            <br/><br/>
            <span class="video_box_data_gray">Hely: <?php echo $data['regio'][$hirdetes['regio']]; ?> - <?php echo $data['telepules'][$hirdetes['regio']][$hirdetes['telepules']]; ?> / <?php echo $data['varosresz'][$hirdetes['regio']][$hirdetes['telepules']][$hirdetes['varosresz']]; ?></span>
            <?php
              if($hirdetes['cim'] != '') {
                echo '<br/><br/><span class="video_box_data_gray">'.$hirdetes['cim'].'</span><br/>'
                    .'<strong onmouseover="tip(event, \'Tekintse meg a címet a Google Maps segítségével!\');" onmouseout="tip(false);"><a class="gmaps_link" target="_blank" '
                    .'href="http://maps.google.com/maps?q='.$data['telepules'][$hirdetes['regio']][$hirdetes['telepules']].', '.$hirdetes['cim'].'">Google Maps!</a></strong>';
              }
            ?>
              <br/><br/>
              Faj: <?php echo $data['faj'][$hirdetes['faj']]; ?>
              <br/><br/>
              <span class="video_box_data_gray">Fajta: <?php echo $data['fajta'][$hirdetes['faj']][$hirdetes['fajta']]; ?></span>
              <br/><br/>
              Kor: <?php echo $data['kor'][$hirdetes['kor']]; ?></span>
              <br/><br/>
              <span class="video_box_data_gray">Nem: <?php echo $data['nem'][$hirdetes['nem']]; ?></span>
              <br/><br/>
              Pedigré: <?php echo $data['pedigre'][$hirdetes['pedigre']]; ?>
          </div>
          <div id="video_box_bottom">
            <form autocomplete="off" id="video_box_send_link" action="" method="get" onsubmit="send_to_friend(<?php echo $hirdetes['hiid']; ?>); return false;" onkeydown="if(event.keyCode == 13 || event.which == 13) send_to_friend(<?php echo $hirdetes['hiid']; ?>);">
              Email: <input maxlength="250" onmouseover="tip(event, 'Hirdetés küldése egy ismerősnek.&lt;br/&gt;(valós email címet adjon meg)');" id="send_to_friend_email" type="text" name="send_to_friend_email" /> 
              <input type="submit" value="Mehet"/> <span id="send_to_friend_error"> </span>
            </form>
            <div id="video_box_print">
              <a target="_blank" href="hirdetes-nyomtatasa.php?hirdetes=<?php echo $hirdetes['hiid']; ?>">Hirdetés nyomtatása! <strong onclick="print_now(<?php echo $hirdetes['hiid']; ?>); return false;" onmouseover="tip(event, 'Nyomtatás azonnal!');">(&nabla;)</strong></a>
            </div>
          </div>
<?php
      
    }
  }
  else echo '<h2>Hibás azonosító ID.</h2>';
}
elseif($_GET['type'] == 40) {
  echo '40';

  if($_SESSION['qlist'][$_POST['hirdetes']]) {
    unset($_SESSION['qlist'][$_POST['hirdetes']]);
  }
  elseif($_ref = 'hirdetesek.php' && is_numeric($_POST['hirdetes'])) {
    //$_SESSION['qlist'][$_POST['hirdetes']]['html'] = urldecode($_POST['html']);
    $_SESSION['qlist'][$_POST['hirdetes']]['pic'] = htmlspecialchars(strip_tags(urldecode($_POST['pic'])));
    $_SESSION['qlist'][$_POST['hirdetes']]['title'] = htmlspecialchars(strip_tags(urldecode($_POST['title'])));
    
    $_SESSION['qlist'][$_POST['hirdetes']]['html'] = '<div class="qlist_row">'
      . '<img alt=" " src="' . $_SESSION['qlist'][$_POST['hirdetes']]['pic'] . '" class="qlist_row_pic" onclick="open_box(' . htmlspecialchars($_POST['hirdetes']) . ');"/>'
      . '<h3 class="qlist_row_title">' . $_SESSION['qlist'][$_POST['hirdetes']]['title'] . '</h3>'
      . '<div class="qlist_row_buttons"><div onclick="qlist_set(' . htmlspecialchars($_POST['hirdetes']) . ');" class="qlist_row_remove"></div><div class="qlist_row_play" onclick="open_box(' . htmlspecialchars($_POST['hirdetes']) . ');"></div></div>'
    . '</div>';
  }
  
  if(!is_array($_SESSION['qlist'])) $_SESSION['qlist'] = array();
  foreach($_SESSION['qlist'] as $hiid => $qhirdetes) {
	  echo $qhirdetes['html'];
  }
}
elseif($_GET['type'] == 50) {
  echo '50';
  $time = time();
  $where = kereses_szurese($_POST, $search_link, " WHERE `statusz` > '1' AND `ervenyes_datum` > '$time'");
  
  $query = mysql_query("SELECT COUNT(`hiid`) FROM `hirdetesek`$where");
  $talalatok = end(mysql_fetch_assoc($query));
  
  echo $talalatok;
}
elseif($_GET['type'] == 60) {
  echo '60';
  
  if(is_numeric($_POST['esid']) && $data['e_statusz'][$_POST['statusz']] != '') {
    $esid = mysql_real_escape_string($_POST['esid']);
    $statusz = mysql_real_escape_string($_POST['statusz']);
    
    $e = mysql_query("UPDATE `esemenyek` SET `statusz` = '$statusz' WHERE `esid` = '$esid' LIMIT 1");
  }
}
elseif($_GET['type'] == 70) {
//echo '00';//debug
  echo '7';
  
  $pattern = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i';
  if(!preg_match($pattern, urldecode($_POST['email']))) {
    echo '0Érvénytelen email cím.';
  }
  elseif(is_numeric($_POST['hiid'])) {
    $time = time();
    $hiid = mysql_real_escape_string($_POST['hiid']);
    $hirdetes = @mysql_fetch_assoc(mysql_query("SELECT `hirdetes_cime` FROM `hirdetesek` WHERE `hiid` = '{$hiid}' AND `statusz` > '1' AND `ervenyes_datum` > '{$time}'"));
    
    if($hirdetes == false) {
      echo '0Nincsen érvényes hirdetés a kapott azonosítóval.';
    }
    else {
      $targy = $hirdetes['hirdetes_cime'];
      $uzenet = 'Üdvözlöm.'."\r\n"
               .'Egyik ismerőse úgy döntött, hogy megosztja önnel a www.petborze.hu egyik hirdetését.'."\r\n"
               .'A hirdetést máris megtekintheti, csak kattintson a levélben található linkre, vagy másolja azt be a böngészőjébe.'."\r\n"
               .''."\r\n"
               .'Hirdetés adatlapja: http://petborze.hu/hirdetesek.php?hirdetes='.$hiid."\r\n"
               .'Hirdetés nyomtatása: http://petborze.hu/hirdetes-nyomtatasa.php?hirdetes='.$hiid."\r\n";
      
      mail_kuldo($_POST['email'], $targy, $uzenet);
      
      echo '1Email elküldve.';
    }
  }
  else {
    echo '0Érvénytelen azonosító.';
  }
}
elseif($_GET['type'] == 80) {
  echo '80';
  $def = ($_ref == 'kereses.php') ? '(Mindegy...)' : 'Fajták...';

  if($data['fajta'][$_POST['faj']]) {
    echo '<select name="fajta" id="reg_fajta">';
    //echo '<option value="-">'.$def.'</option>';
    if(count($data['fajta'][$_POST['faj']]) > 1) echo '<option value="-">'.$def.'</option>';
    foreach($data['fajta'][$_POST['faj']] as $key => $val) {
      echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
  }
  else {
    echo '<select name="fajta" id="reg_fajta" disabled="disabled">';
    echo '<option value="-">'.$def.'</option>';
    echo '</select>';
  }
}
?>