<?php
/* MOD_RELATIONS_HANDLER.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 30)) { redir('index.php'); }//log

$_CATEGORY = getCatInfo($_POST['id'], false, false);

if($_CATEGORY !== false && $_GET['id'] == $_POST['id']) {
  if($_CATEGORY['content'] != 1) { redir('mod_category.php?id='.$_CATEGORY['caid']); }

  $catList = getCatlist();
  $QUICK_INFOS = sql_fetch_all(sql_query("SELECT * FROM `rpl_quick_infos` ORDER BY `quid` DESC"), 'quid', true);
  $RELATIONS = sql_fetch_all(sql_query("SELECT * FROM `rpl_relations` WHERE `caid` = '{$_CATEGORY['caid']}'"), 'related_caid', true);


  //quick infos
  $copy_qi = (is_numeric($_POST['copy_qi'])) ? esc($_POST['copy_qi'], 1) : false;
  if(!$copy_qi) {
    foreach($_POST['quick_infos'] as $qi_quid) {
      if(is_numeric($qi_quid)) { $quick_infos_a[] = esc($qi_quid, 1); }
    }

    $new_quick_infos = ($quick_infos_a[0] != '') ? '|'.implode('|', $quick_infos_a).'|' : '';
  }
  else {//copy
    if($catList[$copy_qi] != false && $catList[$copy_qi]['content'] == 1 && $catList[$copy_qi]['caid'] != $_CATEGORY['caid']) {
      if($catList[$copy_qi]['content'] == 0) {  }
      $new_quick_infos = $catList[$copy_qi]['quick_infos'];
    }
    else { redir('mod_relations.php?id='.$_CATEGORY['caid'].'&error=1'); }
  }

  if($new_quick_infos != $_CATEGORY['quick_infos']) {
    $query_array[] = "UPDATE `rpl_categories` SET `quick_infos` = '$new_quick_infos' WHERE `caid` = '{$_CATEGORY['caid']}'";
  }


  //relations
  $copy_rel = (is_numeric($_POST['copy_rel'])) ? esc($_POST['copy_rel'], 1) : false;
  if(!$copy_rel) {
    foreach($catList as $cat) {
      if($cat['content'] == 1) {
        //relation title and status
        $related_title = esc(makeTitle($_POST['related_title-'.$cat['caid']], 1));
        $related_status = ($_POST['related_status-'.$cat['caid']] == true && $related_title != '') ? 1 : 0;

        //relation quick infos
        $cat_qi_quids = explode('|', substr($cat['quick_infos'], 1, -1));

        $related_quick_infos = '';
        foreach($_POST['related_quick_infos-'.$cat['caid']] as $rel_qi_quid) {
          if(in_array($rel_qi_quid, $cat_qi_quids) && $QUICK_INFOS[$rel_qi_quid]['width'] != 0) {
            $related_quick_infos[] = $rel_qi_quid;
          }
        }
        $related_quick_infos = ($related_quick_infos[0] != '') ? '|'.implode('|', $related_quick_infos).'|' : '';

        //relation editing / adding query
        if($RELATIONS[$cat['caid']]) {//ha mar letezik
          if($RELATIONS[$cat['caid']]['quick_infos'] != $related_quick_infos || $RELATIONS[$cat['caid']]['title'] != $related_title || $RELATIONS[$cat['caid']]['status'] != $related_status) {
            $query_array[] = "UPDATE `rpl_relations` SET `title` = '$related_title', `status` = '$related_status', `quick_infos` = '$related_quick_infos' WHERE `caid` = '{$_CATEGORY['caid']}' AND `related_caid` = '{$cat['caid']}'";
          }
        }
        else {//ha új
          if($related_quick_infos != '' || $related_title != '') {
            $query_array[] = "INSERT INTO `rpl_relations` (`caid`, `related_caid`, `title`, `status`, `quick_infos`) VALUES ('{$_CATEGORY['caid']}', '{$cat['caid']}', '$related_title', '$related_status', '$related_quick_infos')";
          }
        }
      }
    }
  }
  else {//copy
    if($catList[$copy_rel] != false && $catList[$copy_rel]['content'] == 1 && $catList[$copy_rel]['caid'] != $_CATEGORY['caid']) {
      //only active / with quick infos
      $copy_rel_active = ($_POST['copy_rel_active'] == true) ? true : false;
      $copy_rel_qi = ($_POST['copy_rel_qi'] == true) ? true : false;

      //getting new relations
      $only_active = ($copy_rel_active === true) ? " AND `status` = '1'" : "";
      $NEW_RELATIONS = sql_fetch_all(sql_query("SELECT * FROM `rpl_relations` WHERE `caid` = '{$catList[$copy_rel]['caid']}'".$only_active), 'related_caid', true);

      //setting old relations status to 0
      $query_array[] = "UPDATE `rpl_relations` SET `status` = '0' WHERE `caid` = '{$_CATEGORY['caid']}'";

      //setting new relations
      $_NEW_QUICK_INFOS = '';
      foreach($NEW_RELATIONS as $_NEW_RELATION) {
        if($copy_rel_qi === true) { $_NEW_QUICK_INFOS = $_NEW_RELATION['quick_infos']; }

        if($RELATIONS[$_NEW_RELATION['related_caid']]) {
          $query_array[] = "UPDATE `rpl_relations` SET `title` = '{$_NEW_RELATION['title']}', `status` = '{$_NEW_RELATION['status']}', `quick_infos` = '$_NEW_QUICK_INFOS' WHERE `caid` = '{$_CATEGORY['caid']}' AND `related_caid` = '{$_NEW_RELATION['related_caid']}'";
        }
        else {
          $query_array[] = "INSERT INTO `rpl_relations` (`caid`, `related_caid`, `title`, `status`, `quick_infos`) VALUES ('{$_CATEGORY['caid']}', '{$_NEW_RELATION['related_caid']}', '{$_NEW_RELATION['title']}', '{$_NEW_RELATION['status']}', '$_NEW_QUICK_INFOS')";
        }
      }
    }
    else { redir('mod_relations.php?id='.$_CATEGORY['caid'].'&error=2'); } 
  }


  //dbg($query_array);//debug

  //run querys
  foreach($query_array as $queryX) { sql_query($queryX); }

  redir('mod_relations.php?id='.$_CATEGORY['caid']);
}
else { redir('categories.php?error=1'); }//log
?>