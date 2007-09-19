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
    if(isset($_POST['iid'])) { die('Kritikus hiba, különbözõ GET és POST iid!'); }
    $infoid = esc($_GET['iid']);
    $query = "SELECT * FROM ots_info WHERE iid = '$infoid'";
    $queryresult = mysql_query($query);
    $resultnum = mysql_num_rows($queryresult);

    if($resultnum < 1) { die('Hibás iid!'); }
    if($resultnum > 1) { die('Adatbázis hiba, többszörös iid!'); }
    $data = mysql_fetch_array($queryresult, MYSQL_ASSOC);
?>
    <form method="post">
    Cím: <input type="text" name="title" value="<?php echo tag_esc($data['title']); ?>"> :: Státusz/Prioritás: <input type="text" name="status" value="<?php echo $data['status']; ?>">
    <br>
    Szöveg: <textarea name="text"><?php echo tag_esc($data['text']); ?></textarea>
    <br>
    Dátum: <input type="text" name="date" value="<?php echo $data['date']; ?>">
    <br>
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="iid" value="<?php echo $data['iid']; ?>">
    <input type="submit" value="Mentés">
    </form>
<?php
    if($data['title'] == '' || $data['text'] == '') { echo '<br>Legalább cím és szöveg legyen megadva különben a státusz értéke 0 lesz alapértelmezetten.'; }
    if($data['status'] == '' || $data['status'] == 0) { echo '<br>A státusz értéke 0, így ez a bejegyzés nem fog megjelenni.'; }
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
    if(isset($_POST['iid'])) { die('Kritikus hiba, különbözõ GET és POST iid!'); }
    $infoid = esc($_GET['iid']);
    $query = "SELECT iid, title FROM ots_info WHERE iid = '$infoid'";
    $queryresult = mysql_query($query);
    $resultnum = mysql_num_rows($queryresult);

    if($resultnum < 1) { die('Hibás iid!'); }
    if($resultnum > 1) { die('Adatbázis hiba, többszörös iid!'); }
    $data = mysql_fetch_array($queryresult, MYSQL_ASSOC);
?>
    <form method="post">
    Biztosan törlöd ezt a bejegyzést?<br>
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
    Cím: <input type="text" name="title"> :: Státusz/Prioritás: <input type="text" name="status" value="0">
    <br>
    Szöveg: <textarea name="text"></textarea>
    <br>
    <input type="hidden" name="action" value="add">
    <input type="submit" value="Mentés">
    </form>
    <br>Legalább cím és szöveg legyen megadva különben a státusz értéke 0 lesz alapértelmezetten.
    <br>A státusz értéke 0, így ez a bejegyzés nem fog megjelenni.
<?php
  }
}
else {
  if(isset($_GET['action']) || isset($_GET['iid'])) { echo '<b style="color: #f00; text-align: center;">Hányzik vagy hibás valamelyik érték!</b><br><br>'; }
?>
  Portfolio info bejegyzések:
  <br><br>
  <a href="info.php?action=add">Új bejegyzés</a>
  <br><br>

<?php
  $query = "SELECT iid, title, status, date FROM ots_info ORDER BY status DESC";

  $queryresult = mysql_query($query);
  $resultnum = mysql_num_rows($queryresult);

  if($resultnum < 1) { echo 'Nincsenek Portfolio info bejegyzések'; }
  else {
    for($i = 0; $resultnum > $i; $i++) {
      $a = mysql_fetch_array($queryresult, MYSQL_ASSOC);

      echo stripslashes('ID: <b>'.$a['iid'].'</b> Cím: <b>'.$a['title'].'</b> Státusz/Prioritás: <b>'.$a['status'].'</b> Dátum: <b>'.$a['date'].'</b> :: <a href="info.php?action=edit&iid='.$a['iid'].'">Szerkeszt</a> :: <a href="info.php?action=delete&iid='.$a['iid'].'">Töröl</a><br>');
    }
  }
}

foot();
?>