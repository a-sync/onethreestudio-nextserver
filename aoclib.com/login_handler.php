<?php
/* LOGIN_HANDLER.PHP */

include('functions.php');

timeout(1);//biztonsági okokból

if(checkLogin() !== false) { redir('index.php'); }//log

if($_POST['username'] != '' && $_POST['password'] != '') {
  delCookie();

  $username = esc($_POST['username'], 1);
  $password = ident($username, $_POST['password']);

  $query0 = "SELECT `usid` FROM `rpl_users` WHERE `username` = '$username' AND `password` = '$password'";
  $result0 = sql_query($query0);
  $resultnum0 = mysql_num_rows($result0);

  if($resultnum0 < 1) { redir('login.php?error=2'); }

  $_USERDATA = mysql_fetch_array($result0, MYSQL_ASSOC);

  addCookie($_USERDATA['usid'], $password);
  redir('index.php');
}
else { redir('login.php?error=1'); }
?>