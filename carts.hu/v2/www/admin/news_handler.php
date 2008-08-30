<?php
if(!defined('_OTS_ADMIN_')) { header("Location: ../admin.php"); exit; } // ha nincs bejelentkezve (nem admin)

if($_POST['add_news']) {//hír hozzáadása

  $title = trim(mysql_real_escape_string($_POST['title']));
  $text = mysql_real_escape_string($_POST['text']);
  $status = mysql_real_escape_string($_POST['status']);
  $priority = mysql_real_escape_string($_POST['priority']);
  $time = time();

  if($title != '') {
    mysql_query("INSERT INTO `hirek` (`hir_id`, `title`, `text`, `status`, `priority`, `time`) VALUES (NULL, '$title', '$text', '$status', '$priority', '$time')");
  }
  else {
    $error .= ' Nincs megadva cím!';
  }

}
elseif($_POST['news_mod']) {//hír módosítása
  $hir_id = mysql_real_escape_string($_POST['hir_id']);
  $title = trim(mysql_real_escape_string($_POST['title']));
  $text = mysql_real_escape_string($_POST['text']);
  $status = mysql_real_escape_string($_POST['status']);
  $priority = mysql_real_escape_string($_POST['priority']);

  if($title != '') {
    mysql_query("UPDATE `hirek` SET `title` = '$title', `text` = '$text', `status` = '$status', `priority` = '$priority' WHERE `hir_id` = '$hir_id'");
  }
  else {
    $error .= ' Nincs megadva cím!';
  }
}
elseif($_POST['news_del']) {//hír törlése
  $hir_id = mysql_real_escape_string($_POST['hir_id']);
  
  mysql_query("DELETE FROM `hirek` WHERE `hir_id` = '$hir_id'");
}

if(is_numeric($_GET['news'])) {
  $hir_id = mysql_real_escape_string($_GET['news']);
  $result = mysql_query("SELECT * FROM `hirek` WHERE `hir_id` = '$hir_id'");
}

?>