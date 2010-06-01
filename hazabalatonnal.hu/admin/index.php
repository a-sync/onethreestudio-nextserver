<?php
require('../functions.php');
$admin = '..';

$inc = '';
switch($_GET['lap'])
{
  case 'uj': 
    $inc = 'uj.php';
	$_title = 'Új hirdetés felvétele';
  break;
}//default


include '../head.php';
?>

<div id="admin_menu">
<a href="?lap=uj">Új hirdetés</a>
</div>

<?php
if($inc != '') include $inc;

include '../foot.php';

?>