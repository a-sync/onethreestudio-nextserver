<?php
/* USERS.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 30)) { redir('index.php'); }//log

$error = esc($_GET['error'], 1);
$users_errors = array(
  1 => 'No user exists with that id or perma.',
  2 => 'User deleted.'
);

$content_title = 'Users';
head(false, $content_title, $_USER);
box(0);

  if($e = $users_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }

  $USERS = sql_fetch_all(sql_query("SELECT * FROM `rpl_users` ORDER BY `usid` DESC"));

  echo '<div class="list_title_row">'
      .'<div class="list_title_name va_table"><span class="va_cell">Username</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
      .'<div class="list_col_data">'
      .'<div class="list_title_custom va_table" style="width: 200px"><span class="va_cell">E-Mail</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
      .'<div class="list_title_custom va_table" style="width: 50px"><span class="va_cell">Class</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
      .'</div></div>';

  foreach($USERS as $this_user) {
    echo '<div class="list_row">'
        .'<div class="list_col_name va_table"><span class="va_cell">'
        .'<a href="'.$_HOST.'mod_user.php?id='.$this_user['usid'].'">'.esc($this_user['username'], 2).'</a>'
        .'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
        .'<div class="list_col_data">'
        .'<div class="list_col_custom va_table" style="width: 200px"><span class="va_cell">'.esc($this_user['email'], 2).'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
        .'<div class="list_col_custom va_table" style="width: 50px"><span class="va_cell">'.esc($this_user['class'], 2).'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
        .'</div></div>';
  }

box(1);
foot();
?>