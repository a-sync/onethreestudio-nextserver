<?php
/* MOD_RELATIONS.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 30)) { redir('index.php'); }//log

$link = permaHandling();
$_CATEGORY = getCatInfo($link[0], $link[1], false);

$error = esc($_GET['error'], 1);
$mod_relations_errors = array(//debug
  1 => 'Invalid category to copy the quick infos.',
  2 => 'Invalid category to copy the relations.'
);

if($_CATEGORY !== false) {
  if($_CATEGORY['content'] != 1) { redir('mod_category.php?id='.$_CATEGORY['caid']); }

  $catList = getCatlist();
  $QUICK_INFOS = sql_fetch_all(sql_query("SELECT * FROM `rpl_quick_infos` ORDER BY `quid` DESC"), 'quid', true);
  $RELATIONS = sql_fetch_all(sql_query("SELECT * FROM `rpl_relations` WHERE `caid` = '{$_CATEGORY['caid']}'"), 'related_caid', true);
  $sel_quick_infos = explode('|', substr($_CATEGORY['quick_infos'], 1, -1));

  $content_title = catTree($_CATEGORY['caid'], $catList);
  $content_title .= ' &nbsp; [<a href="'.$_HOST.'mod_category.php?id='.esc($_CATEGORY['caid'], 2).'">Mod category</a>]';
  head(false, $content_title, $_USER);
  //head(false, 'Categories &gt; '.$content_title, $_USER);
  box(0);

  if($e = $mod_relations_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }
?>

  <form id="mod_relations" method="post" action="<?php echo $_HOST; ?>mod_relations_handler.php?id=<?php echo esc($_CATEGORY['caid'], 2); ?>">
    Tartalma: <?php if($_CATEGORY['content'] == 1) { echo 'Cikkek'; } else { echo 'Kategóriák'; } ?>
    <br/>
    <br/>

    Státusz: <?php if($_CATEGORY['status'] == 1) { echo 'Aktív'; } else { echo 'Inaktív'; } ?>
    <br/>
    <br/>

    Cikkek gyors infó mezõi:
    <br/>
    <select name="quick_infos[]" size="10" multiple>
    <?php
      foreach($QUICK_INFOS as $this_qi) {
        $selected = (in_array($this_qi['quid'], $sel_quick_infos)) ? ' selected' : '';
        echo '<option value="'.esc($this_qi['quid'], 2).'"'.$selected.'>'.esc($this_qi['title'], 2).'</option>';
      }
    ?>
    </select>
    <br/>
    <br/>

    Másolja le a kiválasztott kategóra cikkeihez beállított gyors infó mezõket:
    <br/>
    <select name="copy_qi">
      <option value="false">Ne másolja</option>
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
    <input type="submit" value="Mehet">
    <br/>
    <br/>
    <hr/>
    <br/>

    Másolja le a kiválasztott kategóriához beállított kapcsolódásokat:
    <br/>
    (a meglévõ és másolás alatt érintetlen kapcsolódások inaktívak lesznek)
    <br/>
    <select name="copy_rel">
      <option value="false">Ne másolja</option>
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
    <input type="checkbox" name="copy_rel_active" value="true" checked/> Csak az aktív kapcsolódásokat másolja át.
    <br/>
    <input type="checkbox" name="copy_rel_qi" value="true" checked/> Kapcsolódásokhoz tartozó gyors infó kapcsolatokat is másolja át.
    <br/>
    <br/>
    <br/>

    Kapcsolódások:
    <br/>
    (kapcsolódásoknál megjelenõ gyors infó mezõk)
    <br/>
    <br/>

    <?php
      $first = true;
      foreach($catList as $cat) {
        $inln = '-';
        $subln = '&nbsp; &nbsp;';
        for($i = 0; $i < $cat['level']; $i++) { $inln .= '----'; $subln .= ' &nbsp; &nbsp;'; }

        if($cat['status'] == 0) { $inln .= '*'; }
        if($first === true) { $first = false; }
        else { $inln = ($cat['level'] == 0) ? '<br/><hr width="70%" align="left"/>'.$inln : '<br/>'.$inln; }

        if($cat['content'] == 1) {
          $status = ($RELATIONS[$cat['caid']]['status'] == 1) ? ' checked' : '';
          if($cat['status'] == 0) { $status = ' disabled'; }

          $this_cat_qis_a = explode('|', substr($cat['quick_infos'], 1, -1));
          $this_cat_sel_qis_a = explode('|', substr($RELATIONS[$cat['caid']]['quick_infos'], 1, -1));

          //echo '<br/>'.$subln.' '.$cat['title']
          echo $inln.' '.$cat['title']
              .'<br/>'.$subln.' <input type="text" maxlength="200" name="related_title-'.esc($cat['caid'], 2).'" value="'.esc($RELATIONS[$cat['caid']]['title'], 2).'"/>'
              .' Aktív: <input type="checkbox" value="true" name="related_status-'.esc($cat['caid'], 2).'"'.$status.'/>'
              .'<br/>'.$subln.' <select name="related_quick_infos-'.esc($cat['caid'], 2).'[]" size="5" multiple>';

          foreach($this_cat_qis_a as $this_cat_qi_quid) {
            if($QUICK_INFOS[$this_cat_qi_quid]['width'] != 0) {
              $selected = (in_array($this_cat_qi_quid, $this_cat_sel_qis_a)) ? ' selected' : '';
              echo '<option value="'.esc($QUICK_INFOS[$this_cat_qi_quid]['quid'], 2).'"'.$selected.'>'.esc($QUICK_INFOS[$this_cat_qi_quid]['title'], 2).'</option>';
            }
          }

          echo '</select>';
        }
        else {//if($cat['content'] == 0)
          echo $inln.' '.$cat['title'];
        }
      }
    ?>
    <br/>
    <br/>
    <br/>
    <br/>

    <input type="hidden" name="id" value="<?php echo esc($_CATEGORY['caid'], 2); ?>">
    <input type="submit" value="Mehet">
  </form>

<?php
  box(1);
  foot();
}
else { redir('categories.php?error=1'); }
?>