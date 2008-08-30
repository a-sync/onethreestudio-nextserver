<?php
/* MOD_QUICK_INFO.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 20)) { redir('index.php'); }//log

$link = permaHandling();
$_QUICK_INFO = getQuickInfo($link[0], $link[1]);

$error = esc($_GET['error'], 1);
$mod_quick_info_errors = array(
  1 => 'Invalid name.',
  2 => 'Invalid title.'
);

if($_QUICK_INFO !== false) {
  head(false, 'Quick infos &gt; '.esc($_QUICK_INFO['title'] ,2), $_USER);
  box(0);

  $type_sel[$_QUICK_INFO['type']] = ' selected';
  $input_type_sel[$_QUICK_INFO['input_type']] = ' selected';

  if($e = $mod_quick_info_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }
?>

  <form id="mod_quick_info" method="post" action="<?php echo $_HOST; ?>mod_quick_info_handler.php?id=<?php echo esc($_QUICK_INFO['quid'], 2); ?>">
    Gyors infó megnevezése*:
    <br/>
    <input type="text" name="title" maxlength="200" value="<?php echo esc($_QUICK_INFO['title'], 2); ?>"/>
    <br/>
    <br/>
    <br/>

    Gyors infó címe:
    <br/>
    (cikkben, listázában)
    <br/>
    <input type="text" name="description_title" maxlength="200" value="<?php echo esc($_QUICK_INFO['description_title'], 2); ?>"/>
    <br/>
    <br/>

    Érték elõtti / utáni szöveg:
    <br/>
    (az infónak megadott érték elé és/vagy után kiírandó szöveg)
    <br/>
    Elõtte: <input type="text" name="before_value" maxlength="200" value="<?php echo esc($_QUICK_INFO['before_value'], 2); ?>"/>
    <br/>
    Utána: <input type="text" name="after_value" maxlength="200" value="<?php echo esc($_QUICK_INFO['after_value'], 2); ?>"/>
    <br/>
    <br/>
    <br/>

    Alap érték:
    <br/>
    <input type="text" name="default" maxlength="200" value="<?php echo esc($_QUICK_INFO['default'], 2); ?>"/>
    <br/>
    <br/>

    Kitöltési segítség:
    <br/>
    (leírás a gyors infó mezõhöz, adat kitöltéskor) (max. 200 karakter)
    <br/>
    <textarea name="help" maxlength="200"><?php echo esc($_QUICK_INFO['value'], 2); ?></textarea>
    <br/>
    <br/>

    Foglalt hely oszlopként:
    <br/>
    (ha nulla, akkor csak a leírásban jelenik meg)
    <br/>
    <input type="text" name="width" maxlength="10" value="<?php echo esc($_QUICK_INFO['width'], 2); ?>"/>px
    <br/>
    <br/>

    Adat típusa:
    <select name="input_type">
      <option value="0"<?php echo $input_type_sel[0]; ?>>Egysoros</option>
      <option value="1"<?php echo $input_type_sel[1]; ?>>Többsoros</option>
    </select>
    <br/>
    <br/>

    Érték tartalma:
    <select name="type">
      <option value="0"<?php echo $type_sel[0]; ?>>Szöveg</option>
      <option value="1"<?php echo $type_sel[1]; ?>>Szám</option>
    </select>
    <br/>
    <br/>
    <br/>

    <span class="admin_delete">Gyors infó törlése: <input type="checkbox" name="delete_quick_info" value="true"/></span>
    <br/>
    <br/>
    <br/>

    <input type="hidden" name="id" value="<?php echo esc($_QUICK_INFO['quid'], 2); ?>"/>
    <input type="submit" value="Mehet">
  </form>

<?php
  box(1);
  foot();
}
else { redir('quick_infos.php?error=1'); }
?>