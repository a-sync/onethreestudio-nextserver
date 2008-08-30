<?php
/* MOD_ICON_HANDLER.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 20)) { redir('index.php'); }//log

$_PICTURE = getPicInfo($_POST['id']);

if($_PICTURE !== false && $_GET['id'] == $_POST['id']) {

  if($_POST['delete_icon'] == true) {
    if(unlink('pictures/icons/'.$_PICTURE['perma'])) {
      $query2 = "DELETE FROM `rpl_pictures` WHERE `piid` = '{$_PICTURE['piid']}'";
      sql_query($query2);

      $query5 = "UPDATE `rpl_categories` SET `icon_perma` = '' WHERE `icon_perma` = '{$_PICTURE['perma']}'";
      sql_query($query5);

      $query6 = "UPDATE `rpl_articles` SET `icon_perma` = '' WHERE `icon_perma` = '{$_PICTURE['perma']}'";
      sql_query($query6);

      redir('icons.php?error=2');
    }
    else { redir('mod_icon.php?id='.$_PICTURE['piid'].'&error=1'); }
  }
  else {
    $title = esc(makeTitle($_POST['title']), 1);
    if($title == '') { redir('mod_icon.php?id='.$_PICTURE['piid'].'&error=1'); }

    $perma = makePermalink($_POST['perma']);
    if($perma == '') { $perma = makePermalink($title); }

    $old_perma_ext = strtolower(substr(strrchr($_PICTURE['perma'], '.'), 0));
    $fname = $perma.$old_perma_ext;

    if(strtolower($fname) != strtolower($_PICTURE['perma'])) {
      if(file_usable('pictures/icons/'.$fname, false, false, false)) {
        $taken = 1;
        while($taken != 0) {
          $fname = $perma.'-'.$taken.$old_perma_ext;
          if(file_usable('pictures/icons/'.$fname, false, false, false)) { $taken++; }
          else { $taken = 0; }
        }
      }

      if(!rename('pictures/icons/'.$_PICTURE['perma'], 'pictures/icons/'.$fname)) {
        redir('mod_icon.php?id='.$_PICTURE['piid'].'&error=2');
      }

      $query7 = "UPDATE `rpl_categories` SET `icon_perma` = '$fname' WHERE `icon_perma` = '{$_PICTURE['perma']}'";
      sql_query($query7);

      $query8 = "UPDATE `rpl_articles` SET `icon_perma` = '$fname' WHERE `icon_perma` = '{$_PICTURE['perma']}'";
      sql_query($query8);
    }
    else { $fname = $_PICTURE['perma']; }

    $query1 = "UPDATE `rpl_pictures` SET `title` = '$title', `perma` = '$fname' WHERE `piid` = '{$_PICTURE['piid']}'";
    sql_query($query1);

    redir('mod_icon.php?id='.$_PICTURE['piid']);
  }
}
else { redir('index.php'); }//log

?>