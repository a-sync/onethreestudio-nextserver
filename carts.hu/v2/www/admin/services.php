<?php
if(!defined('_OTS_ADMIN_')) { header("Location: ../admin.php"); exit; } // ha nincs bejelentkezve (nem admin)

if(@mysql_num_rows($result) > 0)
{
  $hir = mysql_fetch_assoc($result);
  
  echo '<font color="red">'.$error.'</font><br/>';
?>
<form action="" method="post">
  <input type="hidden" name="hir_id" value="<?php echo $hir['hir_id']; ?>"/>

  Szolgáltatás címe: <input type="text" name="title" maxlength="200" size="50" value="<?php echo htmlspecialchars($hir['title']); ?>"/>
  <br/>
  Szolgáltatás szövege: <br/><textarea name="text" rows="10" cols="50"><?php echo htmlspecialchars($hir['text']); ?></textarea>
  <br/>
  Képfájl elérése: <?php echo $gal['thumb']; ?><input type="text" size="50" name="file" value="<?php echo substr($hir['meta'], 2); ?>"/>
  <br/>
  Státusz: Aktív <input type="radio" name="status" value="1"<?php if($hir['status'] != 0) { echo ' checked'; } ?>/> &nbsp; Inaktív <input type="radio" name="status" value="0"<?php if($hir['status'] == 0) { echo ' checked'; } ?>/>
  <br/>
  Prioritás: <input type="text" name="priority" maxlength="4" size="4" value="<?php echo htmlspecialchars($hir['priority']); ?>"/>
  <br/>
  <br/>
  <input type="submit" name="services_mod" value="Módosítás!"/>
  <input type="submit" name="services_del" value="Törlés!"/>
</form>

<?php
  
}
else {
  $news_result = mysql_query("SELECT * FROM `hirek` WHERE `meta` != '' ORDER BY `priority` DESC, `time` DESC");
?>

  <h2 style="cursor: pointer;" onclick="le('add_services')">Új szolgáltatás létrehozása</h2>
<?php echo '<font color="red">'.$error.'</font>'; ?>
  <form action="" method="post" id="add_services" style="display: none;">

  Szolgáltatás címe: <input type="text" name="title" maxlength="200" size="50"/>
  <br/>
  Szolgáltatás szövege: <br/><textarea name="text" rows="10" cols="50"></textarea>
  <br/>
  Képfájl elérése: <?php echo $gal['thumb']; ?><input type="text" size="50" name="file" value=""/>
  <br/>
  Státusz: Aktív <input type="radio" name="status" value="1" checked /> &nbsp; Inaktív <input type="radio" name="status" value="0"/>
  <br/>
  Prioritás: <input type="text" name="priority" maxlength="4" size="4" value="100"/>
  <br/>
  <br/>
  <input type="submit" name="add_services" value="Hozzáadás!"/>

  </form>
  <br/><br/>

<?php
  while($hir = mysql_fetch_assoc($news_result)) {
    if($hir['status'] == 0) echo '<i>';
    echo '<a href="?news='.$hir['hir_id'].'">'.htmlspecialchars($hir['title']).'</a> (prioritás: '.htmlspecialchars($hir['priority']).') ('.date('Y.m.d.', $hir['time']).')<br/>';
    if($hir['status'] == 0) echo '</i>';
  }
}
?>