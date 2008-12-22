<?php
if(!defined('_OTS_ADMIN_')) { header("Location: ../admin.php"); exit; } // ha nincs bejelentkezve (nem admin)

if(@mysql_num_rows($result) > 0)
{
  $hir = mysql_fetch_assoc($result);
  
  echo '<font color="red">'.$error.'</font><br/>';
?>
<form action="" method="post">
  <input type="hidden" name="hir_id" value="<?php echo $hir['hir_id']; ?>"/>

  Hír címe: <input type="text" name="title" maxlength="200" size="50" value="<?php echo htmlspecialchars($hir['title']); ?>"/>
  <br/>
  Hír szövege: <br/><textarea name="text" rows="10" cols="50"><?php echo htmlspecialchars($hir['text']); ?></textarea>
  <br/>
  Státusz: Aktív <input type="radio" name="status" value="1"<?php if($hir['status'] != 0) { echo ' checked'; } ?>/> &nbsp; Inaktív <input type="radio" name="status" value="0"<?php if($hir['status'] == 0) { echo ' checked'; } ?>/>
  <br/>
  Prioritás: <input type="text" name="priority" maxlength="4" size="4" value="<?php echo htmlspecialchars($hir['priority']); ?>"/>
  <br/>
  <br/>
  <input type="submit" name="news_mod" value="Módosítás!"/>
  <input type="submit" name="news_del" value="Törlés!"/>
</form>

<?php
  
}
else {
  $news_result = mysql_query("SELECT * FROM `hirek` ORDER BY `priority` DESC, `time` DESC");
?>

  <h2 style="cursor: pointer;" onclick="le('add_news')">Új hír létrehozása</h2>
<?php echo '<font color="red">'.$error.'</font>'; ?>
  <form action="" method="post" id="add_news" style="display: none;">

  Hír címe: <input type="text" name="title" maxlength="200" size="50"/>
  <br/>
  Hír szövege: <br/><textarea name="text" rows="10" cols="50"></textarea>
  <br/>
  Státusz: Aktív <input type="radio" name="status" value="1" checked /> &nbsp; Inaktív <input type="radio" name="status" value="0"/>
  <br/>
  Prioritás: <input type="text" name="priority" maxlength="4" size="4" value="100"/>
  <br/>
  <br/>
  <input type="submit" name="add_news" value="Hozzáadás!"/>

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