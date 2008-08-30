<?php
if(!defined('_OTS_ADMIN_')) { header("Location: ../admin.php"); exit; } // ha nincs bejelentkezve (nem admin)

$categories[0]['name'] = '(Kategorizálatlan)';
$categories[0]['cat_id'] = 0;
$cats_result = mysql_query("SELECT * FROM `galeria` ORDER BY `name` ASC");
while($cat = mysql_fetch_assoc($cats_result)) {
  $categories[$cat['cat_id']] = $cat;
}

if($_POST['add_pic']) {//kép  hozzáadása
//echo'<pre>';print_r($_FILES);echo'</pre>';exit;//debug
  $f = $_FILES['upload'];
  if($_POST['file'] == '') {
    if(is_uploaded_file($f['tmp_name']) && $f['size'] > 0) {
      $file = $gal['mappa'].basename($f['name']);
      
      //meglevo fajlt ne irja felul
      if(file_exists($file)) {
        $file_a = explode('.', $file);
        $f_ext = array_pop($file_a);
        $f_name = implode('.', $file_a);
        $i = 1;
        $file = $f_name.'-'.$i.'.'.$f_ext;
        
        while(file_exists($file)) {
          $i++;
          $file = $f_name.'-'.$i.'.'.$f_ext;
        }
      }

      list($fw, $fh) = getimagesize($f['tmp_name']);
      if($fw > 1000 || $fh > 1000) {
        $rf = makeThumbnail($f['tmp_name'], $file, 1000, 1000);
      }
      if($rf != true) {
        move_uploaded_file($f['tmp_name'], $file);
      }
      chmod($file, 0644);
    }
  }
  else {
    $file = $gal['mappa'].mysql_real_escape_string($_POST['file']);
  }
  
  $filename = basename($file);
  
  if(file_exists($file)) {
    $mt = makeThumbnail($file, $gal['thumb'].$filename);
    chmod($gal['thumb'].$filename, 0644);

    if($_POST['pic_cat'] != 0 && !$categories[$_POST['pic_cat']]) {
      $error .= ' Nemlétezik a kiválasztott kategória';
      $pic_cat = 0;
    }
    else $pic_cat = mysql_real_escape_string($_POST['pic_cat']);
    if(trim($_POST['title']) == '') {
      $error .= ' Nincs cím megadva.';
      $title = $filename;
    }
    else $title = trim(mysql_real_escape_string($_POST['title']));
    $desc = mysql_real_escape_string($_POST['desc']);

    $tags_a = explode(',', mysql_real_escape_string($_POST['tags']));
    $tags = ',';
    foreach($tags_a as $tag) {
      $tag = preg_replace('/\s\s+/', ' ', trim($tag));//duplaspace és elejéről végéről takarítás
      if($tag != '') { $tags .=  mysql_real_escape_string($tag).','; }
    }

    mysql_query("INSERT INTO `kepek` (`pic_id`, `cat_id`, `title`, `desc`, `tags`, `file`) VALUES (NULL, '$pic_cat', '$title', '$desc', '$tags', '$filename')");
    
    if(!$mt) $error .= ' Nemsikerült thumbnailt létrehozni. Másolj egyet fel kézileg ide: '.$gal['thumb'].$filename;
    else { header("Location: admin.php?pic=".mysql_insert_id()); exit; }
  }
  else {
    $error .= 'Nincs ilyen fájl, vagy nemsikerült a feltöltés!';
  }

}
elseif($_POST['pic_mod']) {//kép módosítása
  $pic_id = mysql_real_escape_string($_POST['pic_id']);
  $result = mysql_query("SELECT * FROM `kepek` WHERE `pic_id` = '$pic_id'");
  
  if(@mysql_num_rows($result) > 0)
  {
    $pic = mysql_fetch_assoc($result);
    
    
    $f = $_FILES['upload'];
    if($_POST['file'] == '') {
      if(is_uploaded_file($f['tmp_name']) && $f['size'] > 0) {
        
        // előző fájlok törlése
        unlink($gal['mappa'].$pic['file']);
        unlink($gal['thumb'].$pic['file']);
        
        $file = $gal['mappa'].basename($f['name']);
        
        //meglévő fájlt ne írja felül
        if(file_exists($file)) {
          $file_a = explode('.', $file);
          $f_ext = array_pop($file_a);
          $f_name = implode('.', $file_a);
          $i = 1;
          $file = $f_name.'-'.$i.'.'.$f_ext;
          
          while(file_exists($file)) {
            $i++;
            $file = $f_name.'-'.$i.'.'.$f_ext;
          }
        }
        
        list($fw, $fh) = getimagesize($f['tmp_name']);
        if($fw > 1000 || $fh > 1000) {
          $rf = makeThumbnail($f['tmp_name'], $file, 1000, 1000);
        }
        if($rf != true) {
          move_uploaded_file($f['tmp_name'], $file);
        }
        
        chmod($file, 0644);
      }
    }
    else {
      if($pic['file'] != $_POST['file']) {
        // előző fájlok törlése
        unlink($gal['mappa'].$pic['file']);
        unlink($gal['thumb'].$pic['file']);
      }
    
      $file = $gal['mappa'].mysql_real_escape_string($_POST['file']);
    }

    $filename = basename($file);
  
    if(file_exists($file)) {
      if($pic['file'] != $_POST['file']) {
        $mt = makeThumbnail($file, $gal['thumb'].$filename);
      }
      else $mt = true;
      chmod($gal['thumb'].$filename, 0644);

      if($_POST['pic_cat'] != 0 && !$categories[$_POST['pic_cat']]) {
        $error .= ' Nemlétezik a kiválasztott kategória';
        $pic_cat = 0;
      }
      else $pic_cat = mysql_real_escape_string($_POST['pic_cat']);
      if(trim($_POST['title']) == '') {
        $error .= ' Nincs cím megadva.';
        $title = $filename;
      }
      else $title = trim(mysql_real_escape_string($_POST['title']));
      $desc = mysql_real_escape_string($_POST['desc']);

      $tags_a = explode(',', mysql_real_escape_string($_POST['tags']));
      $tags = ',';
      foreach($tags_a as $tag) {
        $tag = preg_replace('/\s\s+/', ' ', trim($tag));//duplaspace és elejéről végéről takarítás
        if($tag != '') { $tags .=  mysql_real_escape_string($tag).','; }
      }

      mysql_query("UPDATE `kepek` SET `cat_id` = '$pic_cat', `title` = '$title', `desc` = '$desc', `tags` = '$tags', `file` = '$filename' WHERE `pic_id` = '$pic_id'");
      
      if(!$mt) $error .= ' Nemsikerült thumbnailt létrehozni. Másolj egyet fel kézileg ide: '.$gal['thumb'].$filename;
    }
    else {
      $error .= 'Nincs ilyen fájl, vagy nemsikerült a feltöltés!';
    }
  }
  else {
    $error .= ' Nincs ilyen kép az adatbázisban, ezért nemlehet módosítani.';//wut?.. mindegy..
  }
  
}
elseif($_POST['pic_del']) {//kép törlése
  $pic_id = mysql_real_escape_string($_POST['pic_id']);
  $result = mysql_query("SELECT * FROM `kepek` WHERE `pic_id` = '$pic_id'");
  
  if(@mysql_num_rows($result) > 0)
  {
    $pic = mysql_fetch_assoc($result);

    // fájlok törlése
    unlink($gal['mappa'].$pic['file']);
    unlink($gal['thumb'].$pic['file']);

    // adatbázisból törlés
    mysql_query("DELETE FROM `kepek` WHERE `pic_id` = '$pic_id'");
    header("Location: admin.php?a=pictures"); exit;
  }
  else {
    $error .= ' Nincs ilyen kép az adatbázisban, ezért nemlehet törölni.';//wut?.. mindegy..
  }
}

if(is_numeric($_GET['pic'])) {
  $pic_id = mysql_real_escape_string($_GET['pic']);
  $result = mysql_query("SELECT * FROM `kepek` WHERE `pic_id` = '$pic_id'");
}

?>