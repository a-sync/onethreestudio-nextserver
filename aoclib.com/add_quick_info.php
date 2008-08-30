<?php
/* ADD_QUICK_INFO.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 20)) { redir('index.php'); }//log

$error = esc($_GET['error'], 1);
$add_quick_info_errors = array(
  1 => 'Invalid name.',
  2 => 'Invalid title.',
  3 => 'Failed to insert row into MySQL.'
);

head(false, 'Quick infos &gt; New quick info', $_USER);
box(0);

  if($e = $add_quick_info_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }
?>

  <form id="add_quick_info" method="post" action="<?php echo $_HOST; ?>add_quick_info_handler.php">
    Gyors infó megnevezése*:
    <br/>
    <input type="text" name="title" maxlength="200"/>
    <br/>
    <br/>
    <br/>

    Gyors infó címe*:
    <br/>
    (cikkben, listázában)
    <br/>
    <input type="text" name="description_title" maxlength="200"/>
    <br/>
    <br/>

    Érték elõtti / utáni szöveg:
    <br/>
    (az infónak megadott érték elé és/vagy után kiírandó szöveg)
    <br/>
    Elõtte: <input type="text" name="before_value" maxlength="200"/>
    <br/>
    Utána: <input type="text" name="after_value" maxlength="200"/>
    <br/>
    <br/>
    <br/>

    Alap érték:
    <br/>
    <input type="text" name="default" maxlength="200"/>
    <br/>
    <br/>

    Kitöltési segítség:
    <br/>
    (leírás a gyors infó mezõhöz, adat kitöltéskor) (max. 200 karakter)
    <br/>
    <textarea name="help" maxlength="200"></textarea>
    <br/>
    <br/>

    Foglalt hely oszlopként:
    <br/>
    (ha nulla, akkor csak a leírásban jelenik meg)
    <br/>
    <input type="text" name="width" maxlength="10" value="0"/>px
    <br/>
    <br/>

    Adat típusa:
    <select name="input_type">
      <option value="0" selected>Egysoros</option>
      <option value="1">Többsoros</option>
    </select>
    <br/>
    <br/>

    Érték tartalma:
    <select name="type">
      <option value="0" selected>Szöveg</option>
      <option value="1">Szám</option>
    </select>
    <br/>
    <br/>

    <input type="submit" value="Mehet">
  </form>

<?php
box(1);
foot();
?>