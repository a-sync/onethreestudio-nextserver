<?php
/* MOD_ICON.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 20)) { redir('index.php'); }//log

$link = permaHandling();
$_PICTURE = getPicInfo($link[0], $link[1]);

$error = esc($_GET['error'], 1);
$mod_icon_errors = array(
  1 => 'Invalid title.',
  2 => 'File renaming failed.',
  3 => 'File deleting failed.'
);

if($_PICTURE !== false) {
  head(false, 'Icons &gt; '.esc($_PICTURE['title'] ,2), $_USER);
  box(0);

  $perma_ext = strtolower(substr(strrchr($_PICTURE['perma'], '.'), 0));
  $perma = substr($_PICTURE['perma'], 0, (0 - strlen($perma_ext)));

  if($e = $mod_icon_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }
?>

  <form id="mod_icon" method="post" action="<?php echo $_HOST; ?>mod_icon_handler.php?id=<?php echo esc($_PICTURE['piid'], 2); ?>">
    <?php echo '<img src="'.$_HOST.'pictures/icons/'.$_PICTURE['perma'].'"/>'; ?>
    <br/>
    <br/>

    Ikon megnevezése*:
    <br/>
    <input type="text" name="title" maxlength="200" value="<?php echo esc($_PICTURE['title'], 2); ?>"/>
    <br/>
    <br/>
    <br/>

    Ikon permalink címe:
    <br/>
    (ha nincs megadva, átalakítja a címet, ha foglalt, megszámozza)
    <br/>
    <input type="text" name="perma" maxlength="200" value="<?php echo esc($perma, 2); ?>"/><?php echo esc($perma_ext, 2); ?>
    <br/>
    <br/>
    <br/>

    <span class="admin_delete">Ikon törlése: <input type="checkbox" name="delete_icon" value="true"/></span>
    <br/>
    <br/>
    <br/>

    <input type="hidden" name="id" value="<?php echo esc($_PICTURE['piid'], 2); ?>"/>  
    <input type="submit" value="Mehet">
  </form>

<?php
  box(1);
  foot();
}
else { redir('icons.php?error=1'); }
?>