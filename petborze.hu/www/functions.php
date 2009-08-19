<?php
error_reporting(E_ALL ^ E_NOTICE);//normál
//error_reporting(0);//nemkellenek hibaüzenetek: 0
//set_time_limit(0);
session_start();
//mktime helyett strtotime();

require_once('config.php');

$rpl_connection = @mysql_connect($db['dbhost'], $db['dbuser'], $db['dbpass']) or die('Nem sikerült csatlakozni a szerverhez!');
@mysql_select_db($db['dbname'], $rpl_connection) or die('Nem sikerült csatlakozni az adatbázishoz!');

$_ref = array_shift(explode('?', end(explode('/', @$_SERVER['HTTP_REFERER']))));

function uj_esemeny($text, $hiid = 0, $statusz = 0, $prioritas = 0) {
  global $data;

  $text = mysql_real_escape_string(substr($text, 0, 32000));
  $hiid = mysql_real_escape_string($hiid);

  if(is_numeric($hiid) && $data['e_statusz'][$statusz] != '' && $data['e_prioritas'][$prioritas] != '') {
//die('alma4');
    $datum = time();
    //if($prioritas == 0) $statusz = 0; // ha jelentéktelen a prioritás akkor nemkell ellenőrizni
    
    $query = "INSERT INTO `esemenyek` (`esid`, `hiid`, `text`, `statusz`, `prioritas`, `datum`) VALUES"
            ." (NULL, '{$hiid}', '{$text}', '{$statusz}', '{$prioritas}', '$datum')";
    $q = mysql_query($query);
    
    // last insert id (ha kell)
    return $q;
  }
  else {
    return false;
  }
}

function add_stat($hiid = 0) {
  $hiid = mysql_real_escape_string($hiid);
  
  if(is_numeric($hiid)) {
    $datum_ev = date('Y');
    $datum_honap = date('n');
    $datum_nap = date('j');
    
    $q = mysql_query("UPDATE `stat` SET `szamlalo` = `szamlalo` + 1 WHERE `hiid` = '{$hiid}' AND `datum_ev` = '{$datum_ev}' AND `datum_honap` = '{$datum_honap}' AND `datum_nap` = '{$datum_nap}' LIMIT 1");
    $affected = mysql_affected_rows();
    
    if($affected != 1) {
      $q = mysql_query("INSERT INTO `stat` (`hiid`, `szamlalo`, `datum_ev`, `datum_honap`, `datum_nap`) VALUES ('{$hiid}', '1', '{$datum_ev}', '{$datum_honap}', '{$datum_nap}')");
    }
    
    return $q;
  }
  else {
    return false;
  }
}


function mail_kuldo($cimzett, $targy, $uzenet, $valaszcim = 'noreplay@petborze.hu') {
  $headers ='From: Petborze.hu <ertesito@petborze.hu>'."\r\n"
    .'Reply-To: '.$valaszcim."\r\n"
    .'Return-Path: '.$valaszcim."\r\n"
    .'X-Mailer: PHP/'.phpversion()."\r\n";

    $uzenet .= "\r\n\r\n".'www.petborze.hu - `Szlogen.`'."\r\n\n";
    
  return mail($cimzett, substr($targy, 0, 250), $uzenet, $headers);
}

// a tulfutott idoknel ne mutasson 0-at hanem -1-el legyen üresnek jelölve
function google_chart_stat($cim, $adatok, $ev = false, $honap = false, $osszes = false, $max = false, $min = false, $atlag = false) {
//rendes év / hónap / nap értékeket fogadó változat
  $ev = (integer) floor($ev);
  $honap = (integer) floor($honap);

  $jelenlegi_ev = date('Y');
  $jelenlegi_honap = date('n');
  $jelenlegi_nap = date('j');

  // alapvetően feltételezzük, hogy nem kapunk olyan időpontot ami a jövőben van
  //if($ev > $jelenlegi_ev || ($ev == $jelenlegi_ev && $honap > $jelenlegi_honap)) return false; // nullázott tömböt is tehetünk, hogy mindig legyen várt(/kért) kimenet
  if($ev > $jelenlegi_ev || ($ev == $jelenlegi_ev && $honap > $jelenlegi_honap)) $adatok[1] = 0;

  if($ev == false) $ev = date('Y');

  if($honap == false) {
    $ido_label_hossz = 12;
    
    if($jelenlegi_ev == $ev && $ido_label_hossz > $jelenlegi_honap) {
      $lemert_pontok = $jelenlegi_honap;
    }
    else $lemert_pontok = $ido_label_hossz;
  }
  else {
    //$d_time = mktime(0, 0, 0, $honap, 1, $ev);
    //if(is_numeric($d_time)) $ido_label_hossz = date('t', $d_time);
    
    $ido_label_hossz = @date('t', @mktime(0, 0, 0, $honap, 1, $ev));
    if(!is_numeric($ido_label_hossz)) $ido_label_hossz = 31;
    
    if($jelenlegi_ev == $ev && $jelenlegi_honap == $honap && $ido_label_hossz > $jelenlegi_nap) {
      $lemert_pontok = $jelenlegi_nap;
    }
    else $lemert_pontok = $ido_label_hossz;
  }

  if(!is_array($adatok)) $adatok = array();
  $szurt_adatok = array();

/*
  // a nullával töltögetés nem kell, ezért elég a foreach()
  ksort($adatok, SORT_NUMERIC);
  reset($adatok);
  for($n = 1; $n <= $lemert_pontok; $n++) {
    $val = (integer) floor(current($adatok));
    $key = (integer) key($adatok);
    
    if($key == $n) {
      $szurt_adatok[$n] = $val;
      next($adatok);
    }
    else {
      $szurt_adatok[$n] = 0;
    }
  }
*/

  foreach($adatok as $key => $val) {
    $key = (integer) floor($key);
    $val = (integer) floor($val); // csak egész számokat várunk
    
    if($key <= $lemert_pontok) {
      $szurt_adatok[$key] = $val;
    }
  }

  if(!is_numeric($osszes)) $osszes = array_sum($szurt_adatok);
  if(!is_numeric($max)) $max = max($szurt_adatok);
  if(!is_numeric($min)) $min = (($lemert_pontok > count($szurt_adatok)) ? 0 : min($szurt_adatok));
  if(!is_numeric($atlag)) $atlag = $osszes / $lemert_pontok;

  $chart_data = '';
  if($max > 0) $egy_egyseg_szazalek = (100 / $max);
  else $egy_egyseg_szazalek = 0;
  
  for($m = 1; $m <= $ido_label_hossz; $m++) {
    if($chart_data !== '') $chart_data .= ',';
    
    if(isset($szurt_adatok[$m])) {
      $chart_data .= number_format($szurt_adatok[$m] * $egy_egyseg_szazalek, 1, '.', '');
    }
    elseif($m <= $lemert_pontok) {
      $chart_data .= '0';
    }
    else {
      $chart_data .= '-1';
    }
  }

  return 'http://chart.apis.google.com/chart?' // elérés
        
        .'cht=lc&amp;' // chart típus
        
        .'chs=570x300&amp;' // chart mérete
        
        .'chco=FF9900&amp;' // vonal színe
        
        .'chls=2,1,0&amp;' // vonal vastagság, vonal hossza, üres szakasz hossza
        
        .'chm=o,cc6600,0,-1,4,0&amp;' // adatpontok típusa (o(kör)), szín, N-edik pontnál (minusszal minden N-ediknél), méret, prioritás (-1, 0, 1)
        
        .'chf=' // chart felületen színezés (bg(háttér), c(chart terület), a(teljes áttetszés)), színezés típus (s(szolid)), szín
                // chart felületen színezés (bg(háttér), c(chart terület), a(teljes áttetszés)), színezés típus (ls(sávozás)), forgatás szöge (ls), szín, szélesség (0-1), szín, szélesség ...
          .'bg,s,212121|'
          //.'a,s,FFFFFF|'
          //.'c,s,FFFFFF'
          .'c,ls,90,202020,0.04,2a2a2a,0.08,202020,0.04&amp;'
        
        .'chtt='.htmlspecialchars($cim).'&amp;' // cím
        
        .'chts=AAAAAA,15&amp;' // cím betűszín, betűméret
        
        .'chxt=x,y,r&amp;' // chart melyik oldalán legyenek labelek (lent, bal, jobb)
        
        .'chxl=' // label index, label elemek
          .'2:|min ('.floor($min).')|átlag ('.round($atlag).')|max ('.floor($max).')&amp;' // jobboldali label elemei
        
        .'chxs=' // label index, betűszín, betűméret, igazítás (-1(bal), 0(közép), 1(jobb)), vonal (l(fővonal), t(jelölések), lt(fővonal jelölésekkel)), jelölések színe
          .'0,9A9A9A,8,0,l,0A0A0A|' // alsó label
          .'1,808080,10,1,lt,0A0A0A|' // baloldali label
          .'2,6A6A6A,10,0,lt,0A0A0A&amp;' // jobboldali label
        
        .'chxp=2,'.number_format(($egy_egyseg_szazalek * $min), 4, '.', '').','.number_format(($egy_egyseg_szazalek * $atlag), 4, '.', '').',100|&amp;' // jobboldali label elemek pozíciója (0-100)
        
        .'chxr=' // label index, label számozásának kezdete, vége, intervallum
          .'0,1,'.$ido_label_hossz.',1|' // alsó label (év | hónap)
          //.'1,0,'.floor($max).','.round($max * 0.10).'&amp;' // baloldali label (statisztika)
          .'1,0,'.floor($max).'&amp;' // baloldali label (statisztika)
        
        .'chg='.number_format((100 / ($ido_label_hossz - 1)), 4, '.', '').',0,3,4&amp;' // vonalazás intervalluma X tengelyen (0-100), Y tengelyen, vonal hossza, üres szakasz hossza
        
        .'chd=t:'.$chart_data // chart adatok felsorolva, vesszovel elvalasztva (0.0-100.0 || -1)
        .'';
}




function addCookie($name, $passhash) {
  $time = time() + 60 * 60 * 24 * 1;//1 napon át érvényes a süti

  setcookie('name', strtolower($name), $time, '/', $_SERVER['SERVER_NAME']);
  setcookie('passhash', $passhash, $time, '/', $_SERVER['SERVER_NAME']);
}

function delCookie() {
  $time = time() - 60 * 60 * 24 * 1;

  setcookie('name', '', $time, '/', $_SERVER['SERVER_NAME']);
  setcookie('passhash', '', $time, '/', $_SERVER['SERVER_NAME']);
}

function ident($name, $rpass, $salt = '*Nagy0N # Tit0K*') {
  $nameB64hex = bin2hex(base64_encode(strtolower($name).$salt));
  $rpassB64hex = bin2hex(base64_encode($rpass.$salt));

  return md5($nameB64hex.$rpassB64hex.$nameB64hex);
}

function lapozo($osszesen, $link, $oldal = 20, $jelenlegi = false) {
  $gomb = floor($osszesen / $oldal);
  if($osszesen % $oldal != 0 || $osszesen == 0) $gomb++;
  
  if(!is_numeric($jelenlegi)) {
    $jelenlegi = (is_numeric($_GET['oldal'])) ? $_GET['oldal'] : 1;
  }
  if($jelenlegi < 1) $jelenlegi = 1;
  
  $pager_minus = ($jelenlegi <= 1) ? 1 : ($jelenlegi - 1);
  $pager_plus = ($jelenlegi >= $gomb) ? $gomb : ($jelenlegi + 1);
?>

        <div id="content_foot">
          <div id="pager_frame">
            <!--[if IE]><span id="pager_frame"><![endif]-->
            <div id="pager">
              <a id="pager_first" title="Első lap" href="<?php echo $link . 'oldal=1'; ?>"> </a>
              <a id="pager_minus" title="Előző lap" href="<?php echo $link . 'oldal='.$pager_minus; ?>"> </a>
              <?php
                for($i = 1; $i <= $gomb; $i++) {
                  $class = ($i == $jelenlegi) ? ' pager_num_active' : '';
                  echo '<a class="pager_num'.$class.'" href="'.$link.'oldal='.$i.'">'.$i.'</a>';
                }
              ?>
              <a id="pager_plus" title="Következő lap" href="<?php echo $link . 'oldal='.$pager_plus; ?>"> </a>
              <a id="pager_last" title="Utolsó lap" href="<?php echo $link . 'oldal='.$gomb; ?>"> </a>
            </div>
            <!--[if IE]></span><![endif]-->
          </div>
        </div><!--/content_foot-->

<?php
}

function adatok_szurese($tomb, &$hibak, $escape = false, $feladas = false) {
  global $data;
  
  // név
  if($tomb['nev'] == '') $hibak['nev'] = 'Nincs név megadva.';
  $tomb['nev'] = substr(strip_tags($tomb['nev']), 0, 250);
  if($escape) $tomb['nev'] = mysql_real_escape_string($tomb['nev']);
  else $tomb['nev'] = htmlspecialchars($tomb['nev']);
  
  // telefon
  $tomb['telefon'] = preg_replace('/\W/', '', substr(strip_tags($tomb['telefon']), 0, 250));
  if($tomb['telefon'] == '') $hibak['telefon'] = 'Nincs telefonszám megadva.';
  elseif(!is_numeric($tomb['telefon']) || strlen(strip_tags($tomb['telefon'])) < 6) $hibak['telefon'] = 'Valós telefonszámot kell megadnod.';
  if($escape) $tomb['telefon'] = mysql_real_escape_string($tomb['telefon']);
  else $tomb['telefon'] = htmlspecialchars($tomb['telefon']);
  
  // email
  $pattern = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i';
  if(!preg_match($pattern, $tomb['email'])) $hibak['email'] = 'Valós emailt adj meg.';
  //if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $tomb['email'])) $hibak['email'] = 'Valós emailt adj meg.';
  $tomb['email'] = substr(strip_tags($tomb['email']), 0, 250);
  if($escape) $tomb['email'] = mysql_real_escape_string($tomb['email']);
  else $tomb['email'] = htmlspecialchars($tomb['email']);
  
  // jelszó
  if($tomb['jelszo'] == '') $hibak['jelszo'] = 'A jelszó nincs megadva.';
  elseif(strlen($tomb['jelszo']) < 4) $hibak['jelszo'] = 'A jelszó legalább 4 karakter legyen.';
  $tomb['jelszo'] = substr($tomb['jelszo'], 0, 32);
  if($escape) $tomb['jelszo'] = mysql_real_escape_string($tomb['jelszo']);
  else $tomb['jelszo'] = htmlspecialchars($tomb['jelszo']);
  
  // régió
  if(!is_numeric($tomb['regio']) || $data['regio'][$tomb['regio']] == '') {
    $hibak['regio'] = 'Érvénytelen régió.';
    $tomb['regio'] = '';
  }
  //else $tomb['regio'] = substr($tomb['regio'], 0, 10);
  
  // település
  if(!is_numeric($tomb['telepules']) || $data['telepules'][$tomb['regio']][$tomb['telepules']] == '') {
    $hibak['telepules'] = 'Érvénytelen település.';
    $tomb['telepules'] = '';
  }
  //else $tomb['telepules'] = substr($tomb['telepules'], 0, 10);
  
  // városrész
  if(!is_numeric($tomb['varosresz']) || $data['varosresz'][$tomb['regio']][$tomb['telepules']][$tomb['varosresz']] == '') {
    $hibak['varosresz'] = 'Érvénytelen városrész.';
    $tomb['varosresz'] = '';
  }
  //else $tomb['varosresz'] = substr($tomb['varosresz'], 0, 10);
  
  // faj
  if(!is_numeric($tomb['faj']) || $data['faj'][$tomb['faj']] == '') {
    //$hibak['faj'] = 'Érvénytelen faj.';
    $tomb['faj'] = '';
  }
  //else $tomb['faj'] = substr($tomb['faj'], 0, 10);
  
  // fajta
  if(!is_numeric($tomb['fajta']) || $data['fajta'][$tomb['faj']][$tomb['fajta']] == '') {
    //$hibak['fajta'] = 'Érvénytelen fajta.';
    $tomb['fajta'] = '';
  }
  //else $tomb['fajta'] = substr($tomb['fajta'], 0, 10);
  
  // kor
  if(!is_numeric($tomb['kor']) || $data['kor'][$tomb['kor']] == '') {
    //$hibak['kor'] = 'Érvénytelen kor.';
    $tomb['kor'] = '';
  }
  //else $tomb['kor'] = substr($tomb['kor'], 0, 10);
  
  // ár
  $tomb['ar'] = preg_replace('/\W/', '', $tomb['ar']);
  if(!is_numeric($tomb['ar'])) {
    $hibak['ar'] = 'Az árnak numerikus értéknek kell lennie, forintban értve.';
    $tomb['ar'] = '';
  }
  else $tomb['ar'] = substr($tomb['ar'], 0, 10);
  
  // pedigré
  if(!is_numeric($tomb['pedigre']) || $data['pedigre'][$tomb['pedigre']] == '') {
    //$hibak['pedigre'] = 'Érvénytelen pedigré típus.';
    $tomb['pedigre'] = '';
  }
  //else $tomb['pedigre'] = substr($tomb['pedigre'], 0, 10);
  
  // nem
  if(!is_numeric($tomb['nem']) || $data['nem'][$tomb['nem']] == '') {
    //$hibak['nem'] = 'Érvénytelen nem.';
    $tomb['nem'] = '';
  }
  //else $tomb['nem'] = substr($tomb['nem'], 0, 10);
  
  // cím
  //if($tomb['cim'] == '') $hibak['cim'] = 'Nincs cím megadva.';
  $tomb['cim'] = substr(strip_tags($tomb['cim']), 0, 3200);
  if($escape) $tomb['cim'] = mysql_real_escape_string($tomb['cim']);
  else $tomb['cim'] = htmlspecialchars($tomb['cim']);
  
  // megjegyzés
  //if($tomb['megjegyzes'] == '') $hibak['megjegyzes'] = 'Nincs megjegyzés megadva.';
  $tomb['megjegyzes'] = substr(strip_tags($tomb['megjegyzes']), 0, 3200);
  if($escape) $tomb['megjegyzes'] = mysql_real_escape_string($tomb['megjegyzes']);
  else $tomb['megjegyzes'] = htmlspecialchars($tomb['megjegyzes']);
  
  if($feladas == false && (check_admin() || check_mod($tomb['hiid']))) {
  //if(check_admin()) {
    // azonosító ID
    if(!is_numeric($tomb['hiid'])) {
      $hibak['hiid'] = 'Érvénytelen azonosító ID.';
      $tomb['hiid'] = false;
    }
    
    // hirdetés címe
    //if($tomb['cime'] == '') $hibak['cime'] = 'A hirdetés címe nincs megadva.';
    $tomb['hirdetes_cime'] = substr(strip_tags($tomb['hirdetes_cime']), 0, 250);
    if($escape) $tomb['hirdetes_cime'] = mysql_real_escape_string($tomb['hirdetes_cime']);
    else $tomb['hirdetes_cime'] = htmlspecialchars($tomb['hirdetes_cime']);
    
    // státusz
    if(!is_numeric($tomb['statusz']) || $data['statusz'][$tomb['statusz']] == '') {
      $hibak['statusz'] = 'Érvénytelen státusz típus.';
      $tomb['statusz'] = '';
    }
    //else $tomb['statusz'] = substr($tomb['statusz'], 0, 10);
    
    // üzletkötő
    $tomb['uzletkoto'] = substr(strip_tags($tomb['uzletkoto']), 0, 250);
    if($escape) $tomb['uzletkoto'] = mysql_real_escape_string($tomb['uzletkoto']);
    else $tomb['uzletkoto'] = htmlspecialchars($tomb['uzletkoto']);
    
    // érvényesség
    if($tomb['ervenyes_datum'] == '') $tomb['ervenyes_datum'] = '946684800';
    else {
      $ervenyes_datum = explode('-', $tomb['ervenyes_datum']);
      $ervenyes_datum = mktime(23, 59, 59, $ervenyes_datum[1], $ervenyes_datum[2], $ervenyes_datum[0]);
    }
    if($ervenyes_datum === false) {
      //$hibak['ervenyes_datum'] = 'Hibás érvényességi dátum.';
      $tomb['ervenyes_datum'] = '946684800';
    }
    
    // kiemeltség
    if($tomb['kiemelt_datum'] == '') $tomb['kiemelt_datum'] = '946684800';
    else {
      $kiemelt_datum = explode('-', $tomb['kiemelt_datum']);
      $kiemelt_datum = mktime(23, 59, 59, $kiemelt_datum[1], $kiemelt_datum[2], $kiemelt_datum[0]);
    }
    if($kiemelt_datum === false) {
      //$hibak['kiemelt_datum'] = 'Hibás kiemeltségi dátum.';
      $tomb['kiemelt_datum'] = '946684800';
    }
    
    // új kicsi kép
    if(!is_numeric($tomb['rekicsi'])) {
      //$hibak['rekicsi'] = 'Érvénytelen kicsi kép azonosító.';
      $tomb['rekicsi'] = false;
    }
    
    if($tomb['rethumb'] == 'true') $tomb['rethumb'] = true;
    
    if($tomb['hiid'] != false && ($tomb['rekicsi'] !== false || $tomb['rethumb'] !== false)) {
      hirdetes_kepek($tomb['hiid'], $tomb['rekicsi'], $tomb['rethumb']);
    }
  }
  
  return $tomb;
}

function kereses_szurese($tomb, &$link, $where = ' WHERE 1') {
// ?regio=0&telepules=0&varosresz=0&faj=2&fajta=0
// &kor=1&nem=1&pedigre=3&ar_tol=3&ar_ig=30
  global $data;
  
  // régió
  if(is_numeric($tomb['regio']) && $data['regio'][$tomb['regio']] != '') {
    $tomb['regio'] = mysql_real_escape_string($tomb['regio']);
    $where .= " AND `regio` = '{$tomb['regio']}'";
    $link .= '&amp;regio='.$tomb['regio'];
  }
  
  // település
  if(is_numeric($tomb['telepules']) && $data['telepules'][$tomb['regio']][$tomb['telepules']] != '') {
    $tomb['telepules'] = mysql_real_escape_string($tomb['telepules']);
    $where .= " AND `telepules` = '{$tomb['telepules']}'";
    $link .= '&amp;telepules='.$tomb['telepules'];
  }
  
  // városrész
  if(is_numeric($tomb['varosresz']) && $data['varosresz'][$tomb['regio']][$tomb['telepules']][$tomb['varosresz']] != '') {
    $tomb['varosresz'] = mysql_real_escape_string($tomb['varosresz']);
    $where .= " AND `varosresz` = '{$tomb['varosresz']}'";
    $link .= '&amp;varosresz='.$tomb['varosresz'];
  }
  
  // faj
  if(is_numeric($tomb['faj']) && $data['faj'][$tomb['faj']] != '') {
    $tomb['faj'] = mysql_real_escape_string($tomb['faj']);
    $where .= " AND `faj` = '{$tomb['faj']}'";
    $link .= '&amp;faj='.$tomb['faj'];
  }
  
  // fajta
  if(is_numeric($tomb['fajta']) && $data['fajta'][$tomb['faj']][$tomb['fajta']] != '') {
    $tomb['fajta'] = mysql_real_escape_string($tomb['fajta']);
    $where .= " AND `fajta` = '{$tomb['fajta']}'";
    $link .= '&amp;fajta='.$tomb['fajta'];
  }
  
  // kor
  if(is_numeric($tomb['kor']) && $data['kor'][$tomb['kor']] != '') {
    $tomb['kor'] = mysql_real_escape_string($tomb['kor']);
    $where .= " AND `kor` = '{$tomb['kor']}'";
    $link .= '&amp;kor='.$tomb['kor'];
  }
  
  // ártól
  if(is_numeric($tomb['ar_tol'])) {
    $tomb['ar_tol'] = mysql_real_escape_string($tomb['ar_tol']);
    $where .= " AND `ar` >= '{$tomb['ar_tol']}'";
    $link .= '&amp;ar_tol='.$tomb['ar_tol'];
  }
  
  // árig
  if(is_numeric($tomb['ar_ig'])) {
    $tomb['ar_ig'] = mysql_real_escape_string($tomb['ar_ig']);
    $where .= " AND `ar` <= '{$tomb['ar_ig']}'";
    $link .= '&amp;ar_ig='.$tomb['ar_ig'];
  }
  
  // nem
  if(is_numeric($tomb['nem']) && $data['nem'][$tomb['nem']] != '') {
    $tomb['nem'] = mysql_real_escape_string($tomb['nem']);
    $where .= " AND `nem` = '{$tomb['nem']}'";
    $link .= '&amp;nem='.$tomb['nem'];
  }
  
  // pedigré
  if(is_numeric($tomb['pedigre']) && $data['pedigre'][$tomb['pedigre']] != '') {
    $tomb['pedigre'] = mysql_real_escape_string($tomb['pedigre']);
    $where .= " AND `pedigre` = '{$tomb['pedigre']}'";
    $link .= '&amp;pedigre='.$tomb['pedigre'];
  }
  
  return $where;
}

function adatlap($hirdetes = array(), $hibak = false, $gomb = 'Hirdetés feladása!') {
  global $data;
  /*
  $hirdetes['szobak'] = number_format(substr($hirdetes['szobak'], 0, 10), 1, ',', '');
  $tmp_array = explode(',', $hirdetes['szobak']);
  if($tmp_array[1] == 0) $hirdetes['szobak'] = $tmp_array[0];
  
  $hirdetes['szintek'] = number_format(substr($hirdetes['szintek'], 0, 10), 1, ',', '');
  $tmp_array = explode(',', $hirdetes['szintek']);
  if($tmp_array[1] == 0) $hirdetes['szintek'] = $tmp_array[0];
  */
  
  $time = time();
?>
          <form method="post" action="">
          <?php
            if($hibak != false && $_POST['kuldes'] != '') {
              echo '<div class="reg_row reg_error_text"><h3>Hibásan kitöltött adatok</h3>';
              foreach($hibak as $hiba) {
                echo $hiba.'<br/>';
              }
              echo '</div>';
            }
          ?>

            <?php
              if(check_admin() && is_numeric($hirdetes['hiid'])) {
                if($hirdetes['datum'] == '') $hirdetes['datum'] = time();
            ?>
                
              <div class="reg_row reg_row_alt">
                  <h3>Adminisztrátori beállítások</h3>
                  
                  <div class="reg_col_left">
                    <div class="reg_col_left_row">Azonosító ID:</div>
                    <div class="reg_col_left_row">Feladás dátuma:</div>
                    <div class="reg_col_left_row">Hirdetés címe:</div>
                    <div class="reg_col_left_row">Státusz:</div>
                    <div class="reg_col_left_row">Üzletkötő:</div>
                    <div class="reg_col_left_row">Érvényesség:</div>
                    <div class="reg_col_left_row">Kiemeltség:</div>
                  </div>
                  
                  <div class="reg_col_right">
                    <div class="reg_col_right_row"><input name="hiid" id="reg_hiid" type="text" maxlength="10" value="<?php echo $hirdetes['hiid']; ?>" readonly="readonly" /></div>
                    
                    <div class="reg_col_right_row"><input name="datum" id="reg_datum" type="text" maxlength="19" value="<?php echo date('Y-m-d H:i:s', $hirdetes['datum']); ?>" readonly="readonly" /></div>
                    
                    <div class="reg_col_right_row"><input name="hirdetes_cime" id="reg_hirdetes_cime" type="text" maxlength="250" value="<?php echo $hirdetes['hirdetes_cime']; ?>" /></div>
                    
                    <div class="reg_col_right_row">
                      <select name="statusz" id="reg_statusz">
                        <!--<option value="">Státusz...</option>-->
                        <?php
                          foreach($data['statusz'] as $key => $val) {
                            if($hirdetes['statusz'] == $key) $key .= '" selected="selected';
                            echo '<option value="'.$key.'">'.$val.'</option>';
                          }
                        ?>
                      </select>
                    </div>
                    
                    <div class="reg_col_right_row"><input name="uzletkoto" id="reg_uzletkoto" type="text" maxlength="250" value="<?php echo $hirdetes['uzletkoto']; ?>" /></div>
                    
                    <div class="reg_col_right_row"><input name="ervenyes_datum" id="reg_ervenyes_datum" type="text" maxlength="10" value="<?php echo date('Y-m-d', $hirdetes['ervenyes_datum']); ?>" /></div>
                    
                    <div class="reg_col_right_row"><input name="kiemelt_datum" id="reg_kiemelt_datum" type="text" maxlength="10" value="<?php echo date('Y-m-d', $hirdetes['kiemelt_datum']); ?>" /></div>
                  </div>
              </div>
                
              <div class="reg_row">
                  <h3>Képek</h3>
                  
                    Kicsi kép:<br/>
                    <?php /*<img id="video_pic-<?php echo $hirdetes['hiid']; ?>" class="admin_row_pic" src="hirdetesek/<?php echo $hirdetes['hiid']; ?>/kicsi.jpg" alt=" "/>*/ ?>
                    <img id="video_pic-<?php echo $hirdetes['hiid']; ?>" class="admin_row_pic" src="<?php echo 'hirdetes-kep.php?hirdetes='.$hirdetes['hiid']; ?>" alt=" "/>
                    <br/><hr/>
                    <center class="admin_thumbnails">
                  <?php
                    $kepek = hirdetes_kepek($hirdetes['hiid']);
                    foreach($kepek as $k => $kep) {
                      echo '<a rel="floatboxKepek" target="_blank" href="hirdetesek/'.$hirdetes['hiid'].'/'.$kep[0].'">'
                          .'<img title="'.$kep[0].'" id="video_pic-'.$hirdetes['hiid'].'-'.$k.'" class="admin_row_pic" src="hirdetesek/'.$hirdetes['hiid'].'/'.$kep[1].'?'.$time.'" alt=" "/>'
                          .'</a><br/>Új kicsi kép: <input type="radio" name="rekicsi" value="'.$k.'"/><br/>';
                    }
                  ?>
                    </center>
                  Kicsi képek újrakészítése: <input type="checkbox" name="rethumb" value="true"/>
              </div>
              <?php
                }
                elseif(check_mod($hirdetes['hiid'])) {
              ?>
              <div class="reg_row">
                  <h3>Adminisztrátori beállítások (csak adminisztrátor szerkesztheti)</h3>
                  
                  <div class="reg_col_left">
                    <div class="reg_col_left_row">Azonosító ID:</div>
                    <div class="reg_col_left_row">Feladás dátuma:</div>
                    <div class="reg_col_left_row">Hirdetés címe:</div>
                    <div class="reg_col_left_row">Státusz:</div>
                    <?php/*<div class="reg_col_left_row">Üzletkötő:</div>*/?>
                    <div class="reg_col_left_row">Érvényesség:</div>
                    <div class="reg_col_left_row">Kiemeltség:</div>
                  </div>
                  
                  <div class="reg_col_right">
                    <div class="reg_col_right_row"><input name="hiid" id="reg_hiid" type="text" maxlength="10" value="<?php echo $hirdetes['hiid']; ?>" readonly="readonly" /></div>
                    
                    <div class="reg_col_right_row"><input name="datum" id="reg_datum" type="text" maxlength="19" value="<?php echo date('Y-m-d H:i:s', $hirdetes['datum']); ?>" readonly="readonly" /></div>
                    
                    <div class="reg_col_right_row"><input name="hirdetes_cime" id="reg_hirdetes_cime" type="text" maxlength="250" value="<?php echo $hirdetes['hirdetes_cime']; ?>" readonly="readonly" /></div>
                    
                    <div class="reg_col_right_row">
                      <select name="statusz" id="reg_statusz" readonly="readonly">
                        <option value="<?php echo $hirdetes['statusz']; ?>"><?php echo $data['statusz'][$hirdetes['statusz']]; ?></option>
                      </select>
                    </div>
                    
                    <?php/*<div class="reg_col_right_row"><input name="uzletkoto" id="reg_uzletkoto" type="text" maxlength="250" value="<?php echo $hirdetes['uzletkoto']; ?>" readonly="readonly" /></div>*/?>
                    
                    <div class="reg_col_right_row"><input name="ervenyes_datum" id="reg_ervenyes_datum" type="text" maxlength="10" value="<?php echo date('Y-m-d', $hirdetes['ervenyes_datum']); ?>" readonly="readonly" /></div>
                    
                    <div class="reg_col_right_row"><input name="kiemelt_datum" id="reg_kiemelt_datum" type="text" maxlength="10" value="<?php echo date('Y-m-d', $hirdetes['kiemelt_datum']); ?>" readonly="readonly" /></div>
                  </div>
              </div>

              <?php } ?>
            <div class="reg_row reg_row_alt">
                <h3>Feladó adatai</h3>
                
                <div class="reg_col_left">
                  <div class="reg_col_left_row">* Név:</div>
                  <div class="reg_col_left_row">* Telefon:</div>
                  <div class="reg_col_left_row">* Email:</div>
                  <div class="reg_col_left_row">* Jelszó:</div>
                </div>
                
                <div class="reg_col_right">
                  <div class="reg_col_right_row"><input name="nev" id="reg_nev" type="text" maxlength="250" value="<?php echo $hirdetes['nev']; ?>" rel="tip:Hirdető neve (kötelező kitölteni)"/></div>
                  <div class="reg_col_right_row"><input name="telefon" id="reg_telefon" type="text" maxlength="250" value="<?php echo $hirdetes['telefon']; ?>" rel="tip:Hirdető telefonszáma a körzetszámmal (kötelező kitölteni)"/></div>
                  <div class="reg_col_right_row"><input name="email" id="reg_email" type="text" maxlength="250" value="<?php echo $hirdetes['email']; ?>" rel="tip:Hirdető valós email címe Jelszóemlékeztető és a kapcsolat miatt szükséges&lt;br/&gt;(kötelező kitölteni; csak adminisztrátor szerkesztheti)" <?php if(check_mod($hirdetes['hiid']) && !check_admin()) { echo 'readonly="readonly"'; } ?>/></div>
                  <div class="reg_col_right_row">
				    <input name="jelszo" id="reg_jelszo" type="text" maxlength="32" value="<?php echo $hirdetes['jelszo']; ?>" rel="tip:Hirdetés jelszava. Ennek segítségével tudja később módosítani hirdetését (kötelező kitölteni)"/>
				    <input type="button" class="rand_button" value="*" onclick="document.getElementById('reg_jelszo').value = rand_str(8);" rel="tip:Véletlen jelszó generálása"/>
				  </div>
                </div>
            </div>

            <div class="reg_row">
                <h3>Hirdetési adatok</h3>
                
                <div class="reg_col_left">
                  <div class="reg_col_left_row">* Régió:</div>
                  <div class="reg_col_left_row">* Település:</div>
                  <div class="reg_col_left_row">* Városrész / kerület:</div>
                  <div class="reg_col_left_row">Faj:</div>
                  <div class="reg_col_left_row">Fajta:</div>
                  <div class="reg_col_left_row">Kor:</div>
                  <div class="reg_col_left_row">* Ár (Ft):</div>
                  <div class="reg_col_left_row">Nem:</div>
                  <div class="reg_col_left_row">Pedigré:</div>
                  <div class="reg_col_left_row">Cím:</div>
                  <div class="reg_col_left_row">Egyéb megjegyzés:</div>
                  <div class="reg_col_left_row"> </div>
                </div>
                
                <div class="reg_col_right">
                  <div class="reg_col_right_row">
                    <select name="regio" id="reg_regio" onchange="regio_valasztas(this);">
                      <option value="-">Régiók...</option>
                      <?php
                        foreach($data['regio'] as $key => $val) {
                          if($hirdetes['regio'] != '' && $hirdetes['regio'] == $key) $key .= '" selected="selected';
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="reg_col_right_row" id="reg_telepules_div">
                    <select name="telepules" id="reg_telepules" onchange="telepules_valasztas(this);"<?php if(!$data['telepules'][$hirdetes['regio']]) echo ' disabled="disabled"'; ?>>
                      <option value="-">Települések...</option>
                      <?php
                        if($hirdetes['regio'] != '' && $hirdetes['telepules'] != '') {
                          foreach($data['telepules'][$hirdetes['regio']] as $key => $val) {
                            if($hirdetes['telepules'] == $key) $key .= '" selected="selected';
                            echo '<option value="'.$key.'">'.$val.'</option>';
                          }
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="reg_col_right_row" id="reg_varosresz_div">
                    <select name="varosresz" id="reg_varosresz"<?php if(!$data['varosresz'][$hirdetes['regio']][$hirdetes['telepules']]) echo ' disabled="disabled"'; ?>>
                      <option value="-">Városrészek...</option>
                      <?php
                        if($hirdetes['regio'] != '' && $hirdetes['telepules'] != '' && $hirdetes['varosresz'] != '') {
                          foreach($data['varosresz'][$hirdetes['regio']][$hirdetes['telepules']] as $key => $val) {
                            if($hirdetes['varosresz'] == $key) $key .= '" selected="selected';
                            echo '<option value="'.$key.'">'.$val.'</option>';
                          }
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="reg_col_right_row">
                    <select name="faj" id="reg_faj" onchange="faj_valasztas(this);">
                      <option value="-">Fajok...</option>
                      <?php
                        foreach($data['faj'] as $key => $val) {
                          if($data['faj'][$hirdetes['faj']] != '' && $hirdetes['faj'] == $key) $key .= '" selected="selected';
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="reg_col_right_row" id="reg_fajta_div">
                    <select name="fajta" id="reg_fajta"<?php if(!$data['fajta'][$hirdetes['faj']]) echo ' disabled="disabled"'; ?>>
                      <option value="-">Fajták...</option>
                      <?php
                        if($hirdetes['faj'] != '' && $hirdetes['fajta'] != '') {
                          foreach($data['fajta'][$hirdetes['faj']] as $key => $val) {
                            if($hirdetes['fajta'] == $key) $key .= '" selected="selected';
                            echo '<option value="'.$key.'">'.$val.'</option>';
                          }
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="reg_col_right_row">
                    <select name="kor" id="reg_kor">
                      <!--<option value="">Kor...</option>-->
                      <?php
                        foreach($data['kor'] as $key => $val) {
                          if($hirdetes['kor'] == $key) $key .= '" selected="selected';
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="reg_col_right_row"><input name="ar" id="reg_ar" type="text" maxlength="10" value="<?php echo $hirdetes['ar']; ?>" rel="tip: (kötelező kitölteni)" onkeyup="numeric_input(this, 0);"/></div>
                  
                  <div class="reg_col_right_row">
                    <select name="nem" id="reg_nem">
                      <!--<option value="">Nem...</option>-->
                      <?php
                        foreach($data['nem'] as $key => $val) {
                          if($hirdetes['nem'] == $key) $key .= '" selected="selected';
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="reg_col_right_row">
                    <select name="pedigre" id="reg_pedigre">
                      <!--<option value="">Pedigré...</option>-->
                      <?php
                        foreach($data['pedigre'] as $key => $val) {
                          if($hirdetes['pedigre'] == $key) $key .= '" selected="selected';
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="reg_col_right_row"><input name="cim" id="reg_cim" type="text" maxlength="3200" value="<?php echo $hirdetes['cim']; ?>" rel="tip:Nem kötelező"/></div>
                  
                  <div class="reg_textarea"><textarea name="megjegyzes" id="reg_megjegyzes" rel="tip:Amit az ingatlannal kapcsolatosan fontosnak tart megjegyezni"><?php echo $hirdetes['megjegyzes']; ?></textarea></div>
                  
                  <div class="reg_col_right_row"><input name="kuldes" id="reg_submit" type="submit" value="<?php echo $gomb; ?>" /></div>
                </div>
            </div>
            
          </form>
<?php
}

if(!function_exists('file_put_contents')) {
  define('FILE_APPEND', 1);

  function file_put_contents($file, $data, $flag = false) {
    $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
    $handle = @fopen($file, $mode);
    $written_bytes = 0;

    if($handle) {
      if(is_array($data)) $data = implode($data);
      
      $written_bytes = fwrite($handle, $data);
      fclose($handle);
      
      return $written_bytes;
    }
    else {
      return false;
    }
  }
}

if(!function_exists('scandir')) {
  function scandir($dir, $sort = 0) {
    $files = array();
    $dh  = opendir($dir);
    while (false !== ($filename = readdir($dh))) {
      $files[] = $filename;
    }

    if($sort == 0) sort($files);
    else rsort($files);
    
    return $files;
  }
}

function rand_file($mappa, $ext, $num = 1, $mappaval = true) {
  $reklamok = scandir($mappa);
  $fajlok = array();
  
  for($i = 0; $i < $num; $i++) {
    foreach($reklamok as $key => $val) {
      if(strtolower(end(explode('.', $val))) != $ext) {
        unset($reklamok[$key]);
      }
    }
    
    if(count($reklamok) > 0) {
      $rand_key = array_rand($reklamok);
      if($mappaval == false) $fajlok[] = $reklamok[$rand_key];
      else $fajlok[] = $mappa.$reklamok[$rand_key];
      
      unset($reklamok[$rand_key]);
    }
    else {
      $fajlok[] = '';
    }
  }
  
  return $fajlok;
}

function hirdetes_feladasa($hirdetes) {
//todo: ha admin akkkor az admin adatokat is vegye fel
  global $data;
  $datum = time();
  $datum2 = '946684800';
  $hirdetes_cime = $data['nem'][$hirdetes['nem']].' '.$data['fajta'][$hirdetes['faj']][$hirdetes['fajta']].' ('.$data['kor'][$hirdetes['kor']].')';
  //if($hirdetes['szobak'] != '') $hirdetes_cime .= ', '.$hirdetes['szobak'].' szoba';
  
  $q = mysql_query("INSERT INTO `hirdetesek` (`hiid`, `hirdetes_cime`, `regio`, `telepules`, `varosresz`, `faj`, `fajta`, `kor`, `ar`, `pedigre`, `nem`, `megjegyzes`, `cim`, `statusz`, `datum`, `ervenyes_datum`, `email`, `jelszo`, `nev`, `telefon`, `kiemelt_datum`, `uzletkoto`) VALUES (NULL, '{$hirdetes_cime}', '{$hirdetes['regio']}', '{$hirdetes['telepules']}', '{$hirdetes['varosresz']}', '{$hirdetes['faj']}', '{$hirdetes['fajta']}', '{$hirdetes['kor']}', '{$hirdetes['ar']}', '{$hirdetes['pedigre']}', '{$hirdetes['nem']}', '{$hirdetes['megjegyzes']}', '{$hirdetes['cim']}', '0', '{$datum}', '{$datum2}', '{$hirdetes['email']}', '{$hirdetes['jelszo']}', '{$hirdetes['nev']}', '{$hirdetes['telefon']}', '{$datum2}', '')");
  
  if($q != false) {
    $q = mysql_insert_id();
    //@mkdir('hirdetesek/'.$q, 0777);
    //mkdir('./hirdetesek/104/', 0777);
    // $q string típuskényszerítés ha kell
    mkdir('./hirdetesek/'.$q.'/', 0777);
    chmod('./hirdetesek/'.$q.'/', 0777);
  }
  
  return $q;
}

function hirdetes_modositasa($hirdetes) {
  if(!is_numeric($hirdetes['hiid'])) return false;
  //die('<pre>'.print_r($hirdetes, true).'</pre>');//debug
  
  $admin_settings = '';
  if(check_admin()) {
    
    $kiemelt_datum = explode('-', $hirdetes['kiemelt_datum']);
    $kiemelt_datum = mktime(23, 59, 59, $kiemelt_datum[1], $kiemelt_datum[2], $kiemelt_datum[0]);
    $ervenyes_datum = explode('-', $hirdetes['ervenyes_datum']);
    $ervenyes_datum = mktime(23, 59, 59, $ervenyes_datum[1], $ervenyes_datum[2], $ervenyes_datum[0]);
	  
    $admin_settings = ", `hirdetes_cime` = '{$hirdetes['hirdetes_cime']}', `statusz` = '{$hirdetes['statusz']}', `ervenyes_datum` = '{$ervenyes_datum}', `kiemelt_datum` = '{$kiemelt_datum}', `uzletkoto` = '{$hirdetes['uzletkoto']}', `email` = '{$hirdetes['email']}'";
  }
  
  $query = "UPDATE `hirdetesek` SET `regio` = '{$hirdetes['regio']}', `telepules` = '{$hirdetes['telepules']}', `varosresz` = '{$hirdetes['varosresz']}', `faj` = '{$hirdetes['faj']}', `fajta` = '{$hirdetes['fajta']}', `kor` = '{$hirdetes['kor']}', `ar` = '{$hirdetes['ar']}', `pedigre` = '{$hirdetes['pedigre']}', `nem` = '{$hirdetes['nem']}', `megjegyzes` = '{$hirdetes['megjegyzes']}', `cim` = '{$hirdetes['cim']}', `jelszo` = '{$hirdetes['jelszo']}', `nev` = '{$hirdetes['nev']}', `telefon` = '{$hirdetes['telefon']}'$admin_settings WHERE `hiid` = '{$hirdetes['hiid']}' LIMIT 1";
  
  $q = mysql_query($query);
  
  //die('<pre>'.print_r(array($hirdetes, $query, $q), true).'</pre>');//debug
  
  return $q;
}


function kategoriak_kiirasa($kiir = true) { // valamifele cach-elést be lehetne iktatni
  $time = time();
  if($kiir == true) {
    global $data;
    
    $n = 0;
    
    echo '<ul>';
    foreach($data['faj'] as $id => $nev) {
      $where = " WHERE `statusz` > '1' AND `ervenyes_datum` > '{$time}' AND `faj` = '{$id}'";
      $hirdetesek_szama = end(mysql_fetch_assoc(mysql_query("SELECT COUNT(`hiid`) as 'osszes' FROM `hirdetesek`{$where}")));
      $n += $hirdetesek_szama;
      
      echo '<li><a href="hirdetesek.php?faj='.$id.'">'.$nev.' ('.$hirdetesek_szama.' db)</a></li>';
    }
    echo '</ul>';
  }
  else {
    $n = end(mysql_fetch_assoc(mysql_query("SELECT COUNT(`hiid`) as 'osszes' FROM `hirdetesek` WHERE `statusz` > '1' AND `ervenyes_datum` > '{$time}'")));
  }
  
  return $n;
}


function check_admin($name = false, $passhash = false) {
//hirdetes admin ellenorzesehez funkcio
  if($name == false || $passhash == false) {
    $name = mysql_real_escape_string($_COOKIE['name']);
    $passhash = mysql_real_escape_string($_COOKIE['passhash']);
  }

  if($name != '' && $passhash != '') {
    global $admins;

    if($admins[strtolower($name)]) {
      if($passhash == ident($name, $admins[strtolower($name)])) {
        return true;
      }
    }
    
    //delCookie();
    return false;
  }
  else {
    return false;
  }
}

function check_mod($hiid, $adatok = false) {
  
  if(is_numeric($_SESSION['admin']['hiid']) && is_numeric($hiid) && $_SESSION['admin']['hiid'] == $hiid) {
    if($adatok == false) return true;
    else {
      return @mysql_fetch_assoc(mysql_query("SELECT * FROM `hirdetesek` WHERE `hiid` = '{$_SESSION['admin']['hiid']}' LIMIT 1"));
    }
  }
  else return false;
}


/**
 * makeThumbnail() Thumbnail készítő funkció.
 *
 * @param (string) $originalImage // eredeti fájl címe
 * @param (string) $thumbnailImage // célfájl címe
 * @param (integer) $toWidth // maximum szélesség
 * @param (integer) $toHeight // maximum magasság
 * @param (integer) $resamplingQ // kép minősége (1 - 100)
 * @param (mixed) $debug // debug infó küldése (false: nemküld, true: hiba esetén küld, 'forced': mindig küld)
 * @return (mixed) // debug infótól függően tömb (Array()), vagy true / false (bool)
 **/
function makeThumbnail($originalImage, $thumbnailImage, $toWidth = 120, $toHeight = 80, $fixWidth = false, $fixHeight = false, $resamplingQ = 100, $debug = false) {
  $error = array();
  // szedjük ki a forrás és a célfájl kiterjesztését
  $o_ext = strtolower(substr(strrchr($originalImage, '.'), 1));
  $t_ext = strtolower(substr(strrchr($thumbnailImage, '.'), 1));

  // állapítsuk meg a forrásfájl méreteit, és az új méretekhez szükséges tömörítés arányát
  list($width, $height) = getimagesize($originalImage);
  $xscale = $width / $toWidth;
  $yscale = $height / $toHeight;

  // állapítsuk meg a tömörítési arányból a legmegfelelőbb új méreteket
  if($yscale > $xscale){
    $new_width = round($width * (1 / $yscale));
    $new_height = round($height * (1 / $yscale));
  }
  else {
    $new_width = round($width * (1 / $xscale));
    $new_height = round($height * (1 / $xscale));
  }

  if(is_numeric($fixWidth)) $new_width = $fixWidth;
  if(is_numeric($fixHeight)) $new_height = $fixHeight;

  // készítsük el az átméretezett képet
  $imageResized = imagecreatetruecolor($new_width, $new_height);

  if($o_ext == 'png') $imageTmp = imagecreatefrompng($originalImage);
  elseif($o_ext == 'gif') $imageTmp = imagecreatefromgif($originalImage);
  else $imageTmp = imagecreatefromjpeg($originalImage);

  $error['imagecopyresampled'] = imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

  // írjuk az új képet a helyére
  if($t_ext == 'png') $error['imagepng/gif/jpeg'] = imagepng($imageResized, $thumbnailImage, round($resamplingQ / 11.111111));
  elseif($t_ext == 'gif') $error['imagepng/gif/jpeg'] = imagegif($imageResized, $thumbnailImage);
  else $error['imagepng/gif/jpeg'] = imagejpeg($imageResized, $thumbnailImage, $resamplingQ);

  // töröljük a képobjektumokat
  $error['imagedestroy($imageTmp)'] = imagedestroy($imageTmp);
  $error['imagedestroy($imageResized)'] = imagedestroy($imageResized);

  // debug infók begyüjtése
  if($debug !== false) {
    $error['data']['$originalImage'] = $originalImage;
    $error['data']['$thumbnailImage'] = $thumbnailImage;
    $error['data']['$toWidth'] = $toWidth;
    $error['data']['$toHeight'] = $toHeight;
    $error['data']['$resamplingQ'] = $resamplingQ;
    $error['data']['$o_ext'] = $o_ext;
    $error['data']['$t_ext'] = $t_ext;
    $error['data']['$width'] = $width;
    $error['data']['$height'] = $height;
    $error['data']['$xscale'] = $xscale;
    $error['data']['$yscale'] = $yscale;
    $error['data']['$new_width'] = $new_width;
    $error['data']['$new_height'] = $new_height;
  }

  // visszaadjuk a folyamat eredményét / debug infókat
  if($error['imagecopyresampled'] === false || $error['imagepng/gif/jpeg'] === false) {//hiba esetén
    $error['success'] = false;
    if($debug !== false) return $error;
    else return false;
  }
  else {//siker esetén
    $error['success'] = true;
    if($debug == 'forced') return $error;
    else return true;
  }
}

function dbg($a, $function = __FUNCTION__, $file = __FILE__, $line = __LINE__) {
  @file_put_contents('dbg.log',
    date('Y.m.d - H:i:s')."\r\n"
   .'['.$function.'() - '.$file.' :: '.$line.']'."\r\n"
   .print_r($a, true).''."\r\n\r\n\r\n",
    FILE_APPEND);
}

function hirdetes_kepek($hiid, $rekicsi = false, $rethumb = false) { 
  //$mappa = 'hirdetesek/'.$hiid.'/';
  $mappa = './hirdetesek/'.$hiid.'/'; // pont elé
  if($rekicsi !== false) $rekicsi *= 1; // ???
  // siman is_numericcel ellenorizni lejebb

  if(is_dir($mappa)) {
    $kepek = array();
    $tartalom = scandir($mappa);

    foreach($tartalom as $elem) {
      //$kit = strtolower(end(explode('.', $elem)));
      // kit meretevel hasznalni a substr-eket
      
      if(is_file($mappa.$elem) && '.jpg' == substr(strtolower($elem), -4) && '_kicsi' != substr(strtolower($elem), -10, 6) && $elem != 'kicsi.jpg') {// sul sok substr mivan ha nem 3 karakter a kit.
        $kicsi = substr($elem, 0, -4).'_kicsi.jpg';
        //$kepek[] = array($elem, $kicsi);
        //$kepek[] = array(utf8_encode($elem), utf8_encode($kicsi));
        $kepek[] = array(rawurlencode($elem), rawurlencode($kicsi)); // valami masik kodoloas kell // hash, b46, valami
        //$kepek[] = array(utf8_decode($elem), utf8_decode($kicsi)); // valami masik kodoloas kell // hash, b46, valami
//die('d2');
        if(!is_file($mappa.$kicsi) || $rethumb) {
          $d = makeThumbnail($mappa.$elem, $mappa.$kicsi, 120, 180, false, false, 100, true);
          //dbg($d, __FUNCTION__, __FILE__, __LINE__);
//die('d3');
		      /*
          list($k_width, $k_height) = getimagesize($mappa.$elem);
          if($k_width > 800 || $k_height > 600) {
            $tmp_file = 'tmp_'.$elem;
            $thumb = makeThumbnail($mappa.$elem, $mappa.$tmp_file, 800, 600, false, false, 96);
            
            if($thumb == true) {
              unlink($mappa.$elem);
              rename($mappa.$tmp_file, $mappa.$elem);
            }
            else {//if(is_file($mappa.$tmp_file))
              @unlink($mappa.$tmp_file);
            }
          }
          */
        }
      }
    }
    
    if((!is_file($mappa.'kicsi.jpg') || $rekicsi !== false) && $kepek[0][1] != '') {
      if($rekicsi === false || $kepek[$rekicsi] == false) $rekicsi = 0; // ???
      
      //if(!copy($mappa.$kepek[$rekicsi][1], $mappa.'kicsi.jpg')) {
        $d = makeThumbnail($mappa.rawurldecode($kepek[$rekicsi][0]), $mappa.'kicsi.jpg', 120, 80, 120, 80, 100, true);
        //dbg($d, __FUNCTION__, __FILE__, __LINE__);
      //}
    }
    
    //dbg($kepek, __FUNCTION__, __FILE__, __LINE__);
    
    return $kepek;
  }
  else return false;
}

?>