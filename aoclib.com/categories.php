<?php
/* CATEGORIES.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 30)) { redir('index.php'); }//log

$error = esc($_GET['error'], 1);
$users_errors = array(
  1 => 'No category exists with that id or perma.',
  2 => 'Category deleted.'
);

$content_title = 'Inactive categories';
$content_title .= ' &nbsp; [<a href="'.$_HOST.'add_category.php">New category</a>]';
head(false, $content_title, $_USER);
box(0);

  if($e = $users_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }

  $CATEGORIES = sql_fetch_all(sql_query("SELECT * FROM `rpl_categories` WHERE `status` = '0' ORDER BY `level` ASC"));
  $catList = getCatList();

  echo '<div class="list_title_row">'
      .'<div class="list_title_name va_table"><span class="va_cell">Name</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
      .'<div class="list_col_data">'
      .'<div class="list_title_custom va_table" style="width: 50px"><span class="va_cell">Level</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
      .'<div class="list_title_custom va_table" style="width: 150px"><span class="va_cell">Parent category</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
      .'</div></div>';

  foreach($CATEGORIES as $this_cat) {
    $parent_title = ($this_cat['parent_caid'] == 0) ? 'Main' : esc($catList[$this_cat['parent_caid']]['title'], 2);
    $this_icon = ($this_cat['icon_perma'] != '') ? '<img src="'.$_HOST.'pictures/icons/'.$this_cat['icon_perma'].'" alt=" "/>' : '';
    echo '<div class="list_row">'
        .'<div class="list_col_name va_table"><span class="va_cell">'
        .$this_icon
        .'<a href="'.$_HOST.'mod_category.php?id='.$this_cat['caid'].'">'.esc($this_cat['title'], 2).'</a>'
        .'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
        .'<div class="list_col_data">'
        .'<div class="list_col_custom va_table" style="width: 50px"><span class="va_cell">'.esc($this_cat['level'], 2).'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
        .'<div class="list_col_custom va_table" style="width: 150px"><span class="va_cell">'.$parent_title.'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
        .'</div></div>';
  }

box(1);
foot();
?>