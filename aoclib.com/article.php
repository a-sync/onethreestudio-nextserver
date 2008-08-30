<?php
/* ARTICLE.PHP */

include('functions.php');

$_USER = checkLogin();
$link = permaHandling();
$_ARTICLE = getArtInfo($link[0], $link[1]);

if($_ARTICLE !== false) {
//echo '<a align="left" href="'.$_HOST.'newarticle.php?article='.$_ARTICLE['arid'].'"> [cikk módosítása] </a>';

  $RELATED = getRelatedStuff($_ARTICLE);

  $relation_groups = groupArrays($RELATED['relations'], 'title');
  $categories = groupArrays($RELATED['categories'], 'caid', true);
  $article_cat_groups = groupArrays($RELATED['articles'], 'caid');

  $error = esc($_GET['error'], 1);
  if(minClass($_USER, 20)) {
    $upload_errors = array(
      1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
      2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive.',
      3 => 'The uploaded file was only partially uploaded.',
      4 => 'No file was uploaded.',
      6 => 'Missing a temporary folder.',
      7 => 'Failed to write file to disk.',
      8 => 'File upload stopped by extension.',
  
      9 => 'The uploaded file was empty. (0 byte)',
      10 => 'The uploaded file exceeds the $max_f_size directive.',
      11 => 'The given file is not passed by the post method.',
      12 => 'Not allowed filetype.',
      13 => 'Failed to move the uploaded file.',
      14 => 'Failed to insert row into MySQL.'
    );
  }
  else {
    $upload_errors = array(
      1 => 'The uploaded file exceeds the maximum filesize.',
      2 => 'The uploaded file exceeds the maximum filesize.',
      3 => 'The uploaded file was only partially uploaded.',
      4 => 'No file was uploaded.',
      6 => 'The file upload failed because of an internal error. Please try again later.',
      7 => 'The file upload failed because of an internal error. Please try again later.',
      8 => 'The file upload failed because of an internal error. Please try again later.',
  
      9 => 'The uploaded file was empty. (0 byte)',
      10 => 'The uploaded file exceeds the maximum filesize.',
      11 => 'The file upload failed because of an internal error. Please try again later.',
      12 => 'Not allowed filetype.',
      13 => 'The file upload failed because of an internal error. Please try again later.',
      14 => 'The file upload failed because of an internal error. Please try again later.'
    );
  }
  $selected_tab = $_GET['tab'];
  $checked_tab = false;

  $content_title = catTree($_ARTICLE['caid']);
  if(minClass($_USER, 30)) { $content_title .= ' &nbsp; [<a href="'.$_HOST.'mod_category.php?id='.esc($_ARTICLE['caid'], 2).'">Mod category</a>]'; }
  head(false, $content_title, $_USER);

  box(0);

  $this_icon = ($_ARTICLE['icon_perma'] != '') ? '<img src="'.$_HOST.'pictures/icons/'.$_ARTICLE['icon_perma'].'" alt=" "/>' : '';
  echo '<div id="article_head" class="va_table">'
      .$this_icon
      .'<span class="va_cell">'.esc($_ARTICLE['title'], 2).'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
      .'<div id="article_description"><p class="article_description_title">Description:</p>'
      .'<p class="article_description_text">'.nl2br(esc($_ARTICLE['description'], 2)).'</p>'
      .'</div>';


  //function
  $art_quick_info_cats = explode('|', substr($_ARTICLE['quick_info'], 1, -1));

  foreach($art_quick_info_cats as $art_quick_info_cat) {

    $art_quick_info_cat = explode('>', $art_quick_info_cat);

    $qi_quid = $art_quick_info_cat[0];
    unset($art_quick_info_cat[0]);

    $art_quick_info[$qi_quid] = implode('>', $art_quick_info_cat);
  }
  //function

  //function
  $quick_info_quids = explode('|', substr($categories[$_ARTICLE['caid']]['quick_infos'], 1, -1));
  $where1 = " WHERE 1 = 2";
  foreach($quick_info_quids as $qi_quid) {
    $where1 .= " OR `quid` = '$qi_quid'";
  }
  $QUICK_INFOS = sql_fetch_all(sql_query("SELECT * FROM `rpl_quick_infos`".$where1));
  //function


  if(is_array($QUICK_INFOS[0])) {
    echo '<div id="article_quick_infos">';


    foreach($QUICK_INFOS as $qi) {
      $val = ($art_quick_info[$qi['quid']] == '') ? esc($qi['default'], 2) : esc($art_quick_info[$qi['quid']], 2);
      if($qi['input_type'] == 1) { $val = nl2br($val); }

      if($val != '') {
        echo '<div class="quick_info">'
            .'<div class="quick_info_title">'.esc($qi['description_title'], 2).'</div>'
            .'<div class="quick_info_text">'.esc($qi['before_value'], 2).$val.esc($qi['after_value'], 2).'</div>'
            .'</div>';

        $is_info = true;
      }
    }
    if($is_info !== true) { echo 'No additional info...'; }


    echo '</div>';
  }


  foreach($relation_groups as $relation_group) {
    $tab_perma = makePermalink($relation_group[0]['title']);
    if(strtolower($selected_tab) == strtolower($tab_perma)) {
      $selected_tab = $tab_perma;
      $checked_tab = true;
    }

    foreach($relation_group as $relation) {
      $relation_qi_quids = explode('|', substr($relation['quick_infos'], 1, -1));

      foreach($relation_qi_quids as $relation_qi_quid) {
        if($relation_qi_quid != $qi_relation_group[$relation['title']][$relation_qi_quid]) {
          $qi_relation_group[$relation['title']][$relation_qi_quid] = $relation_qi_quid;
          $qi_relation_groups[] = $relation_qi_quid;
        }
      }
    }
  }


  //egybe lekérésnél ez kell plusz enek a cikknek a kategoriajanak a quid-jei
  //function
  $where2 = " WHERE 1 = 2";
  foreach($qi_relation_groups as $qi_quid_rg) {
    $where2 .= " OR `quid` = '$qi_quid_rg'";
  }
  $RELATIONS_QUICK_INFOS = sql_fetch_all(sql_query("SELECT * FROM `rpl_quick_infos`".$where2));
  //function
  $RELATIONS_QUICK_INFOS = groupArrays($RELATIONS_QUICK_INFOS, 'quid', true);


  $article_tabs = '<div id="article_tabs"><div id="article_related_tabs">';
  $article_relations = '';
  $selected_tab = ($checked_tab == true || $selected_tab == 'the_screenshots') ? $selected_tab : false;// || $selected_tab == 'the_comments'

  $selected_tab_bgcolor = '#242424';
  $category_qi_width = '100';//in pixels
  foreach($relation_groups as $relation_group) {
    if(true) {//szûrni hogy melyik kapcsolódásban VANNAK cikkek /  elõre kipörgetve a cikkeket kategoriába és relation group-ra

      $tab_perma = makePermalink($relation_group[0]['title']);
      $tab_style = ($selected_tab == $tab_perma || $selected_tab === false) ? ' style="background-color: '.$selected_tab_bgcolor.'";' : '';

      $article_tabs .= '<div class="related_tab va_table" onclick="toggle(\''.$tab_perma.'\')" id="'.$tab_perma.'_tab"'.$tab_style.'><span class="va_cell">'.esc($relation_group[0]['title'], 2).'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>';


      $relation_style = ($selected_tab == $tab_perma || $selected_tab === false) ? '' : ' style="display: none"';

      $article_relations .= '<div class="article_related" id="'.$tab_perma.'"'.$relation_style.'>'
      .'<div class="list_title_row">'

      .'<div class="list_title_name va_table"><span class="va_cell">Name</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'

      .'<div class="list_col_data">';
      foreach($qi_relation_group[$relation_group[0]['title']] as $qi_quid) {
        if($RELATIONS_QUICK_INFOS[$qi_quid]['width'] != 0) {
          $article_relations .= '<div class="list_title_custom va_table" style="width: '.esc($RELATIONS_QUICK_INFOS[$qi_quid]['width'], 2).'px"><span class="va_cell">'.esc($RELATIONS_QUICK_INFOS[$qi_quid]['description_title'], 2).'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>';
        }
      }
      $article_relations .= '<div class="list_title_custom va_table" style="width: '.$category_qi_width.'px"><span class="va_cell">Category</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
      .'</div></div>';

      //$relation_group = sortArrays($relation_group, 'title')
      foreach($relation_group as $relation) {

        $article_cat_group = (is_array($article_cat_groups[$relation['related_caid']])) ? sortArrays($article_cat_groups[$relation['related_caid']], 'title') : '';

        foreach($article_cat_group as $r_article) {

          //function
          $art_quick_info_cats = explode('|', substr($r_article['quick_info'], 1, -1));

          $art_quick_info = '';
          foreach($art_quick_info_cats as $art_quick_info_cat) {

            $art_quick_info_cat = explode('>', $art_quick_info_cat);

            $qi_quid = $art_quick_info_cat[0];
            unset($art_quick_info_cat[0]);

            $art_quick_info[$qi_quid] = implode('>', $art_quick_info_cat);
          }
          //function

          $this_icon = ($r_article['icon_perma'] != '') ? '<img src="'.$_HOST.'pictures/icons/'.$r_article['icon_perma'].'" alt=" "/>' : '';
          $article_relations .= '<div class="list_row">'
          .'<div class="list_col_name va_table"><span class="va_cell">'
          .$this_icon
          .'<a href="'.$_HOST.'article/'.esc($r_article['perma'], 2).'">'.esc($r_article['title'], 2).'</a>'
          .'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
          .'<div class="list_col_data">';
          foreach($qi_relation_group[$relation_group[0]['title']] as $qi_quid) {

            if($RELATIONS_QUICK_INFOS[$qi_quid]['width'] != 0) {
              $value = ($art_quick_info[$qi_quid] == '') ? esc($RELATIONS_QUICK_INFOS[$qi_quid]['default'], 2) : esc($art_quick_info[$qi_quid], 2);
              $data = ($value == '' || $value == ' ') ? '&nbsp;' : esc($RELATIONS_QUICK_INFOS[$qi_quid]['before_value'], 2).$value.esc($RELATIONS_QUICK_INFOS[$qi_quid]['after_value'], 2);

              $article_relations .= '<div class="list_col_custom va_table" style="width: '.esc($RELATIONS_QUICK_INFOS[$qi_quid]['width'], 2).'px"><span class="va_cell">'.$data.'</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>';
            }
          }
          $article_relations .= '<div class="list_col_custom va_table" style="width: '.$category_qi_width.'px"><span class="va_cell"><a href="'.$_HOST.'category/'.esc($categories[$r_article['caid']]['perma'], 2).'">'.esc($categories[$r_article['caid']]['title'], 2).'</a></span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
          .'</div></div>';
        }
      }
      $article_relations .= '</div>';

      if($selected_tab === false) { $selected_tab = $tab_perma; }
    }
  }
  $article_tabs .= '</div>';
  
  if($selected_tab === false) { $selected_tab = 'the_screenshots'; }
  $article_tabs .= '<div id="article_extra_tabs">';
  $the_screenshots_tab_style = ($selected_tab == 'the_screenshots') ? ' style="background-color: '.$selected_tab_bgcolor.'";' : '';
  $article_tabs .= '<div class="extra_tab va_table" onclick="toggle(\'the_screenshots\')" id="the_screenshots_tab"'.$the_screenshots_tab_style.'><span class="va_cell"><a name="tabs">Screenshots</a></span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
  //;/$the_comments_tab_style = ($selected_tab == 'the_comments') ? ' style="background-color: '.$selected_tab_bgcolor.'";' : '';
  //$article_tabs .= '<div class="extra_tab va_table" onclick="toggle(\'the_comments\')" id="the_comments_tab"'.$the_comments_tab_style.'><span class="va_cell">Comments</span><!--[if lte IE 7]><b class="va_ie"></b><![endif]--></div>'
  .'</div>'
  .'</div>';

  echo '<script type="text/javascript">
  function toggle(id)
  {
    document.getElementById(open_tab).style.display = "none";
    document.getElementById(open_tab + "_tab").style.backgroundColor = "";

    open_tab = id;

    document.getElementById(id).style.display = "";
    document.getElementById(id + "_tab").style.backgroundColor = "'.$selected_tab_bgcolor.'";
  }

  var open_tab = "'.$selected_tab.'";
  </script>'."\n\r\n\r"
  .$article_tabs."\n\r\n\r"
  .$article_relations;


  //screenshots
  $the_screenshots_related_style = ($selected_tab == 'the_screenshots') ? '' : ' style="display: none"';
  echo '<div class="article_related" id="the_screenshots"'.$the_screenshots_related_style.'>';

  $PICTURES = sql_fetch_all(sql_query("SELECT * FROM `rpl_pictures` WHERE `arid` = '{$_ARTICLE['arid']}' AND `type` = '1' AND `shown` > '0'"));

  if(is_array($PICTURES)) {
    foreach($PICTURES as $this_pic) {
      echo '<div class="screenshot">'
          .'<a href="'.$_HOST.'pictures/'.$this_pic['perma'].'" target="_blank"><img src="'.$_HOST.'pictures/thumbnails/'.$this_pic['piid'].'.jpg'.'" alt="'. esc($this_pic['title'], 2) .'"/></a>'
          .'<br/><br/>'.wrapper($this_pic['title'])
          .'</div>';
    }
  }

  $max_f_size = 8 * 1024 * 1024 * 2;//2MB
?>

  <form id="upload_screenshot" method="post" action="<?php echo $_HOST; ?>screenshot_handler.php?id=<?php echo $_ARTICLE['arid']; ?>" enctype="multipart/form-data">

    <p class="upload_screenshot_title">
      Uplad screenshot:<br/>
      (max. <?php echo number_format(floor($max_f_size / 1024 / 1024 / 8), 2, '.', ' '); ?> MB, .jpg or .png)
    </p>

    <p class="upload_screenshot_data">
      Title: <input name="title" type="text" maxlength="50" autocomplete="off"/>
      <br/>
      File: &nbsp;<input name="screenshot" type="file" autocomplete="off"/>
      <br/>
      <input type="hidden" name="id" value="<?php echo esc($_ARTICLE['arid'], 2); ?>"/>
      <input type="hidden" name="max_file_size" value="<?php echo $max_f_size; ?>"/>
      <br/>
      <?php
        if($_USER == false) { echo '<a href="'.$_HOST.'login.php">Please login to upload pictures.</a>'; }
        else { echo '<input type="submit" value="Upload!"/>'; }
      ?>
    </p>

    <?php
      if($e = $upload_errors[$error]) { echo '<p class="error">'.$e.'</p>'; }
    ?>

  </form>

<?php
  echo '</div>';
  //screenshots

  //comments
  //$the_comments_related_style = ($selected_tab == 'the_comments') ? '' : ' style="display: none"';
  //echo '<div class="article_related" id="the_comments"'.$the_comments_related_style.'>';
  //echo '</div>';
  //comments

  box(1);

  foot();
}
else { redir('index.php'); }
//log
?>