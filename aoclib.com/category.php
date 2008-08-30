<?php
/* CATEGORY.PHP */

include('functions.php');

$_USER = checkLogin();
$link = permaHandling();
$_CATEGORY = getCatInfo($link[0], $link[1]);

if($_CATEGORY === false) {
  $_CATEGORY['caid'] = 0;
  $_CATEGORY['title'] = 'Main';
  $_CATEGORY['content'] = 0;
  $_CATEGORY['perma'] = '';
  $_CATEGORY['cat_quick_infos'] = '';
  $_CATEGORY['cat_quick_info'] = '';
}

if($_CATEGORY['content'] == 0) { $CATEGORY_CHILDS = getCatChilds($_CATEGORY['caid']); }
//elseif($_CATEGORY['content'] == 1)
else { $ARTICLES = getCatArticles($_CATEGORY['caid']); }


if($_CATEGORY !== false) {
  $content_title = ($_CATEGORY['caid'] == 0) ? false : catTree($_CATEGORY['caid']);
  if(minClass($_USER, 30)) {
    $content_title .= ' &nbsp; [<a href="'.$_HOST.'mod_category.php?id='.esc($_CATEGORY['caid'], 2).'">Mod category</a>]';
    if($_CATEGORY['content'] == 1) { $content_title .= ' &nbsp; [<a href="'.$_HOST.'mod_relations.php?id='.esc($_CATEGORY['caid'], 2).'">Mod relations</a>]'; }
  }
  head(false, $content_title, $_USER);

  box(0);

  if($_CATEGORY['content'] == 0) {

    $CATEGORY_CHILDS = (is_array($CATEGORY_CHILDS)) ? sortArrays($CATEGORY_CHILDS, 'title') : '';

    foreach($CATEGORY_CHILDS as $cat_child) {
      $cat_qi_quids = explode('|', substr($cat_child['cat_quick_infos'], 1, -1));

      foreach($cat_qi_quids as $cat_qi_quid) {
        if($cat_qi_quid != $cats_qi_quids[$cat_qi_quid]) {
          $cats_qi_quids[$cat_qi_quid] = $cat_qi_quid;
        }
      }
    }

    //function
    $where2 = " WHERE 1 = 2";
    foreach($cats_qi_quids as $qi_quid_cat) {
      $where2 .= " OR `quid` = '$qi_quid_cat'";
    }
    $QUICK_INFOS = sql_fetch_all(sql_query("SELECT * FROM `rpl_quick_infos`".$where2));
    //function

    echo '<div class="list_title_row">'
        .'<div class="list_title_name va_table"><span class="va_cell">Name</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
        .'<div class="list_col_data">';
    foreach($QUICK_INFOS as $qi) {
      if($qi['width'] != 0) {
        echo '<div class="list_title_custom va_table" style="width: '.esc($qi['width'], 2).'px"><span class="va_cell">'.esc($qi['description_title'], 2).'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>';
      }
    }
    echo '</div></div>';

    foreach($CATEGORY_CHILDS as $cat_child) {
      //function
      $cat_quick_info_cats = explode('|', substr($cat_child['cat_quick_info'], 1, -1));

      $cat_quick_info = '';
      foreach($cat_quick_info_cats as $cat_quick_info_cat) {

        $cat_quick_info_cat = explode('>', $cat_quick_info_cat);

        $qi_quid = $cat_quick_info_cat[0];
        unset($cat_quick_info_cat[0]);

        $cat_quick_info[$qi_quid] = implode('>', $cat_quick_info_cat);
      }
      //function

      $this_icon = ($cat_child['icon_perma'] != '') ? '<img src="'.$_HOST.'pictures/icons/'.$cat_child['icon_perma'].'" alt=" "/>' : '';
      echo '<div class="list_row">'
          .'<div class="list_col_name va_table"><span class="va_cell">'
          .$this_icon
          .'<a href="'.$_HOST.'category/'.esc($cat_child['perma'], 2).'">'.esc($cat_child['title'], 2).'</a>'
          .'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
          .'<div class="list_col_data">';
      foreach($QUICK_INFOS as $qi) {
        if($qi['width'] != 0) {

          $value = ($cat_quick_info[$qi['quid']] == '') ? esc($qi['default'], 2) : esc($cat_quick_info[$qi['quid']], 2);
          $data = ($value == '' || $value == ' ') ? '&nbsp;' : esc($qi['before_value'], 2).$value.esc($qi['after_value'], 2);

          echo '<div class="list_col_custom va_table" style="width: '.esc($qi['width'], 2).'px"><span class="va_cell">'.$data.'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>';
        }
      }
      echo '</div></div>';
    }
  }
  else {//if($_CATEGORY['content'] == 1)

    $ARTICLES = (is_array($ARTICLES)) ? sortArrays($ARTICLES, 'title') : '';

    //function
    $quick_info_quids = explode('|', substr($_CATEGORY['quick_infos'], 1, -1));
    $where1 = " WHERE 1 = 2";
    foreach($quick_info_quids as $qi_quid) {
      $where1 .= " OR `quid` = '$qi_quid'";
    }
    $QUICK_INFOS = sql_fetch_all(sql_query("SELECT * FROM `rpl_quick_infos`".$where1));
    //function

    echo '<div class="list_title_row">'
        .'<div class="list_title_name va_table"><span class="va_cell">Name</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
        .'<div class="list_col_data">';
    foreach($QUICK_INFOS as $qi) {
      if($qi['width'] != 0) {
        echo '<div class="list_title_custom va_table" style="width: '.esc($qi['width'], 2).'px"><span class="va_cell">'.esc($qi['description_title'], 2).'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>';
      }
    }
    echo '</div></div>';


    foreach($ARTICLES as $art_child) {
      //function
      $art_quick_info_cats = explode('|', substr($art_child['quick_info'], 1, -1));

      $art_quick_info = '';
      foreach($art_quick_info_cats as $art_quick_info_cat) {
        $art_quick_info_cat = explode('>', $art_quick_info_cat);

        $qi_quid = $art_quick_info_cat[0];
        unset($art_quick_info_cat[0]);

        $art_quick_info[$qi_quid] = implode('>', $art_quick_info_cat);
      }
      //function

      $this_icon = ($art_child['icon_perma'] != '') ? '<img src="'.$_HOST.'pictures/icons/'.$art_child['icon_perma'].'" alt=" "/>' : '';
      echo '<div class="list_row">'
          .'<div class="list_col_name va_table"><span class="va_cell">'
          .$this_icon
          .'<a href="'.$_HOST.'article/'.esc($art_child['perma'], 2).'">'.esc($art_child['title'], 2).'</a>'
          .'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
          .'<div class="list_col_data">';
      foreach($QUICK_INFOS as $qi) {
        if($qi['width'] != 0) {

          $value = ($art_quick_info[$qi['quid']] == '') ? esc($qi['default'], 2) : esc($art_quick_info[$qi['quid']], 2);
          $data = ($value == '' || $value == ' ') ? '&nbsp;' : esc($qi['before_value'], 2).$value.esc($qi['after_value'], 2);

          echo '<div class="list_col_custom va_table" style="width: '.esc($qi['width'], 2).'px"><span class="va_cell">'.$data.'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>';
        }
      }
      echo '</div></div>';
    }
  }

  box(1);

  foot();
}
?>