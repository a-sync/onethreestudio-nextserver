<?php
if(!defined('_OTS_ADMIN_')) { header("Location: ../admin.php"); exit; } // ha nincs bejelentkezve (nem admin)

  if($_GET['a'] == 'logout') {
    delCookie();
    header("Location: admin.php");
    exit;
  }

  if($_POST['login']) { // ha elküldte a bejelentkező formot
    $passhash = ident($_POST['name'], $_POST['pass']);
    
    if(checkLogin($_POST['name'], $passhash)) {
      addCookie($_POST['name'], $passhash);
      header("Location: admin.php");
    }
    else {
      $error .= 'Hibás felhasználónév vagy jelszó!';
    }
  }
?>