<?php
/* MOD_QUICK_INFO_HANDLER.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 20)) { redir('index.php'); }//log

$_QUICK_INFO = getQuickInfo($_POST['id']);

if($_QUICK_INFO !== false && $_GET['id'] == $_POST['id']) {

  $delete_quick_info = ($_POST['delete_quick_info'] == true) ? true : false;
  if($delete_quick_info === true) {
    sql_query("DELETE FROM `rpl_quick_infos` WHERE `quid` = '{$_QUICK_INFO['quid']}'");
    redir('quick_infos.php?error=2');
  }
  else {
    $title = esc(makeTitle($_POST['title']), 1);
    if($title == '') { redir('mod_quick_info.php?id='.$_QUICK_INFO['quid'].'error=1'); }

    $description_title = esc(makeTitle($_POST['description_title']), 1);
    if($description_title == '') { redir('mod_quick_info.php?id='.$_QUICK_INFO['quid'].'error=2'); }

    $before_value = esc(substr($_POST['before_value'], 0, 200), 1);
    $after_value = esc(substr($_POST['after_value'], 0, 200), 1);

    $default = esc(substr($_POST['default'], 0, 200), 1);
    $help = esc(substr($_POST['help'], 0, 200), 1);

    $width = (is_numeric($_POST['width']) && strlen($_POST['width']) < 11) ? esc($_POST['width'], 1) : 0;

    $type = ($_POST['type'] == 1) ? 1 : 0;
    $input_type = ($_POST['input_type'] == 1) ? 1 : 0;

    $query1 = "UPDATE `rpl_quick_infos` SET `title` = '$title', `description_title` = '$description_title', `before_value` = '$before_value', `after_value` = '$after_value', `type` = '$type', `width` = '$width', `default` = '$default', `help` = '$help', `input_type` = '$input_type' WHERE `quid` = '{$_QUICK_INFO['quid']}'";
    sql_query($query1);

    redir('mod_quick_info.php?id='.$_QUICK_INFO['quid']);
  }
}
?>