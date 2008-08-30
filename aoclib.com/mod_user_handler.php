<?php
/* MOD_USER_HANDLER.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 30)) { redir('index.php'); }//log

$link = permaHandling();
$_USERDATA = getUserInfo($link[0], $link[1]);

if($_USERDATA !== false) {

  if($_USERDATA['class'] < $_USER['class']) {

    //$username = esc($_POST['username'], 1);
    $username = $_USERDATA['username'];
    $email = esc($_POST['email'], 1);
    $class = (is_numeric($_POST['class'])) ? esc($_POST['class'], 1) : $_USERDATA['class'];
    $password = esc($_POST['password'], 1);

    $delete_user = ($_POST['delete_user'] == true) ? true : false;
    if($delete_user === true) {
      sql_query("DELETE FROM `rpl_users` WHERE `usid` = '{$_USERDATA['usid']}'");
      redir('users.php?error=2');
    }
    else {

    //username check
      //(a-z, A-Z, 0-9, min. 3, max 32 character)
      if(!preg_match('/^[A-Za-z0-9]{3,32}$/', $username)) {
        redir('mod_user.php?id='.$_USERDATA['usid'].'&error=1');
      }
    //username check


    //email check
      //(valid e-mail address)
      if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) {
        redir('mod_user.php?id='.$_USERDATA['usid'].'&error=2');
      }
    //email check


    //class check
      //the given class can't be set by this user
      $maxClass = ($_USER['class'] >= 40) ? $_USER['class'] + 1 : $_USER['class'];
      if($class >= $maxClass) {
        redir('mod_user.php?id='.$_USERDATA['usid'].'&error=3');
      }
    //class check


    //password check
      if($password != '') {
        //the password is 3 or more and 200 or less characters long
        if(strlen($password) < 3 || strlen($password) > 200) {
          redir('mod_user.php?id='.$_USERDATA['usid'].'&error=4');
        }
      /*
        //the password doesn't contains the username or vica versa
        if(preg_match('/'.strtolower($username).'/', strtolower($password)) || preg_match('/'.strtolower($password).'/', strtolower($username))) {
          redir('mod_user.php?id='.$_USERDATA['usid'].'&error=666');
        }

        //the password has a-z character in it
        if(!preg_match('/[a-z]/', $password)) {
          redir('mod_user.php?id='.$_USERDATA['usid'].'&error=666');
        }

        //the password has A-Z character in it
        if(!preg_match('/[A-Z]/', $password)) {
          redir('mod_user.php?id='.$_USERDATA['usid'].'&error=666');
        }

        //the password has 0-9 character in it
        if(!preg_match('/[0-9]/', $password)) {
          redir('mod_user.php?id='.$_USERDATA['usid'].'&error=666');
        }
      */

        $password = ident($username, $password1);
      }
      else { $password = $_USERDATA['password']; }
    //password check


    //duplication check
      $query1 = "SELECT * FROM `rpl_users` WHERE `usid` != {$_USERDATA['usid']} AND (`username` = '$username' OR `email` = '$email')";
      $du = sql_fetch_all(sql_query($query1));//dupe users
      if($du[0]) {
        //if($du[2]) {  }//log
        //two users have the same email or username / both are taken
        if($du[1]) { redir('mod_user.php?id='.$_USERDATA['usid'].'&error=5'); }
        //the username is taken
        if($du[0]['username'] == $username) { redir('mod_user.php?id='.$_USERDATA['usid'].'&error=6'); }
        //the email is taken
        if($du[0]['email'] == $email) { redir('mod_user.php?id='.$_USERDATA['usid'].'&error=7'); }
      }
    //duplication check


      $query2 = "UPDATE `rpl_users` SET `username` = '$username', `password` = '$password', `email` = '$email', `class` = '$class' WHERE `usid` = '{$_USERDATA['usid']}'";
      sql_query($query2, true);

      redir('mod_user.php?id='.$_USERDATA['usid']);
    }
  }
  else { redir('mod_user.php?id='.$_USERDATA['usid'].'&error=8'); }
  //log
}
else { redir('users.php?error=1'); }
?>