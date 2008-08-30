<?php
/* QUICK_INFOS.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 20)) { redir('index.php'); }//log

$error = esc($_GET['error'], 1);
$quick_infos_errors = array(
  1 => 'No Quick info exists with that id or perma.',
  2 => 'Quick info deleted.'
);

$content_title = 'Quick infos';
$content_title .= ' &nbsp; [<a href="'.$_HOST.'add_quick_info.php">New quick info</a>]';
head(false, $content_title, $_USER);
box(0);

  if($e = $quick_infos_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }

  $QUICK_INFOS = sql_fetch_all(sql_query("SELECT * FROM `rpl_quick_infos` ORDER BY `quid` DESC"));

  echo '<div class="list_title_row">'
      .'<div class="list_title_name va_table"><span class="va_cell">Quick info name</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
      .'<div class="list_col_data">'
      .'</div></div>';

  foreach($QUICK_INFOS as $this_quick_info) {
    echo '<div class="list_row">'
        .'<div class="list_col_name va_table"><span class="va_cell">'
        .'<a href="'.$_HOST.'mod_quick_info.php?id='.$this_quick_info['quid'].'">'.esc($this_quick_info['title'], 2).'</a>'
        .'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
        .'<div class="list_col_data">'
        .'</div></div>';
  }

box(1);
foot();
?>