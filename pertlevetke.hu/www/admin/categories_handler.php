<?php
if(!defined('_OTS_ADMIN_')) { header("Location: ../admin.php"); exit; } // ha nincs bejelentkezve (nem admin)

if($_POST['add_cat']) {//kategória hozzáadása
  $name = trim(mysql_real_escape_string($_POST['name']));

  if($name != '') {
    mysql_query("INSERT INTO `galeria` (`cat_id`, `name`) VALUES (NULL, '$name')");
  }
  else {
    $error .= 'Nincs megadva ketegórianév.';
  }

}
elseif($_POST['cat_mod']) {//kategória módosítása
  $name = trim(mysql_real_escape_string($_POST['name']));
  $cat_id = mysql_real_escape_string($_POST['cat_id']);
  
  if($name != '') { mysql_query("UPDATE `galeria` SET `name` = '$name' WHERE `cat_id` = '$cat_id'"); }
  else { $error .= 'Nincs megadva ketegórianév.'; }

}
elseif($_POST['cat_del']) {//kategória törlése
  $cat_id = mysql_real_escape_string($_POST['cat_id']);
  
  mysql_query("DELETE FROM `galeria` WHERE `cat_id` = '$cat_id'");
}

if(is_numeric($_GET['cat'])) {
  $cat_id = mysql_real_escape_string($_GET['cat']);
  $result = mysql_query("SELECT * FROM `galeria` WHERE `cat_id` = '$cat_id'");
}
?>