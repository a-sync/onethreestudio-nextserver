<?php
require('functions.php');

head();

menu();
?>

<?php
if($_GET['action'] == 'edit' && isset($_GET['iid'])) {
  if($_POST['action'] == 'edit' && $_GET['iid'] == $_POST['iid']) {
    $infoid = esc($_POST['iid']);
    $title = esc($_POST['title']);
    $text = esc($_POST['text']);
    $status = esc($_POST['status']);
    $date = esc($_POST['date']);

    if($title == '' || $text == '' || $status == '') { $status = 0; }

		$query = "UPDATE ots_info SET title = '$title', text = '$text', status = '$status', date = '$date' WHERE iid = '$infoid'";
    mysql_query($query);

    redir('info.php');
  }
  else {
    if(isset($_POST['iid'])) { die('Kritikus hiba, k�l�nb�z� GET �s POST iid!'); }
    $infoid = esc($_GET['iid']);
    $query = "SELECT * FROM ots_info WHERE iid = '$infoid'";
    $queryresult = mysql_query($query);
    $resultnum = mysql_num_rows($queryresult);

    if($resultnum < 1) { die('Hib�s iid!'); }
    if($resultnum > 1) { die('Adatb�zis hiba, t�bbsz�r�s iid!'); }
    $data = mysql_fetch_array($queryresult, MYSQL_ASSOC);
?>
    <form method="post">
    C�m: <input type="text" name="title" value="<?php echo tag_esc($data['title']); ?>"> :: St�tusz/Priorit�s: <input type="text" name="status" value="<?php echo $data['status']; ?>">
    <br>
    Sz�veg: <textarea name="text"><?php echo tag_esc($data['text']); ?></textarea>
    <br>
    D�tum: <input type="text" name="date" value="<?php echo $data['date']; ?>">
    <br>
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="iid" value="<?php echo $data['iid']; ?>">
    <input type="submit" value="Ment�s">
    </form>
<?php
    if($data['title'] == '' || $data['text'] == '') { echo '<br>Legal�bb c�m �s sz�veg legyen megadva k�l�nben a st�tusz �rt�ke 0 lesz alap�rtelmezetten.'; }
    if($data['status'] == '' || $data['status'] == 0) { echo '<br>A st�tusz �rt�ke 0, �gy ez a bejegyz�s nem fog megjelenni.'; }
  }
}
elseif($_GET['action'] == 'delete' && isset($_GET['iid'])) {
  if($_POST['action'] == 'delete' && $_GET['iid'] == $_POST['iid']) {
    $infoid = esc($_POST['iid']);
    $query = "DELETE FROM ots_info WHERE iid = '$infoid'";
    mysql_query($query);

    redir('info.php');
  }
  else {
    if(isset($_POST['iid'])) { die('Kritikus hiba, k�l�nb�z� GET �s POST iid!'); }
    $infoid = esc($_GET['iid']);
    $query = "SELECT iid, title FROM ots_info WHERE iid = '$infoid'";
    $queryresult = mysql_query($query);
    $resultnum = mysql_num_rows($queryresult);

    if($resultnum < 1) { die('Hib�s iid!'); }
    if($resultnum > 1) { die('Adatb�zis hiba, t�bbsz�r�s iid!'); }
    $data = mysql_fetch_array($queryresult, MYSQL_ASSOC);
?>
    <form method="post">
    Biztosan t�rl�d ezt a bejegyz�st?<br>
    <i><?php echo $data['title']; ?></i>
    <br>
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="iid" value="<?php echo $data['iid']; ?>">
    <input type="submit" value="Igen"><input type="button" OnClick="document.location='info.php'" value="Nem">
    </form>
<?php
  }
}
elseif($_GET['action'] == 'add') {

  if($_POST['action'] == 'add') {
    $title = esc($_POST['title']);
    $text = esc($_POST['text']);
    $status = esc($_POST['status']);

    if($title == '' || $text == '' || $status == '') { $status = 0; }

    $date = date('Y-m-d H:i:s');

    $query = "INSERT INTO ots_info VALUES (NULL, '$title', '$text', '$status', '$date')";
		mysql_query($query);

    redir('info.php');
  }
  else {
?>
    <form method="post">
    C�m: <input type="text" name="title"> :: St�tusz/Priorit�s: <input type="text" name="status" value="0">
    <br>
    Sz�veg: <textarea name="text"></textarea>
    <br>
    <input type="hidden" name="action" value="add">
    <input type="submit" value="Ment�s">
    </form>
    <br>Legal�bb c�m �s sz�veg legyen megadva k�l�nben a st�tusz �rt�ke 0 lesz alap�rtelmezetten.
    <br>A st�tusz �rt�ke 0, �gy ez a bejegyz�s nem fog megjelenni.
<?php
  }
}
else {
  if(isset($_GET['action']) || isset($_GET['iid'])) { echo '<b style="color: #f00; text-align: center;">H�nyzik vagy hib�s valamelyik �rt�k!</b><br><br>'; }
?>
  Portfolio info bejegyz�sek:
  <br><br>
  <a href="info.php?action=add">�j bejegyz�s</a>
  <br><br>

<?php
  $query = "SELECT iid, title, status, date FROM ots_info ORDER BY status DESC";

  $queryresult = mysql_query($query);
  $resultnum = mysql_num_rows($queryresult);

  if($resultnum < 1) { echo 'Nincsenek Portfolio info bejegyz�sek'; }
  else {
    for($i = 0; $resultnum > $i; $i++) {
      $a = mysql_fetch_array($queryresult, MYSQL_ASSOC);

      echo stripslashes('ID: <b>'.$a['iid'].'</b> C�m: <b>'.$a['title'].'</b> St�tusz/Priorit�s: <b>'.$a['status'].'</b> D�tum: <b>'.$a['date'].'</b> :: <a href="info.php?action=edit&iid='.$a['iid'].'">Szerkeszt</a> :: <a href="info.php?action=delete&iid='.$a['iid'].'">T�r�l</a><br>');
    }
  }
}

foot();
?>