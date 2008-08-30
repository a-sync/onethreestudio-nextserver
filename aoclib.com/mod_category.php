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

//content kateg�ri�k/cikkek ki�rat�sa
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

    Kateg�ria c�me*:
    <br/>
    (max. 200 character)
    <br/>
    <input type="text" name="title" maxlength="200" value="<?php echo esc($_CATEGORY['title'], 2); ?>"/>
    <br/>
    <br/>

    Permalink c�m:
    <br/>
    (max. 200 character)
    <br/>
    <input type="text" name="perma" maxlength="200" value="<?php echo esc($_CATEGORY['perma'], 2); ?>"/>
    <br/>
    <br/>

    Gyors inf�k:
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

      if(!$qis) { echo 'Nincsenek gyors info mez�k.'; }
    ?>
    <br/>
    <br/>

    Gyors inf� mez�k:
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

    Tartalom �thelyez�se:
    <br/>
    (A teljes tartalom(#) �thelyez�dik egy m�sik kateg�ri�ba. (kateg�ri�k, cikkek))
    <br/>
    (Ha a c�lkateg�ria inakt�v(*), a teljes tartalom(#) is inakt�vv�(*) v�lik! (kapcsol�d�sok, kateg�ri�k, cikkek))
    <br/>
    <select name="move_content">
      <option value="false">Ne helyezze �t</option>
      <option value="0">F�kateg�ria</option>
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

    St�tusz:
    <br/>
    <?php
    if($_CATEGORY['parent_caid'] == 0 || $_PARENT_CATEGORY['status'] == 1) {
      if($_CATEGORY['status'] == 1) { echo '(Az inakt�vra �ll�t�s minden kapcsol�d�sra/alkateg�ri�ra/cikkre �rv�nyes lesz.)<br/>'; } ?>
      <select name="status">
       <option value="0"<?php echo $status_sel[0]; ?>>Inakt�v</option>
       <option value="1"<?php echo $status_sel[1]; ?>>Akt�v</option>
      </select>
      <br/>

      <?php if($_CATEGORY['status'] == 0) { ?>
        A st�tusz aktivra v�lt�sa:
        <br/>
        <select name="status_change">
        <option value="0">erre a kateg�ri�ra legyen �rv�nyes</option>
        <option value="1"> + minden alkateg�ri�ra (nem aj�nlott)</option>
        <option value="2"> + �s cikkeikre (nem aj�nlott)</option>
        <option value="3"> + �s a kapcsol�d�saikra (nem aj�nlott)</option>
        </select>
      <?php
      }
    }
    else { echo 'Am�g a sz�l�kateg�ria inakt�v, addig a st�tusz inakt�v marad.'; }
    ?>
    <br/>
    <br/>

    <span class="admin_delete">Delete category: <input type="checkbox" name="delete_category" value="true"/> 
    (az �sszes kapcsol�d�sra/alkateg�ri�ra/cikkre is �rv�nyes lesz a t�rl�s)</span>
    <br/>
    <br/>
    <br/>

<?php
  }
  else {//if($_CATEGORY['content'] == 1)
?>

    Tartalom �thelyez�se:
    <br/>
    (Az �sszes cikk �thelyez�dik egy m�sik kateg�ri�ba.)
    <br/>
    (Ha a c�lkateg�ria inakt�v(*), az �sszes cikk is inakt�vv�(*) v�lik!)
    <br/>
    <select name="move_content">
      <option value="false">Ne helyezze �t</option>
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

    St�tusz:
    <br/>
    <?php
    if($_CATEGORY['parent_caid'] == 0 || $_PARENT_CATEGORY['status'] == 1) {
      if($_CATEGORY['status'] == 1) { echo '(Az inakt�vra �ll�t�s minden kapcsol�d�sra/cikkre �rv�nyes lesz.)<br/>'; } ?>
      <select name="status">
       <option value="0"<?php echo $status_sel[0]; ?>>Inakt�v</option>
       <option value="1"<?php echo $status_sel[1]; ?>>Akt�v</option>
      </select>
      <br/>

      <?php if($_CATEGORY['status'] == 0) { ?>
        A st�tusz aktivra v�lt�sa:
        <br/>
        <select name="status_change">
        <option value="0">erre a kateg�ri�ra legyen �rv�nyes</option>
        <option value="1"> + minden cikkre (nem aj�nlott)</option>
        <option value="2"> + �s a kapcsol�d�sokra (nem aj�nlott)</option>
        </select>
      <?php
      }
    }
    else { echo 'Am�g a sz�l�kateg�ria inakt�v, addig a st�tusz inakt�v marad.'; }
    ?>
    <br/>
    <br/>


    <span class="admin_delete">Delete category: <input type="checkbox" name="delete_category" value="true"/> 
    (az �sszes kapcsol�d�sra/cikkre is �rv�nyes lesz a t�rl�s)</span>
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