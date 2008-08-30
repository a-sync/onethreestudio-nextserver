<?php
/* MOD_CATEGORY.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 30)) { redir('index.php'); }//log

$link = permaHandling();
$_CATEGORY = getCatInfo($link[0], $link[1], false);

$error = esc($_GET['error'], 1);
$mod_category_errors = array(
  1 => 'Invalid title.',
  2 => 'Invalid category to move content to.'
);

//content kategóriák/cikkek kiíratása
if($_CATEGORY !== false) {
  $catList = getCatlist();
  //head(false, 'Categories &gt; '.catTree($_CATEGORY['caid'], $catList), $_USER);
  $content_title = catTree($_CATEGORY['caid'], $catList);
  if($_CATEGORY['content'] == 1) { $content_title .= ' &nbsp; [<a href="'.$_HOST.'mod_relations.php?id='.esc($_CATEGORY['caid'], 2).'">Mod relations</a>]'; }
  head(false, $content_title, $_USER);
  box(0);

  if($_CATEGORY['parent_caid'] != 0) { $_PARENT_CATEGORY = getCatInfo($_CATEGORY['parent_caid']); }
  $ICONS = sql_fetch_all(sql_query("SELECT * FROM `rpl_pictures` WHERE `type` = '0' ORDER BY `title` ASC"));
  $QUICK_INFOS = sql_fetch_all(sql_query("SELECT * FROM `rpl_quick_infos` ORDER BY `quid` DESC"), 'quid', true);
  $cat_quick_info_a = explode('|', substr($_CATEGORY['cat_quick_info'], 1, -1));
  $cat_quick_infos_a = explode('|', substr($_CATEGORY['cat_quick_infos'], 1, -1));

  $icon_sel[$_CATEGORY['icon_perma']] = ' selected';
  $status_sel[$_CATEGORY['status']] = ' selected';

  if($e = $mod_category_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }
?>

  <form id="mod_category" method="post" action="<?php echo $_HOST; ?>mod_category_handler.php?id=<?php echo esc($_CATEGORY['caid'], 2); ?>">

    Ikon:
    <br/>
    <select name="icon">
      <option value="0">Nincs ikon</option>
      <?php
        foreach($ICONS as $this_icon) {
          echo '<option value="'.esc($this_icon['piid'], 2).'"'.$icon_sel[$this_icon['perma']].'>'.esc($this_icon['title'], 2).'</option>';
        }
      ?>
    </select>
    <br/>
    <br/>

    Kategória címe*:
    <br/>
    (max. 200 character)
    <br/>
    <input type="text" name="title" maxlength="200" value="<?php echo esc($_CATEGORY['title'], 2); ?>"/>
    <br/>
    <br/>

    Permalink cím:
    <br/>
    (max. 200 character)
    <br/>
    <input type="text" name="perma" maxlength="200" value="<?php echo esc($_CATEGORY['perma'], 2); ?>"/>
    <br/>
    <br/>

    Gyors infók:
    <br/>
    <?php
      foreach($cat_quick_info_a as $cat_quick_info) {
        $cat_quick_info = explode('>', $cat_quick_info);

        $this_quid = $cat_quick_info[0];
        unset($cat_quick_info[0]);
        $cat_quick_info_text[$this_quid] = implode('>', $cat_quick_info);
      }

      foreach($cat_quick_infos_a as $this_quid) {
        $qi = $QUICK_INFOS[$this_quid];
        if(is_array($qi)) {
          $qis = true;
          $qi_value = esc($cat_quick_info_text[$qi['quid']], 2);
          if($qi_value == '') { esc($qi['default'], 2); }

          if($qi['input_type'] == 0) {
            echo esc($qi['description_title'], 2).': &nbsp; '
                .esc($qi['before_value'], 2)
                .'<input value="'.$qi_value.'" name="cat_quick_info-'.esc($qi['quid'], 2).'" type="text"/>'
                .esc($qi['after_value'], 2).'<br/>';
          }
          else {//if($qi['input_type'] == 1)
            echo esc($qi['description_title'], 2).':<br/>'
                .esc($qi['before_value'], 2).'<br/>'
                .'<textarea name="cat_quick_info-'.esc($qi['quid'], 2).'">'.$qi_value.'</textarea>'.'<br/>'
                .esc($qi['after_value'], 2).'<br/>';
          }
          if($qi['help'] != '') { echo esc($qi['help'], 2).'<br/>'; }
        }
      }

      if(!$qis) { echo 'Nincsenek gyors info mezõk.'; }
    ?>
    <br/>
    <br/>

    Gyors infó mezõk:
    <br/>
    <select name="cat_quick_infos[]" size="10" multiple>
      <?php
        foreach($QUICK_INFOS as $this_qi) {
          $selected = (in_array($this_qi['quid'], $cat_quick_infos_a)) ? ' selected' : '';
          echo '<option value="'.esc($this_qi['quid'], 2).'"'.$selected.'>'.esc($this_qi['title'], 2).'</option>';
        }
      ?>
    </select>
    <br/>
    <br/>

<?php
  if($_CATEGORY['content'] == 0) {
?>

    Tartalom áthelyezése:
    <br/>
    (A teljes tartalom(#) áthelyezõdik egy másik kategóriába. (kategóriák, cikkek))
    <br/>
    (Ha a célkategória inaktív(*), a teljes tartalom(#) is inaktívvá(*) válik! (kapcsolódások, kategóriák, cikkek))
    <br/>
    <select name="move_content">
      <option value="false">Ne helyezze át</option>
      <option value="0">Fõkategória</option>
      <?php
        foreach($catList as $cat) {
          $inln = '';
          for($i = 0; $i < $cat['level']; $i++) { $inln .= '--'; }

          if($disabled === true && $cat['level'] <= $_CATEGORY['level']) { $disabled = false; }
          elseif($cat['caid'] == $_CATEGORY['caid']) { $disabled = true; }

          if($disabled === true) { $inln .= '#'; }
          elseif($cat['status'] == 0) { $inln .= '*'; }

          if($cat['content'] == 0 && $disabled !== true) { echo '<option value="'.esc($cat['caid'], 2).'">'.$inln.' '.esc($cat['title'], 2).'</option>'; }
          else { echo '<option disabled>'.$inln.' '.esc($cat['title'], 2).'</option>'; }
        }
      ?>
    </select>
    <br/>
    <br/>

    Státusz:
    <br/>
    <?php
    if($_CATEGORY['parent_caid'] == 0 || $_PARENT_CATEGORY['status'] == 1) {
      if($_CATEGORY['status'] == 1) { echo '(Az inaktívra állítás minden kapcsolódásra/alkategóriára/cikkre érvényes lesz.)<br/>'; } ?>
      <select name="status">
       <option value="0"<?php echo $status_sel[0]; ?>>Inaktív</option>
       <option value="1"<?php echo $status_sel[1]; ?>>Aktív</option>
      </select>
      <br/>

      <?php if($_CATEGORY['status'] == 0) { ?>
        A státusz aktivra váltása:
        <br/>
        <select name="status_change">
        <option value="0">erre a kategóriára legyen érvényes</option>
        <option value="1"> + minden alkategóriára (nem ajánlott)</option>
        <option value="2"> + és cikkeikre (nem ajánlott)</option>
        <option value="3"> + és a kapcsolódásaikra (nem ajánlott)</option>
        </select>
      <?php
      }
    }
    else { echo 'Amíg a szülõkategória inaktív, addig a státusz inaktív marad.'; }
    ?>
    <br/>
    <br/>

    <span class="admin_delete">Delete category: <input type="checkbox" name="delete_category" value="true"/> 
    (az összes kapcsolódásra/alkategóriára/cikkre is érvényes lesz a törlés)</span>
    <br/>
    <br/>
    <br/>

<?php
  }
  else {//if($_CATEGORY['content'] == 1)
?>

    Tartalom áthelyezése:
    <br/>
    (Az összes cikk áthelyezõdik egy másik kategóriába.)
    <br/>
    (Ha a célkategória inaktív(*), az összes cikk is inaktívvá(*) válik!)
    <br/>
    <select name="move_content">
      <option value="false">Ne helyezze át</option>
      <?php
        foreach($catList as $cat) {
          $inln = '';
          for($i = 0; $i < $cat['level']; $i++) { $inln .= '--'; }

          if($cat['status'] == 0) { $inln .= '*'; }

          if($cat['content'] == 1 && $cat['caid'] != $_CATEGORY['caid']) { echo '<option value="'.esc($cat['caid'], 2).'">'.$inln.' '.esc($cat['title'], 2).'</option>'; }
          else { echo '<option disabled>'.$inln.' '.esc($cat['title'], 2).'</option>'; }
        }
      ?>
    </select>
    <br/>
    <br/>

    Státusz:
    <br/>
    <?php
    if($_CATEGORY['parent_caid'] == 0 || $_PARENT_CATEGORY['status'] == 1) {
      if($_CATEGORY['status'] == 1) { echo '(Az inaktívra állítás minden kapcsolódásra/cikkre érvényes lesz.)<br/>'; } ?>
      <select name="status">
       <option value="0"<?php echo $status_sel[0]; ?>>Inaktív</option>
       <option value="1"<?php echo $status_sel[1]; ?>>Aktív</option>
      </select>
      <br/>

      <?php if($_CATEGORY['status'] == 0) { ?>
        A státusz aktivra váltása:
        <br/>
        <select name="status_change">
        <option value="0">erre a kategóriára legyen érvényes</option>
        <option value="1"> + minden cikkre (nem ajánlott)</option>
        <option value="2"> + és a kapcsolódásokra (nem ajánlott)</option>
        </select>
      <?php
      }
    }
    else { echo 'Amíg a szülõkategória inaktív, addig a státusz inaktív marad.'; }
    ?>
    <br/>
    <br/>


    <span class="admin_delete">Delete category: <input type="checkbox" name="delete_category" value="true"/> 
    (az összes kapcsolódásra/cikkre is érvényes lesz a törlés)</span>
    <br/>
    <br/>
    <br/>

<?php
  }
?>

    <input type="hidden" name="id" value="<?php echo esc($_CATEGORY['caid'], 2); ?>">
    <input type="submit" value="Mehet">

  </form>

<?php
  box(1);
  foot();
}
else { redir('categories.php?error=1'); }
?>