<?php
/* MOD_USER.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 30)) { redir('index.php'); }//log

$link = permaHandling();
$_USERDATA = getUserInfo($link[0], $link[1]);

$error = esc($_GET['error'], 1);
$mod_user_errors = array(
  1 => 'The given username is invalid.',
  2 => 'The given e-mail address is invalid.',
  3 => 'The givan class is invalid.',
  4 => 'The password length is invalid.',
  5 => 'The given username and e-mail is taken.',
  6 => 'The given username is taken.',
  7 => 'The given e-mail is taken.',
  8 => 'You have no right to modify the settings of this user.'
);

if($_USERDATA !== false) {
  head(false, 'User &gt; '.esc($_USERDATA['username'] ,2), $_USER);
  box(0);

  $class_sel[$_USERDATA['class']] = ' selected';
  $class_names = array(
    0 => 'User',
    10 => 'Content Manager',
    20 => 'Moderator',
    30 => 'Administrator',
    40 => 'Developer.'
  );

  if($_USERDATA['class'] < $_USER['class']) {
    if($e = $mod_user_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }
?>

    <form id="mod_user" method="post" action="<?php echo $_HOST; ?>mod_user_handler.php?id=<?php echo esc($_USERDATA['usid'], 2); ?>">
      Username:
      <br/>
      <?php /*<input type="text" name="username" maxlength="32" value="<?php echo esc($_USERDATA['username'], 2); ?>"/> (a-z, A-Z, 0-9, min. 3, max 32 character)*/ ?>
      <?php echo esc($_USERDATA['username'], 2); ?>
      <br/>
      <br/>

      E-Mail:
      <br/>
      <input type="text" name="email" maxlength="200" value="<?php echo esc($_USERDATA['email'], 2); ?>"/> (valid e-mail address, max. 200 character)
      <br/>
      <br/>

      Class:
      <br/>
      <select name="class">
      <?php
        $maxClass = ($_USER['class'] >= 40) ? $_USER['class'] + 1 : $_USER['class'];
        for($z = 0; $z < $maxClass; $z++) {
          if($class_names[$z]) { echo '<option disabled>'.$class_names[$z].'</option>'; }
          echo '<option value="'.$z.'"'.$class_sel[$z].'>'.$z.'</option>';
        }
      ?>
      </select>
      <br/>
      <br/>

      New password:
      <br/>
      <input type="text" name="password" maxlength="200" value=""/> (min. 3, max. 200 character)
      <br/>
      <br/>
      <br/>

      <span class="admin_delete">Delete user: <input type="checkbox" name="delete_user" value="true"/></span>
      <br/>
      <br/>
      <br/>

      <input type="hidden" name="id" value="<?php echo esc($_USERDATA['usid'], 2); ?>"/>
      <input type="submit" value="Send">
    </form>

<?php
  }
  else {
?>

    <div id="mod_user">
      Username:
      <br/>
      <?php echo esc($_USERDATA['username'], 2); ?>
      <br/>
      <br/>

      E-Mail:
      <br/>
      <?php echo esc($_USERDATA['email'], 2); ?>
      <br/>
      <br/>

      Class:
      <br/>
      <?php echo esc($_USERDATA['class'], 2); ?>
    </div>

<?php
  }

  box(1);
  foot();
}
else { redir('users.php?error=1'); }
?>