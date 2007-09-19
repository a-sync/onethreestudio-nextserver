<?
require('functions.php');

if(checkLogin() == 'logged_in') {
  head('public');
  redir('index.php');
}
else {
  if($_POST['action'] == 'login' && $_POST['username'] != '' && $_POST['userpass'] != '') {

    $ident = checkIdent($_POST['username'], $_POST['userpass']);

    if($ident == 'logged_in') {
      addCookie($_POST['username'], $_POST['userpass']);
      redir('index.php');
    }
    else {
      redir('login.php?error='.$ident);
    }

  }
  else {
    head('public');
?>

      <table align="center">
        <form action="login.php" method="post">
        <tr><td><font color="<?php if($_GET['error']=='bad_name'){echo'red';}else{echo'black';} ?>">Felhasználónév:</font></td><td><input type="text" name="username" maxlength="32"></td></tr>
        <tr><td><font color="<?php if($_GET['error']=='bad_pass'){echo'red';}else{echo'black';} ?>">Jelszó:</font></td><td><input type="password" name="userpass" maxlength="32"></td></tr>
        <input type="hidden" name="action" value="login">
        <tr><td colspan="2" align="center"><input type="submit" name="send" value="Mehet"></td></tr>
        </form>
      </table>

<?php
  }
}

/*  //logged_in -be
    if($_GET['generate']) {
      if($_POST['thename'] != '' && $_POST['thepass'] != '') {
        $pass = ident($_POST['username'], $_POST['userpass']);
        echo '
          $userName[] = \''.$_POST['username'].'\';
          $userPass[] = \''.$pass.'\';
        ';
      }
      else {
        echo'
          <html><body>
          <table align="center">
            <form method="post" action="login.php?generate=yes">
            <tr><td>Választott felhasználónév:</font></td><td><input type="text" name="thename" maxlength="32"></td></tr>
            <tr><td>Választott jelszó:</font></td><td><input type="text" name="thepass" maxlength="32"></td></tr>
            <input type="hidden" name="action" value="generate">
            <tr><td colspan="2" align="center"><input type="submit" name="send" value="Mehet"></td></tr>
            </form>
          </table>
          </body></html>
        ';
    }
    else {
      redir('index.php');
    }
*/

foot();
?>