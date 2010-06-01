<?php
if(!defined('_HAB_'))die('*');


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo htmlspecialchars($_title); ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $admin; ?>/media/alap.css">
</head>

<body>

  <div id="kontener">
    <div id="fejlec">
      <div id="fejlec_belso">
        <h1><a id="logo" href="http://hazabalatonnal.hu/">HazaBalatonnal.hu</a></h1>
      </div>
    </div>
    
    <div id="test">
      
      <div id="menu">
        <h2>Kategóriák</h2>
        <ul>
<?php

foreach($kategoriak as $i => $n)
{
  echo '<li><a href="?kategoria='.$i.'">'.htmlspecialchars($n).'</a></li>';
}

?>
        </ul>
      </div>
      
      <div id="tartalom">
      <!--tartalom-->