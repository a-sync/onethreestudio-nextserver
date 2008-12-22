<?php
if(!defined('_OTS_ADMIN_')) { header("Location: ../admin.php"); exit; } // ha nincs bejelentkezve (nem admin)

if(@mysql_num_rows($result) > 0)
{
  $cat = mysql_fetch_assoc($result);
  
?>

<form action="" method="post">
  <input type="hidden" name="cat_id" value="<?php echo $cat['cat_id']; ?>"/>

  Kategória neve: <input type="text" name="name" value="<?php echo htmlspecialchars($cat['name']); ?>"/>
  <br/>
  
  <input type="submit" name="cat_mod" value="Módosítás!"/>
  <input type="submit" name="cat_del" value="Törlés!"/>
</form>
<?php
  
}
else {
  $cat_result = mysql_query("SELECT * FROM `galeria` ORDER BY `name` ASC");
?>

  <h2 style="cursor: pointer;" onclick="le('add_cat')">Új kategória létrehozása</h2>
<?php echo '<font color="red">'.$error.'</font>'; ?>
  <form action="" method="post" id="add_cat" style="display: none;">

  Kategória neve: <input type="text" name="name" maxlength="200"/>
  <br/>
  <input type="submit" name="add_cat" value="Hozzáadás!"/>

  </form>
  <br/><br/>

<?php
  while($cat = mysql_fetch_assoc($cat_result)) {
    
    echo '<a href="?cat='.$cat['cat_id'].'">'.htmlspecialchars($cat['name']).'</a><br/>';
    
  }

}
?>