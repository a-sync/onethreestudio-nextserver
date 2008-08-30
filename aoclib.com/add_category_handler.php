<?php
/* ADD_CATEGORY_HANDLER.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 30)) { redir('index.php'); }//log

  $parent_caid = (is_numeric($_POST['parent_caid'])) ? esc($_POST['parent_caid'], 1) : 0;

  $_ICON = getPicInfo($_POST['icon']);
  if($_ICON !== false && $_ICON['type'] == 0) { $icon_perma = $_ICON['perma']; }
  else { $icon_perma = ''; }

  $title = esc(makeTitle($_POST['title']), 1);
  if($title == '') { redir('add_category.php?error=1'); }

  $perma = esc(makePermalink($_POST['perma']), 1);
  if($perma == '') { $perma = makePermalink($title); }

  $content = ($_POST['content'] == 1) ? 1 : 0;
  $status = ($_POST['status'] == 1) ? 1 : 0;

  $perma_likes = sql_fetch_all(sql_query("SELECT `perma` FROM `rpl_categories` WHERE `status` = '1' AND `perma` LIKE '$perma%'"), 'perma', true);//status ellenorzes szukseges?
  if(isset($perma_likes[$perma])) {
    $taken = 1;
    while($taken != 0) {
      $new_perma = $perma.'-'.$taken;
      if(isset($perma_likes[$new_perma])) { $taken++; }
      else { $taken = 0; }
    }
    $perma = $new_perma;
  }

  if($parent_caid != 0) {
    $_CATEGORY = getCatInfo($parent_caid);
    if($_CATEGORY['content'] != 0) { redir('add_category.php?error=2'); }//log
  }
  else { $parent_caid = 0; }

  $level = ($parent_caid == 0) ? 0 : ($_CATEGORY['level'] + 1);


  $query0 = "INSERT INTO `rpl_categories` (`caid`, `perma`, `title`, `content`, `parent_caid`, `level`, `status`, `icon_perma`) VALUES (NULL, '$perma', '$title', '$content', '$parent_caid', '$level', '$status', '$icon_perma')";
  $this_caid = sql_query($query0, true);

  if($this_caid === false) { redir('add_category.php?error=3'); }
  else {
    //new cache, new menu
    cacheCatList();
    generate_jsmenu();

    redir('mod_category.php?id='.$this_caid);
  }
?>