<?php
require('functions.php');

head();

menu();
?>

<?php
if($_GET['action'] == 'edit' && isset($_GET['rid'])) {
  if($_POST['action'] == 'edit' && $_GET['rid'] == $_POST['rid']) {
    if($_POST['add_crop'] == '+') { $add = 'crop'; }
    elseif($_POST['add_full'] == '+') { $add = 'full'; }
    elseif($_POST['add_download'] == '+') { $add = 'download'; }
    else { $add = 'no'; }

    $referenceid = esc($_POST['rid']);
    $crop_pic = esc($_POST['crop_pic']);
    $full_pic = esc($_POST['full_pic']);
    $pic_title = esc($_POST['pic_title']);
    $pic_text = esc($_POST['pic_text']);
    $link = esc($_POST['link']);
    $download = esc($_POST['download']);
    $status = esc($_POST['status']);

    if($crop_pic == '' || $full_pic == '' || $pic_title == '' || $status == '') { $status = 0; }
    if($pic_text == '') { $pic_text = $pic_title; }

		$query = "UPDATE ots_references SET crop_pic = '$crop_pic', full_pic = '$full_pic', pic_title = '$pic_title', pic_text = '$pic_text', link = '$link', download = '$download', status = '$status' WHERE rid = '$referenceid'";
    mysql_query($query);

    if($add == 'no') { redir('references.php'); }
    else { redir('upload.php?add='.$add.'&rid='.$referenceid); }
  }
  else {
    if(isset($_POST['rid'])) { die('Kritikus hiba, különbözõ GET és POST rid!'); }
    $referenceid = esc($_GET['rid']);
    $query = "SELECT * FROM ots_references WHERE rid = '$referenceid'";
    $queryresult = mysql_query($query);
    $resultnum = mysql_num_rows($queryresult);

    if($resultnum < 1) { die('Hibás rid!'); }
    if($resultnum > 1) { die('Adatbázis hiba, többszörös rid!'); }
    $data = mysql_fetch_array($queryresult, MYSQL_ASSOC);
?>
    <form method="post">
    Képszelet: (700x100) <input type="text" name="crop_pic" value="<?php if($_POST['fileLink']!=''&&$_GET['add']=='crop'){ echo tag_esc($_POST['fileLink']); }else{ echo tag_esc($data['crop_pic']); } ?>"><input type="submit" name="add_crop" value="+">
    <br>
    Teljes kép: <input type="text" name="full_pic" value="<?php if($_POST['fileLink']!=''&&$_GET['add']=='full'){ echo tag_esc($_POST['fileLink']); }else{ echo tag_esc($data['full_pic']); } ?>"><input type="submit" name="add_full" value="+">
    <br><br>
    Kép cím: <input type="text" name="pic_title" value="<?php echo tag_esc($data['pic_title']); ?>">
    <br>
    Kép leírás: <input type="text" name="pic_text" value="<?php echo tag_esc($data['pic_text']); ?>">
    <br><br>
    Link: <input type="text" name="link" value="<?php echo tag_esc($data['link']); ?>">
    <br>
    Letöltés: <input type="text" name="download" value="<?php if($_POST['fileLink']!=''&&$_GET['add']=='download'){ echo tag_esc($_POST['fileLink']); }else{ echo tag_esc($data['download']); } ?>"><input type="submit" name="add_download" value="+">
    <br>
    Státusz/Prioritás: <input type="text" name="status" value="<?php echo $data['status']; ?>">
    <br>
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="rid" value="<?php echo $data['rid']; ?>">
    <input type="submit" value="Mentés">
    </form>
<?php
    if($data['crop_pic'] == '' || $data['full_pic'] == '' || $data['pic_title'] == '') { echo '<br>Legalább a képszelet, a teljes kép és a kép címe legyen megadva különben a státusz értéke 0 lesz alapértelmezetten.'; }
    if($data['status'] == '' || $data['status'] == 0) { echo '<br>A státusz értéke 0, így ez a bejegyzés nem fog megjelenni.'; }
  }
}
elseif($_GET['action'] == 'delete' && isset($_GET['rid'])) {
  if($_POST['action'] == 'delete' && $_GET['rid'] == $_POST['rid']) {
    $referenceid = esc($_POST['rid']);
    $query = "DELETE FROM ots_references WHERE rid = '$referenceid'";
    mysql_query($query);

    redir('references.php');
  }
  else {
    if(isset($_POST['rid'])) { die('Kritikus hiba, különbözõ GET és POST rid!'); }
    $referenceid = esc($_GET['rid']);
    $query = "SELECT rid, pic_title FROM ots_references WHERE rid = '$referenceid'";
    $queryresult = mysql_query($query);
    $resultnum = mysql_num_rows($queryresult);

    if($resultnum < 1) { die('Hibás rid!'); }
    if($resultnum > 1) { die('Adatbázis hiba, többszörös rid!'); }
    $data = mysql_fetch_array($queryresult, MYSQL_ASSOC);

    if(mb_strlen($data['pic_title']) > 35) { $pic_title = substr($data['pic_title'], 0, 32).'...'; }
    else { $pic_title = $data['pic_title']; }
?>
    <form method="post">
    Biztosan törlöd ezt a bejegyzést?<br>
    <i><?php echo $pic_title; ?></i>
    <br>
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="rid" value="<?php echo $data['rid']; ?>">
    <input type="submit" value="Igen"><input type="button" OnClick="document.location='references.php'" value="Nem">
    </form>
<?php
  }
}
elseif($_GET['action'] == 'add') {

  if($_POST['action'] == 'add') {
    if($_POST['add_crop'] == '+') { $add = 'crop'; }
    elseif($_POST['add_full'] == '+') { $add = 'full'; }
    elseif($_POST['add_download'] == '+') { $add = 'download'; }
    else { $add = 'no'; }

    $crop_pic = esc($_POST['crop_pic']);
    $full_pic = esc($_POST['full_pic']);
    $pic_title = esc($_POST['pic_title']);
    $pic_text = esc($_POST['pic_text']);
    $link = esc($_POST['link']);
    $download = esc($_POST['download']);
    $status = esc($_POST['status']);

    if($crop_pic == '' || $full_pic == '' || $pic_title == '' || $status == '') { $status = 0; }
    if($pic_text == '') { $pic_text = $pic_title; }

    $query = "INSERT INTO ots_references VALUES (NULL, '$crop_pic', '$full_pic', '$pic_title', '$pic_text', '$link', '$download', '$status')";
		mysql_query($query);
    $rid = mysql_insert_id();

    if($add == 'no') { redir('references.php'); }
    else { redir('upload.php?add='.$add.'&rid='.$rid); }
  }
  else {
?>
    <form method="post">
    Képszelet: (700x100) <input type="text" name="crop_pic"><input type="submit" name="add_crop" value="+">
    <br>
    Teljes kép: <input type="text" name="full_pic"><input type="submit" name="add_full" value="+">
    <br><br>
    Kép cím: <input type="text" name="pic_title">
    <br>
    Kép leírás: <input type="text" name="pic_text">
    <br><br>
    Link: <input type="text" name="link">
    <br>
    Letöltés: <input type="text" name="download"><input type="submit" name="add_download" value="+">
    <br>
    Státusz/Prioritás: <input type="text" name="status" value="0">
    <br>
    <input type="hidden" name="action" value="add">
    <input type="submit" value="Mentés">
    </form>
    <br>Legalább a képszelet, a teljes kép és a kép címe legyen megadva különben a státusz értéke 0 lesz alapértelmezetten.
    <br>A státusz értéke 0, így ez a bejegyzés nem fog megjelenni.
<?php
  }
}
else {
  if(isset($_GET['action']) || isset($_GET['rid'])) { echo '<b style="color: #f00; text-align: center;">Hányzik vagy hibás valamelyik érték!</b><br><br>'; }
?>
  Referencia bejegyzések:
  <br><br>
  <a href="references.php?action=add">Új bejegyzés</a>
  <br><br>

<?php
  $query = "SELECT rid, pic_title, status FROM ots_references ORDER BY status DESC";

  $queryresult = mysql_query($query);
  $resultnum = mysql_num_rows($queryresult);

  if($resultnum < 1) { echo 'Nincsenek Referencia bejegyzések'; }
  else {
    for($i = 0; $resultnum > $i; $i++) {
      $a = mysql_fetch_array($queryresult, MYSQL_ASSOC);

      if(mb_strlen($a['pic_title']) > 35) { $pic_title = substr($a['pic_title'], 0, 32).'...'; }
      else { $pic_title = $a['pic_title']; }

      echo 'ID: <b>'.$a['rid'].'</b> Kép cím: <b>'.$pic_title.'</b> Státusz/Prioritás: <b>'.$a['status'].'</b> :: <a href="references.php?action=edit&rid='.$a['rid'].'">Szerkeszt</a> :: <a href="references.php?action=delete&rid='.$a['rid'].'">Töröl</a><br>';

    }
  }
}

foot();
?>