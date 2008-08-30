<?php
/* REGISTER_HANDLER.PHP */

include('functions.php');

timeout(1);//biztonsági okokból

if(checkLogin() !== false) { redir('index.php'); }//log

if($_POST['secret'] == $rpl_reg_secret || $rpl_reg_secret === false) {
  $username = esc($_POST['username'], 1);
  $email = esc($_POST['email'], 1);
  $password1 = esc($_POST['password1'], 1);
  $password2 = esc($_POST['password2'], 1);


//username check
  //(a-z, A-Z, 0-9, min. 3, max 32 character)
  if(!preg_match('/^[A-Za-z0-9]{3,32}$/', $username)) {
    redir('register.php?error=1');
  }
//username check

//email check
  //(valid e-mail address)
  if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) {
    redir('register.php?error=2');
  }
//email check


//password check
  //(password confirmation)
  if($password1 !== $password2) { redir('register.php?error=3'); }
  else {
    //the password is 3 or more and 200 or less characters long
    if(strlen($password1) < 3 || strlen($password1) > 200) {
      redir('register.php?error=4');
    }
  /*
    //the password doesn't contains the username or vica versa
    if(preg_match('/'.strtolower($username).'/', strtolower($password1)) || preg_match('/'.strtolower($password1).'/', strtolower($username))) {
      redir('register.php?error=666');
    }

    //the password has a-z character in it
    if(!preg_match('/[a-z]/', $password1)) {
      redir('register.php?error=666');
    }

    //the password has A-Z character in it
    if(!preg_match('/[A-Z]/', $password1)) {
      redir('register.php?error=666');
    }

    //the password has 0-9 character in it
    if(!preg_match('/[0-9]/', $password1)) {
      redir('register.php?error=666');
    }
  */
  }

  $password = ident($username, $password1);
//password check


//duplication check
  $query1 = "SELECT * FROM `rpl_users` WHERE `username` = '$username' OR `email` = '$email'";
  $du = sql_fetch_all(sql_query($query1));//dupe users
  if($du[0]) {
    //if($du[2]) {  }//log
    //two users have the same email or username / both are taken
    if($du[1]) { redir('register.php?error=5'); }
    //the username is taken
    if($du[0]['username'] == $username) { redir('register.php?error=6'); }
    //the email is taken
    if($du[0]['email'] == $email) { redir('register.php?error=7'); }
  }
//duplication check


  $query2 = "INSERT INTO `rpl_users` (`usid`, `username`, `password`, `email`, `class`) VALUES (NULL, '$username', '$password', '$email', '0')";
  $this_usid = sql_query($query2, true);

  if($this_usid === false) {
    redir('register.php?error=8');//log
  }

  //redir('index.php');
  redir('login.php');
}
else { redir('index.php'); }
//log
?>