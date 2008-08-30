<?php
/* ADD_CATEGORY.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 30)) { redir('index.php'); }//log

$error = esc($_GET['error'], 1);
$add_category_errors = array(
  1 => 'Invalid title.',
  2 => 'Invalid parent category.',
  3 => 'Failed to insert row into MySQL.'
);

head(false, 'Categories &gt; New category', $_USER);
box(0);

  $catList = getCatlist();
  $ICONS = sql_fetch_all(sql_query("SELECT * FROM `rpl_pictures` WHERE `type` = '0' ORDER BY `piid` DESC"));

  if($e = $add_category_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }
?>

  <form id="add_category" method="post" action="<?php echo $_HOST; ?>add_category_handler.php">
    Szõlõ kategória:
    <br/>
    <select name="parent_caid">
      <option value="0">Nincs (Fõkategória)</option>
      <?php
        foreach($catList as $cat) {
          if($cat['status'] == 1) {
            $inln = '';
            for($i = 0; $i < $cat['level']; $i++) { $inln .= '--'; }

            if($cat['content'] == 0) { echo '<option value="'.esc($cat['caid'], 2).'">'.$inln.' '.esc($cat['title'], 2).'</option>'; }
            else { echo '<option disabled>'.$inln.' '.esc($cat['title'], 2).'</option>'; }
          }
        }
      ?>
    </select>
    <br/>
    <br/>

    Ikon:
    <br/>
    <select name="icon">
      <option value="0">Nincs ikon</option>
      <?php
        foreach($ICONS as $this_icon) {
          echo '<option value="'.esc($this_icon['piid'], 2).'">'.esc($this_icon['title'], 2).'</option>';
        }
      ?>
    </select>
    <br/>
    <br/>

    Kategória címe*:
    <br/>
    (max. 200 character)
    <br/>
    <input type="text" name="title" maxlength="200"/>
    <br/>
    <br/>

    Permalink cím:
    <br/>
    (max. 200 character)
    <br/>
    <input type="text" name="perma" maxlength="200"/>
    <br/>
    <br/>

    Tartalma:
    <br/>
    <select name="content">
      <option value="0">Kategóriák</option>
      <option value="1">Cikkek</option>
    </select>
    <br/>
    <br/>

    Státusz:
    <br/>
    <select name="status">
      <option value="0">Inaktív</option>
      <option value="1" selected>Aktív</option>
    </select>
    <br/>
    <br/>

    <input type="submit" value="Mehet">
</form>

<?php
box(1);
foot();
?>