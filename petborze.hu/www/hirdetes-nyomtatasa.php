<?php
include 'functions.php';

$hiid = mysql_real_escape_string($_GET['hirdetes']);
if(is_numeric($hiid)) {
    $time = time();
    $ervenyes = (check_mod($hiid) || check_admin()) ? '' : " AND `statusz` > '1' AND `ervenyes_datum` > '{$time}'";
    $hirdetes = mysql_query("SELECT * FROM `hirdetesek` WHERE `hiid` = '{$hiid}'{$ervenyes}");
    $hirdetes = mysql_fetch_assoc($hirdetes);
    
    if($hirdetes['hiid'] == '') echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body onload="init();"><h2>A keresett hirdetés nem elérhető, vagy nem létezik.</h2></body></html>';
    else {
      add_stat($hirdetes['hiid']);
    
      foreach($hirdetes as $i => $val) {
        $hirdetes[$i] = htmlspecialchars($val);
      }
      
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Hirdesse lakását biztonságosan és jutalékmentesen.</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <script type="text/javascript" src="default_main.js"></script>
  <link href="default_images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <link href="default_images/favicon.ico" rel="icon" type="image/x-icon" />
</head><body onload="init();">

<h2>http://hazvideo.hu/hirdetesek.php?hirdetes=<?php echo $hirdetes['hiid']; ?></h2>

<h3><?php echo $hirdetes['hirdetes_cime']{0}.strtolower(substr($hirdetes['hirdetes_cime'], 1)); ?></h3>

<?php /*<img src="hirdetesek/<?php echo $hirdetes['hiid']; ?>/kicsi.jpg" alt=" "/>*/ ?>
<img src="<?php echo 'hirdetes-kep.php?hirdetes='.$hirdetes['hiid']; ?>" alt=" "/>
<br/><br/>

Név: <?php echo $hirdetes['nev']; ?>
<br/>

<b>Telefon: <?php echo $hirdetes['telefon']; ?></b>
<br/>

<b>Email: <?php echo $hirdetes['email']; ?></b>
<br/><br/>

Feladás dátuma: <?php echo date('Y.m.d', $hirdetes['datum']); ?>
<br/>

<b>Hely: <?php echo $data['regio'][$hirdetes['regio']]; ?> - <?php echo $data['telepules'][$hirdetes['regio']][$hirdetes['telepules']]; ?> / <?php echo $data['varosresz'][$hirdetes['regio']][$hirdetes['telepules']][$hirdetes['varosresz']]; ?></b>
<br/>

<?php
if($hirdetes['cim'] != '') {
  echo '<b>'.$hirdetes['cim'].'</b>';
}
?>

<b>Faj: <?php echo $data['faj'][$hirdetes['faj']]; ?></b>
<br/>

<b>Fajta: <?php echo $data['fajta'][$hirdetes['faj']][$hirdetes['fajta']]; ?></b>
<br/>

<b>Ár: <?php echo number_format($hirdetes['ar'], 0, '', ' '); ?> Ft</b>
<br/><br/>

Kor: <?php echo $data['kor'][$hirdetes['kor']]; ?>
<br/>

Nem: <?php echo $data['nem'][$hirdetes['nem']]; ?>
<br/>

Pedigré: <?php echo $data['pedigre'][$hirdetes['pedigre']]; ?>
<br/>

Megjegyzés:<br/>
<?php echo nl2br($hirdetes['megjegyzes']); ?>

</body>
</html>

<?php
    }
}
?>