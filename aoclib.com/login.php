<?php
/* LOGIN.PHP */

include('functions.php');

if(checkLogin() !== false) { redir('index.php'); }//log

$error = esc($_GET['error'], 1);
$login_errors = array(
  1 => 'Missing username or password.',
  2 => 'Incorrect username or password.',
  3 => 'Your cookies are corrupted.'
);

head(false, 'Login');

box(0);
?>

<form id="login" method="post" action="<?php echo $_HOST; ?>login_handler.php">
  <div class="login_data_name">Username:</div>
  <div class="login_data_input"><input type="text" name="username" maxlength="32"/></div>

  <div class="login_data_name">Password:</div>
  <div class="login_data_input"><input type="password" name="password" maxlength="200"/></div>

  <div class="login_data_name"><input type="submit" value="Login!"/></div>

  <?php if($e = $login_errors[$error]) { echo '<p class="error">'.$e.'</p>'; } ?>

</form>

<?php
box(1);

foot();
?>