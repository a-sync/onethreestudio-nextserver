<?php
/* ICONS.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 20)) { redir('index.php'); }//log

$error = esc($_GET['error'], 1);
$icons_errors = array(
  1 => 'No icon exists with that id or perma.',
  2 => 'Icon deleted.'
);

$content_title = 'Icons';
$content_title .= ' &nbsp; [<a href="'.$_HOST.'add_icon.php">New icon</a>]';
head(false, $content_title, $_USER);
box(0);

  if($e = $icons_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }

  $ICONS = sql_fetch_all(sql_query("SELECT * FROM `rpl_pictures` WHERE `type` = '0' ORDER BY `piid` DESC"));

  echo '<div class="list_title_row">'
      .'<div class="list_title_name va_table"><span class="va_cell">Icon name</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
      .'<div class="list_col_data">'
      .'</div></div>';

  foreach($ICONS as $this_icon) {
    echo '<div class="list_row">'
        .'<div class="list_col_name va_table"><span class="va_cell">'
        .'<img src="'.$_HOST.'pictures/icons/'.$this_icon['perma'].'" alt=" "/>'
        .'<a href="'.$_HOST.'mod_icon.php?id='.$this_icon['piid'].'">'.esc($this_icon['title'], 2).'</a>'
        .'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
        .'<div class="list_col_data">'
        .'</div></div>';
  }

box(1);
foot();
?>