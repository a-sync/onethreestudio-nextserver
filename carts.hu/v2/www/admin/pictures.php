<?php
if(!defined('_OTS_ADMIN_')) { header("Location: ../admin.php"); exit; } // ha nincs bejelentkezve (nem admin)

if(@mysql_num_rows($result) > 0)
{
  $pic = mysql_fetch_assoc($result);
  
  echo '<a href="'.$gal['mappa'].$pic['file'].'"><img src="'.$gal['thumb'].$pic['file'].'" alt="'.htmlspecialchars($pic['title']).'"/></a><br/><br/>';
?>

  <form action="" method="post" enctype="multipart/form-data">
  <input type="hidden" name="pic_id" value="<?php echo $pic['pic_id']; ?>"/>

  Kép címe: <input type="text" name="title" size="50" maxlength="200" value="<?php echo $pic['title']; ?>"/>
  <br/>
  <br/>
  Képfájl elérése: <?php echo $gal['mappa']; ?><input type="text" size="50" name="file" value="<?php echo $pic['file']; ?>"/>
  <br/>
  vagy egy feltöltése: <input type="file" size="35" name="upload"/> (a képfájl elérése részt töröld)
  <br/>
  <br/>
  Leírás: <br/><textarea name="desc" rows="10" cols="50"><?php echo $pic['desc']; ?></textarea>
  <br/>
  Cimkék: <input size="50" type="text" name="tags" value="<?php echo str_replace(',', ', ', substr($pic['tags'], 1, -1)); ?>"/> (vesszővel elválasztva)
  <br/>
  <br/>
  Kategória:
  <select name="pic_cat">
  <?php
  foreach($categories as $cat) {
    $sel = ($pic['cat_id'] == $cat['cat_id']) ? ' selected' : '';
    echo '<option value="'.$cat['cat_id'].'"'.$sel.'>'.htmlspecialchars($cat['name']).'</option>';
  }
  ?>
  </select>
  <br/>
  <input type="submit" name="pic_mod" value="Módosítás!"/>
  <input type="submit" name="pic_del" value="Törlés!"/>
  <br/>
  (csak .jpg vagy .png képhez készít a program magától thumbnailt)
  <br/>
  <?php echo '(a '.$gal['mappa'].' és a '.$gal['thumb'].' mappákon chmod 0777 legyen!)'; ?>
  </form>

<?php
}
else {
  $pic_result = mysql_query("SELECT * FROM `kepek` ORDER BY `cat_id` ASC, `title` ASC");
?>

  <h2 style="cursor: pointer;" onclick="le('add_pic')">Új kép hozzáadása</h2>
<?php echo '<font color="red">'.$error.'</font>'; ?>
  <form action="" method="post" enctype="multipart/form-data" id="add_pic" style="display: none;">

  Kép címe: <input type="text" name="title" size="50" maxlength="200"/>
  <br/>
  Képfájl elérése: <?php echo $gal['mappa']; ?><input type="text" size="50" name="file"/>
  <br/>
  vagy egy feltöltése: <input type="file" size="35" name="upload"/>
  <br/>
  Leírás: <br/><textarea name="desc" rows="10" cols="50"></textarea>
  <br/>
  Cimkék: <input size="50" type="text" name="tags"/> (vesszővel elválasztva)
  <br/>
  <br/>
  Kategória:
  <select name="pic_cat">
  <?php
  foreach($categories as $cat) {
    echo '<option value="'.$cat['cat_id'].'">'.htmlspecialchars($cat['name']).'</option>';
  }
  ?>
  </select>
  <br/>
  <input type="submit" name="add_pic" value="Hozzáadás!"/>
  <br/>
  (csak .jpg vagy .png képhez készít a program magától thumbnailt)
  <br/>
  <?php echo '(a '.$gal['mappa'].' és a '.$gal['thumb'].' mappákon chmod 0777 legyen!)'; ?>

  </form>
<br/><br/>

<?php
  while($pic = mysql_fetch_assoc($pic_result)) {
    
    echo '<a href="?pic='.$pic['pic_id'].'">'.htmlspecialchars($pic['title']).'</a> ('.htmlspecialchars($categories[$pic['cat_id']]['name']).')<br/>';
    
  }

}
?>