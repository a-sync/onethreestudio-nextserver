<?php
/* REGISTER.PHP */

include('functions.php');

if(checkLogin() !== false) { redir('index.php'); }//log

$error = esc($_GET['error'], 1);
$register_errors = array(
  1 => 'The given username is invalid.',
  2 => 'The given e-mail address is invalid.',
  3 => 'The password confirmation failed.',
  4 => 'The password length is invalid.',
  5 => 'The given username and e-mail is taken.',
  6 => 'The given username is taken.',
  7 => 'The given e-mail is taken.',
  8 => 'Sorry, but we culd not save your request due to a database error. Please try again later...'
);

head(false, 'Register');

box(0);

if($_POST['secret'] == $rpl_reg_secret || $rpl_reg_secret === false) {
?>

<form id="register" method="post" action="<?php echo $_HOST; ?>register_handler.php">
  <div class="register_data_name">Username:</div>
  <div class="register_data_input"><input type="text" name="username" maxlength="32"/> (a-z, A-Z, 0-9, min. 3, max 32 character)</div>

  <div class="register_data_name">E-Mail:</div>
  <div class="register_data_input"><input type="text" name="email" maxlength="200"/> (valid e-mail address, max. 200 character)</div>

  <div class="register_data_name">Password:</div>
  <div class="register_data_input"><input type="password" name="password1" maxlength="200"/> (min. 3, max. 200 character)</div>

  <div class="register_data_name">Password confirmation:</div>
  <div class="register_data_input"><input type="password" name="password2" maxlength="200"/></div>

  <div class="register_data_name"><input type="submit" value="Register!"/></div>

  <?php
    if($e = $register_errors[$error]) { echo '<p class="error">'.$e.'</p>'; }

    if($rpl_reg_secret !== false) { echo '<input type="hidden" name="secret" value="'.esc($_POST['secret'], 2).'"/>'; }
  ?>
</form>

<?php
}
else {
?>

<form id="register" method="post" action="<?php echo $_HOST; ?>register.php">
  <div class="register_data_name">Secret:</div>
  <div class="register_data_input"><input type="text" name="secret"/></div>

  <div class="register_data_name"><input type="submit" value="Register!"/></div>

  <?php
    if($e = $register_errors[$error]) { echo '<p class="error">'.$e.'</p>'; }
    else { echo '<p class="error">The registrations are opened only for certain people right now. Please check back later.</p>'; }
  ?>
</form>

<?php
}

box(1);

foot();
?>