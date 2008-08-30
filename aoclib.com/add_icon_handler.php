<?php
/* ADD_ICON_HANDLER.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 20)) { redir('index.php'); }//log

  $title = esc(makeTitle($_POST['title']), 1);
  if($title == '') { redir('add_icon.php?error=15'); }

  $perma = makePermalink($_POST['perma']);
  if($perma == '') { $perma = makePermalink($title); }

  $allowed_ext[] = '.jpg';
  $allowed_ext[] = '.jpeg';
  $allowed_ext[] = '.png';
  $allowed_image_type[] = 'IMAGETYPE_JPEG';
  $allowed_image_type[] = 'IMAGETYPE_PNG';

  $max_f_size = 8 * 1024 * 1024 * 1;//1MB
  $e = 0;
  $f = $_FILES['icon'];

  if($f['error'] == 0) {
    if(is_uploaded_file($f['tmp_name']) && $f['size'] > 0 && $f['size'] <= $max_f_size) {
      $ext = strtolower(substr(strrchr(basename($f['name']), '.'), 0));

      if(in_array($ext, $allowed_ext) && in_array(imagetype($f['tmp_name']), $allowed_image_type)) {

        if($ext = '.jpeg') { $ext = '.jpg'; }
        $fname = $perma.$ext;

        if(file_usable('pictures/icons/'.$fname, false, false, false)) {
          $taken = 1;
          while($taken != 0) {
            $fname = $perma.'-'.$taken.$ext;
            if(file_usable('pictures/icons/'.$fname, false, false, false)) { $taken++; }
            else { $taken = 0; }
          }
        }

        if(/*@*/move_uploaded_file($f['tmp_name'], 'pictures/icons/'.$fname)) {
          //if(/*@*/rename($f['tmp_name'], 'pictures/'.$perma)) {

          $query1 = "INSERT INTO `rpl_pictures` (`piid`, `title`, `perma`, `type`) VALUES (NULL, '$title', '$fname', '0')";
          $this_piid = sql_query($query1, true);

          if($this_piid !== false) {
            //makeThumbnail('pictures/icons/' . $perma, 'pictures/thumbnails/' . $this_piid . '.jpg', 120, 120, 80);//log //ha false
          }//ha sikerül
          else { $e = 14; }//log //fájlt törölni
        }//ha sikerült elmozgatni a helyére
        else { $e = 13; }//log
      }//ha engedélyezett kiterjesztésû / típusú
      else { $e = 12; }

    }//létezik a feltöltött fájl és a mérete nagyobb mint 0 és kisebb mint $max_f_size
    else {
      if($f['size'] <= 0) { $e = 9; }
      elseif($f['size'] > $max_f_size) { $e = 10; }//log
      else { $e = 11; }//log
    }
  }//ha error == 0
  else { $e = $f['error']; }

  //ha végzett, átirányitás a cikkre / hibára
  if($e != 0) { redir('add_icon.php?error='.$e); }
  else { redir('mod_icon.php?id='.$this_piid); }
?>