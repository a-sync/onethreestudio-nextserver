<?php
/* ADD_QUICK_INFO_HANDLER.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 20)) { redir('index.php'); }//log

  $title = esc(makeTitle($_POST['title']), 1);
  if($title == '') { redir('add_quick_info.php?error=1'); }

  $description_title = esc(makeTitle($_POST['description_title']), 1);
  if($description_title == '') { redir('add_quick_info.php?error=2'); }

  $before_value = esc(substr($_POST['before_value'], 0, 200), 1);
  $after_value = esc(substr($_POST['after_value'], 0, 200), 1);

  $default = esc(substr($_POST['default'], 0, 200), 1);
  $help = esc(substr($_POST['help'], 0, 200), 1);

  $width = (is_numeric($_POST['width']) && strlen($_POST['width']) < 11) ? esc($_POST['width'], 1) : 0;

  $type = ($_POST['type'] == 1) ? 1 : 0;
  $input_type = ($_POST['$input_type'] == 1) ? 1 : 0;


  $query0 = "INSERT INTO `rpl_quick_infos` (`quid`, `title`, `description_title`, `before_value`, `after_value`, `type`, `width`, `default`, `help`, `input_type`) VALUES (NULL, '$title', '$description_title', '$before_value', '$after_value', '$type', '$width', '$default', '$help', '$input_type')";
  $this_quid = sql_query($query0, true);

  if($this_quid === false) { redir('add_quick_info.php?error=3'); }
  else { redir('mod_quick_info.php?id='.$this_quid); }
?>