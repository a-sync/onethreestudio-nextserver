<?php
//todo: fájlnév megadható
//todo: névszerint sorbarendezés
//todo: nem kilistázandó fájlok megadhatók
require('functions.php');

head();

function filesLists() {
  include('config.php');//fix

  $date = 'Y.m.d - H:i';

  for($i = 0; $i < count($workDirRoot); $i++) {
    echo '<div style="float: left; padding: 10px 20px;"><b><u>'.$workDirName[$i].':</u></b><br />';
    $workDirList = dirList($workDirRoot[$i]);
    for($j = 0; $j < count($workDirList); $j++) {
      $theFile = $workDirList[$j];
      if($theFile['type'] == 'file') {
        echo '<a href="'.$workDirLink[$i].$theFile['name'].'" style="cursor: default;" title="'.fs($theFile['size']).' :: '.date($date, $theFile['modded']).'">'.$theFile['name'].'</a> <a style="color: #f00; font-weight: bold; text-decoration: none;" href="upload.php?delfile='.$theFile['name'].'&deldir='.$i.'" title="Törlés">x</a><br />';
      }
    }
    echo '</div>';
  }
}

function filesListsRadio() {
  include('config.php');//fix

  $date = 'Y.m.d - H:i';

  for($i = 0; $i < count($workDirRoot); $i++) {
    echo '<div style="float: left; padding: 10px 20px;"><b><u>'.$workDirName[$i].':</u></b><br />';
    $workDirList = dirList($workDirRoot[$i]);
    for($j = 0; $j < count($workDirList); $j++) {
      $theFile = $workDirList[$j];
      if($theFile['type'] == 'file') {
        echo '<input type="radio" name="fileLink" value="'.$workDirLink[$i].$theFile['name'].'"><a href="'.$workDirLink[$i].$theFile['name'].'" style="cursor: default;" title="'.fs($theFile['size']).' :: '.date($date, $theFile['modded']).'">'.$theFile['name'].'</a><br />';
      }
    }
    echo '</div>';
  }
}

if($_FILES['thefile']['tmp_name'] && $_POST['thedir'] != '') {
	$theDir = $workDirRoot[$_POST['thedir']];

  if(file_exists($theDir.basename($_FILES['thefile']['name']))) {
    redir('upload.php?msg=add_exist');
  }
  elseif(move_uploaded_file($_FILES['thefile']['tmp_name'], $theDir.basename($_FILES['thefile']['name']))) {
    redir('upload.php?msg=add_success');
  }
  else {
    redir('upload.php?msg=add_fail&chmod='.$theDir);
  }
}
elseif($_GET['delfile'] && $_GET['deldir'] != '') {
  if($_POST['delfile'] && $_POST['deldir'] != '') {
    $delDir = $workDirRoot[$_POST['deldir']];

    if(file_exists($delDir.$_POST['delfile'])) {
      if(!unlink($delDir.$_POST['delfile'])) {
        redir('upload.php?msg=del_fail&chmod='.$delDir);
      }
    }
    else {
      redir('upload.php?msg=del_notexist');
    }
    redir('upload.php?msg=del_success');
  }
  else {
    $delDir = $workDirRoot[$_GET['deldir']];

    if(file_exists($delDir.$_GET['delfile'])) {
    menu();
    filesLists();
?>

      <div style="clear: both; padding: 30px 20px;">
        Biztosan törlöd ezt a fájlt?
        <form method="post">
          <i><?php echo $workDirLink[$_GET['deldir']].'<b>'.$_GET['delfile'].'</b>'; ?></i>
          <br />
          <input type="hidden" name="delfile" value="<?php echo $_GET['delfile']; ?>">
          <input type="hidden" name="deldir" value="<?php echo $_GET['deldir']; ?>">
          <input type="submit" value="Igen"><input type="button" OnClick="document.location='upload.php'" value="Nem">
        </form>
      </div>

<?php
    }
    else {
      redir('upload.php?msg=del_notexist');
    }
  }
}
elseif($_GET['add'] && $_GET['rid']) {
?>
  <div style="clear: both; padding: 0px 20px;">
    Kiválasztott fájl küldése:
  </div>
  <form action="references.php?action=edit&add=<?php echo $_GET['add']; ?>&rid=<?php echo $_GET['rid']; ?>" method="post">
    <?php filesListsRadio(); ?>
    <div style="clear: both; padding: 30px 20px;">
      <input type="submit" value="Mehet">
    </div>
  </form>
<?php
}
else {
  menu();

  filesLists();
?>

  <div style="clear: both; padding: 30px 0px 0px 20px;">
    Fájlfeltöltés:
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <input type="file" name="thefile">
      <select name="thedir">
<?php
        for($i = 0; $i < count($workDirRoot); $i++) {
          echo '<option value="'.$i.'">'.$workDirName[$i].'</option>';
        }
?>
      </select>
      <br />
      <input type="submit" value="Mehet">
    </form>
<?php
  if($_GET['msg'] == 'add_success') { echo '<b style="color: #f00; text-align: center;">A fájl sikeresen fel lett töltve...</b>'; }
  elseif($_GET['msg'] == 'add_exist') { echo '<b style="color: #f00; text-align: center;">A fájlnév foglalt. Nevezd át.</b>'; }
  elseif($_GET['msg'] == 'add_fail') { echo '<b style="color: #f00; text-align: center;">A fájl feltöltése sikertelen mert a célmappa nem írható. Állíts CHMOD 777-et a(z) '.$_GET['chmod'].' mappán.</b>'; }
  elseif($_GET['msg'] == 'del_success') { echo '<b style="color: #f00; text-align: center;">A fájl sikeresen törölve lett...</b>'; }
  elseif($_GET['msg'] == 'del_notexist') { echo '<b style="color: #f00; text-align: center;">A törölni kívánt fájl nem létezik, vagy az elérési út helytelen.</b>'; }
  elseif($_GET['msg'] == 'del_fail') { echo '<b style="color: #f00; text-align: center;">A fájl törlése sikertelen mert a célmappa nem módosítható. Állíts CHMOD 777-et a(z) '.$_GET['chmod'].' mappán.</b>'; }
?>
  </div>
<?php
}
foot();
?>