<?php
/* MOD_CATEGORY_HANDLER.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 30)) { redir('index.php'); }//log

$_CATEGORY = getCatInfo($_POST['id'], false, false);

//$_SUBCATS teljes kipörgetése helyett parent_caid alapján (csoportosítsuk, majd) azonosítsuk az alkategóriákat
//OR helyett IN(x, y, ... n)
//a státusz változás, tartalom mozgatás, és törlés sql parancs elõállítását átnézni, hol jön/jöhet létre felesleges parancs
if($_CATEGORY !== false && $_GET['id'] == $_POST['id']) {

  //delete
  $delete_category = ($_POST['delete_category'] == true) ? true : false;
  if($delete_category === true) {
    $query1 = "DELETE FROM `rpl_categories`";
    $where1 = " WHERE `caid` = '{$_CATEGORY['caid']}'";

    $query2 = "DELETE FROM `rpl_articles`";
    $where2 = " WHERE `caid` = '{$_CATEGORY['caid']}'";

    $query3 = "DELETE FROM `rpl_relations`";
    $where3 = " WHERE `caid` = '{$_CATEGORY['caid']}' OR `related_caid` = '{$_CATEGORY['caid']}'";

    if($_CATEGORY['content'] == 0) {
      $_SUBCATS = getSubCats($_CATEGORY);

      foreach($_SUBCATS as $cat) {
        if($cat['content'] == 1) {
          $where3 .= " OR `caid` = '{$cat['caid']}' OR `related_caid` = '{$cat['caid']}'";
          $where2 .= " OR `caid` = '{$cat['caid']}'";
        }
        $where1 .= " OR `caid` = '{$cat['caid']}'";
      }
    }

    $query_array[] = $query3.$where3;
    $query_array[] = $query2.$where2;
    $query_array[] = $query1.$where1;

    //dbg($query_array);//debug

    //run querys
    foreach($query_array as $queryX) { sql_query($queryX); }

    //new cache, new menu
    cacheCatList();
    generate_jsmenu();

    redir('categories.php?error=2');
  }
  else {
    //icon
    $_ICON = getPicInfo($_POST['icon']);
    if($_ICON !== false && $_ICON['type'] == 0) { $icon_perma = $_ICON['perma']; }
    else { $icon_perma = ''; }

    //title
    $title = esc(makeTitle($_POST['title']), 1);
    if($title == '') { redir('mod_category.php?id='.$_CATEGORY['caid'].'&error=1'); }

    //perma
    $perma = esc(makePermalink($_POST['perma']), 1);
    if($perma == '') { $perma = makePermalink($title); }

    $perma_likes = sql_fetch_all(sql_query("SELECT `caid`, `perma` FROM `rpl_categories` WHERE `status` = '1' AND `perma` LIKE '$perma%'"), 'perma', true);//status ellenorzes kell?
    if(isset($perma_likes[$perma]) && $perma_likes[$perma]['caid'] != $_CATEGORY['caid']) {
      $taken = 1;
      while($taken != 0) {
        $new_perma = $perma.'-'.$taken;
        if(isset($perma_likes[$new_perma]) && $perma_likes[$perma]['caid'] != $_CATEGORY['caid']) { $taken++; }
        else { $taken = 0; }
      }
      $perma = $new_perma;
    }

    //cat quick infos
    foreach($_POST['cat_quick_infos'] as $qi_quid) {
      if(is_numeric($qi_quid)) { $cat_quick_infos_a[] = esc($qi_quid, 1); }
    }

    $cat_quick_infos = ($cat_quick_infos_a[0] != '') ? '|'.implode('|', $cat_quick_infos_a).'|' : '';

    //cat quick info
    $quick_info_quids = explode('|', substr($cat_quick_infos, 1, -1));
  
    $where12 = " WHERE 1 = 2";
    foreach($quick_info_quids as $qi_quid) {
      $where12 .= " OR `quid` = '$qi_quid'";
    }
    $QUICK_INFOS = sql_fetch_all(sql_query("SELECT `quid`, `default` FROM `rpl_quick_infos`".$where12));

    foreach($QUICK_INFOS as $qi) {
      $value = ($_POST['cat_quick_info-'.$qi['quid']] == '') ? $qi['default'] : esc($_POST['cat_quick_info-'.$qi['quid']], 1);
      if($value != '') { $cat_quick_info_a[] = $qi['quid'].'>'.$value; }
    }

    $cat_quick_info = ($cat_quick_info_a[0] != '') ? '|'.implode('|', $cat_quick_info_a).'|' : '';


    //info query
    if($icon_perma != $_CATEGORY['icon_perma'] || $title != $_CATEGORY['title'] || $perma != $_CATEGORY['perma'] || $cat_quick_infos != $_CATEGORY['cat_quick_infos'] || $cat_quick_info != $_CATEGORY['cat_quick_info']) {
      $query_array[] = "UPDATE `rpl_categories` SET `icon_perma` = '$icon_perma', `title` = '$title', `perma` = '$perma', `cat_quick_infos` = '$cat_quick_infos', `cat_quick_info` = '$cat_quick_info' WHERE `caid` = '{$_CATEGORY['caid']}'";
      //cache cat and menu
    }


    //move content
    $move_content = (is_numeric($_POST['move_content'])) ? esc($_POST['move_content'], 1) : false;

    //parent category
    $_PARENT_CATEGORY = getCatInfo($_CATEGORY['parent_caid'], false, false);

    //status
    if($_CATEGORY['parent_caid'] != 0 && $_PARENT_CATEGORY['status'] == 0) { $status = 0; }
    else { $status = ($_POST['status'] == 1) ? 1 : 0; }
    $status_change = (is_numeric($_POST['status_change'])) ? esc($_POST['status_change'], 1) : 0;


    //status and move content querys
    if($_CATEGORY['content'] == 0) {
      if($move_content !== false) {
        if($move_content == 0) {
          $_NEW_CATEGORY['caid'] = 0;
          $_NEW_CATEGORY['content'] = 0;
          $_NEW_CATEGORY['level'] = -1;
          $_NEW_CATEGORY['status'] = 1;
        }
        else { $_NEW_CATEGORY = getCatInfo($move_content, false, false); }
        if($_NEW_CATEGORY['status'] == 0) { $status = 0; }
      }
      if($status != $_CATEGORY['status'] || $move_content !== false) { $_SUBCATS = getSubCats($_CATEGORY); }

      if($status != $_CATEGORY['status']) {
        if($status == 0) {//ha aktívról inaktívra váltódik a státusz
          $query1 = "UPDATE `rpl_categories` SET `status` = '0'";
          $where1 = " WHERE `caid` = '{$_CATEGORY['caid']}'";

          $query2 = "UPDATE `rpl_articles` SET `status` = '0'";
          $where2 = " WHERE `caid` = '{$_CATEGORY['caid']}'";

          $query3 = "UPDATE `rpl_relations` SET `status` = '0'";
          $where3 = " WHERE `related_caid` = '{$_CATEGORY['caid']}'";

          foreach($_SUBCATS as $cat) {
            if($cat['status'] != 0) {
              $where1 .= " OR `caid` = '{$cat['caid']}'";
              $where3 .= " OR `related_caid` = '{$cat['caid']}'";
              if($cat['content'] == 1) { $where2 .= " OR `caid` = '{$cat['caid']}'"; }
            }
          }

          $query_array[] = $query1.$where1;
          $query_array[] = $query2.$where2;
          $query_array[] = $query3.$where3;
        }
        else {//if($status == 1) //ha inaktívról aktívra váltódik a státusz
          $query4 = "UPDATE `rpl_categories` SET `status` = '1'";
          $where4 = " WHERE `caid` = '{$_CATEGORY['caid']}'";

          if($status_change > 0) {
            $query5 = "UPDATE `rpl_articles` SET `status` = '1'";
            $where5 = " WHERE `caid` = '{$_CATEGORY['caid']}'";

            $query6 = "UPDATE `rpl_relations` SET `status` = '1'";
            $where6 = " WHERE `related_caid` = '{$_CATEGORY['caid']}'";

            foreach($_SUBCATS as $cat) {
              if($cat['status'] != 1) {
                $where4 .= " OR `caid` = '{$cat['caid']}'";
                $where6 .= " OR `related_caid` = '{$cat['caid']}'";
                if($cat['content'] == 1) { $where5 .= " OR `caid` = '{$cat['caid']}'"; }
              }
              //else //log //sql hiba ha van aktív elem egy inaktív kategóriában
            }
          }

          $query_array[] = $query4.$where4;
          if($status_change > 1) { $query_array[] = $query5.$where5; }
          if($status_change > 2) { $query_array[] = $query6.$where6; }
        }
      }

      if($move_content !== false) {
        if($_NEW_CATEGORY == false || $_NEW_CATEGORY['caid'] == $_CATEGORY['caid'] || $_NEW_CATEGORY['content'] != $_CATEGORY['content'] || is_array($_SUBCATS[$_NEW_CATEGORY['caid']])) {
          redir('mod_category.php?id='.$_CATEGORY['caid'].'&error=2');//log
        }

        $query7 = "UPDATE `rpl_categories` SET `parent_caid` = '{$_NEW_CATEGORY['caid']}'";
        $where7 = " WHERE `parent_caid` = '{$_CATEGORY['caid']}'";
        $query_array[] = $query7.$where7;
  /*
        if($_NEW_CATEGORY['level'] != $_CATEGORY['level']) {
          $subCats_levels = groupArrays($_SUBCATS, 'level', false);
          foreach($subCats_levels as $subCats_level) {
            $subCats_new_level = false;
            foreach($subCats_level as $subCat) {
              if($subCats_new_level === false) {
                $subCats_new_level = ($subCat['level'] - $_CATEGORY['level']) + $_NEW_CATEGORY['level'];
                $query8 = "UPDATE `rpl_categories` SET `level` = '$subCats_new_level'";
                $where8 = " WHERE 1 = 2";
              }
              $where8 .= " OR `caid` = '{$subCat['caid']}'";
            }
            $query_array[] = $query8.$where8;
          }
        }
  */
        if($_NEW_CATEGORY['level'] != $_CATEGORY['level']) {
          foreach($_SUBCATS as $subCat) {
            if(!$subCats_levels[$subCat['level']]) {
              $subCats_new_level = ($subCat['level'] - $_CATEGORY['level']) + $_NEW_CATEGORY['level'];
              $subCats_levels[$subCat['level']] = "UPDATE `rpl_categories` SET `level` = '$subCats_new_level' WHERE 1 = 2";
            }
            $subCats_levels[$subCat['level']] .= " OR `caid` = '{$subCat['caid']}'";
          }
          foreach($subCats_levels as $subCats_level) { $query_array[] = $subCats_level; }
        }
      }
    }
    else {//if($_CATEGORY['content'] == 1)
      if($status != $_CATEGORY['status']) {
        if($status == 0) {//ha aktívról inaktívra váltódik a státusz
          $query1 = "UPDATE `rpl_categories` SET `status` = '0'";
          $where1 = " WHERE `caid` = '{$_CATEGORY['caid']}'";

          $query2 = "UPDATE `rpl_articles` SET `status` = '0'";
          $where2 = " WHERE `caid` = '{$_CATEGORY['caid']}'";

          $query3 = "UPDATE `rpl_relations` SET `status` = '0'";
          $where3 = " WHERE `related_caid` = '{$_CATEGORY['caid']}'";

          $query_array[] = $query1.$where1;
          $query_array[] = $query2.$where2;
          $query_array[] = $query3.$where3;
        }
        else {//if($status == 1) //ha inaktívról aktívra váltódik a státusz
          $query4 = "UPDATE `rpl_categories` SET `status` = '1'";
          $where4 = " WHERE `caid` = '{$_CATEGORY['caid']}'";

          if($status_change > 0) {
            $query5 = "UPDATE `rpl_articles` SET `status` = '1'";
            $where5 = " WHERE `caid` = '{$_CATEGORY['caid']}'";

            $query6 = "UPDATE `rpl_relations` SET `status` = '1'";
            $where6 = " WHERE `related_caid` = '{$_CATEGORY['caid']}'";
          }

          $query_array[] = $query4.$where4;
          if($status_change > 0) { $query_array[] = $query5.$where5; }
          if($status_change > 1) { $query_array[] = $query6.$where6; }
        }
      }

      if($move_content !== false) {
        $_NEW_CATEGORY = getCatInfo($move_content, false, false);
        if($_NEW_CATEGORY == false || $_NEW_CATEGORY['caid'] == $_CATEGORY['caid'] || $_NEW_CATEGORY['content'] != $_CATEGORY['content']) {
          redir('mod_category.php?id='.$_CATEGORY['caid'].'&error=2');//log
        }

        $query9 = "UPDATE `rpl_articles` SET `caid` = '{$_NEW_CATEGORY['caid']}'";
        if($_NEW_CATEGORY['status'] == 0) { $query9 .= ", `status` = '0'"; }
        $where9 = " WHERE `caid` = '{$_CATEGORY['caid']}'";
        $query_array[] = $query9.$where9;
      }
    }


    //dbg($query_array);//debug

    //run querys
    foreach($query_array as $queryX) { sql_query($queryX); }

    //new cache, new menu
    cacheCatList();
    generate_jsmenu();

    redir('mod_category.php?id='.$_CATEGORY['caid']);
  }
}
else { redir('categories.php?error=1'); }//log
?>