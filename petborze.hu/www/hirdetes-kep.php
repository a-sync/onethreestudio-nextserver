<?php
include 'functions.php';

header("Content-Type: image/jpeg");
header("Expires: Mon, 22 Aug 1987 06:06:06 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$hiid = strip_tags($_GET['hirdetes']);
$kep = './hirdetesek/'.$hiid.'/kicsi.jpg';

if(is_numeric($hiid) && is_file($kep)) {
  @readfile($kep); // hirdetesek / hiid / kicsi.jpg
}

exit;
?>
