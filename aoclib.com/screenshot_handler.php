<?php
/* SCREENSHOT_HANDLER.PHP */

include('functions.php');

timeout(1);//biztons�gi okokb�l

$_USER = checkLogin(true);//log //ha false

$ARTICLE = getArtInfo($_POST['id']);

if($ARTICLE !== false && $_GET['id'] == $_POST['id']) {

  $allowed_ext[] = '.jpg';
  $allowed_ext[] = '.jpeg';
  $allowed_ext[] = '.png';
  $allowed_image_type[] = 'IMAGETYPE_JPEG';
  $allowed_image_type[] = 'IMAGETYPE_PNG';

  $max_f_size = 8 * 1024 * 1024 * 2;//2MB
  $e = 0;
  $f = $_FILES['screenshot'];

  if($f['error'] == 0) {
    if(is_uploaded_file($f['tmp_name']) && $f['size'] > 0 && $f['size'] <= $max_f_size) {
      $ext = strtolower(substr(strrchr(basename($f['name']), '.'), 0));

      if(in_array($ext, $allowed_ext) && in_array(imagetype($f['tmp_name']), $allowed_image_type)) {
        $title = ($_POST['title'] == '') ? substr($f['name'], 0, (0 - strlen($ext))) : makeTitle($_POST['title'], 50);

        if($ext = '.jpeg') { $ext = '.jpg'; }
        $perma = $ARTICLE['arid'].'-'.makePermalink($title).$ext;

        if(file_usable('pictures/'.$perma, false, false, false)) {
          $taken = 1;
          while($taken != 0) {
            $perma = $ARTICLE['arid'].'-'.makePermalink($title).'-'.$taken.$ext;
            if(file_usable('pictures/'.$perma, false, false, false)) { $taken++; }
            else { $taken = 0; }
          }
        }

        $title = esc($title, 1);

        if(/*@*/move_uploaded_file($f['tmp_name'], 'pictures/'.$perma)) {
          //if(/*@*/rename($f['tmp_name'], 'pictures/'.$perma)) {

          $query1 = "INSERT INTO `rpl_pictures` (`piid`, `title`, `perma`, `type`, `shown`, `arid`) VALUES (NULL, '$title', '$perma', '1', '2', '{$ARTICLE['arid']}')";
          $this_piid = sql_query($query1, true);

          if($this_piid !== false) {
            makeThumbnail('pictures/' . $perma, 'pictures/thumbnails/' . $this_piid . '.jpg', 120, 120, 80);//log //ha false
          }//ha siker�l
          else { $e = 14; }//log //f�jlt t�r�lni
        }//ha siker�lt elmozgatni a hely�re
        else { $e = 13; }//log
      }//ha enged�lyezett kiterjeszt�s� / t�pus�
      else { $e = 12; }

    }//l�tezik a felt�lt�tt f�jl �s a m�rete nagyobb mint 0 �s kisebb mint $max_f_size
    else {
      if($f['size'] <= 0) { $e = 9; }
      elseif($f['size'] > $max_f_size) { $e = 10; }//log
      else { $e = 11; }//log
    }
  }//ha error == 0
  else { $e = $f['error']; }

  //ha v�gzett, �tir�nyit�s a cikkre / hib�ra
  if($e != 0) { redir('article/'.$ARTICLE['perma'].'&tab=the_screenshots&error='.$e.'#tabs'); }
  else { redir('article/'.$ARTICLE['perma'].'&tab=the_screenshots#tabs'); }
}
else { redir('index.php'); }//log
?>