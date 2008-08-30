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
    Gyors inf� megnevez�se*:
    <br/>
    <input type="text" name="title" maxlength="200"/>
    <br/>
    <br/>
    <br/>

    Gyors inf� c�me*:
    <br/>
    (cikkben, list�z�ban)
    <br/>
    <input type="text" name="description_title" maxlength="200"/>
    <br/>
    <br/>

    �rt�k el�tti / ut�ni sz�veg:
    <br/>
    (az inf�nak megadott �rt�k el� �s/vagy ut�n ki�rand� sz�veg)
    <br/>
    El�tte: <input type="text" name="before_value" maxlength="200"/>
    <br/>
    Ut�na: <input type="text" name="after_value" maxlength="200"/>
    <br/>
    <br/>
    <br/>

    Alap �rt�k:
    <br/>
    <input type="text" name="default" maxlength="200"/>
    <br/>
    <br/>

    Kit�lt�si seg�ts�g:
    <br/>
    (le�r�s a gyors inf� mez�h�z, adat kit�lt�skor) (max. 200 karakter)
    <br/>
    <textarea name="help" maxlength="200"></textarea>
    <br/>
    <br/>

    Foglalt hely oszlopk�nt:
    <br/>
    (ha nulla, akkor csak a le�r�sban jelenik meg)
    <br/>
    <input type="text" name="width" maxlength="10" value="0"/>px
    <br/>
    <br/>

    Adat t�pusa:
    <select name="input_type">
      <option value="0" selected>Egysoros</option>
      <option value="1">T�bbsoros</option>
    </select>
    <br/>
    <br/>

    �rt�k tartalma:
    <select name="type">
      <option value="0" selected>Sz�veg</option>
      <option value="1">Sz�m</option>
    </select>
    <br/>
    <br/>

    <input type="submit" value="Mehet">
  </form>

<?php
box(1);
foot();
?>