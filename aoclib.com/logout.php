<?php
/* LOGOUT.PHP */

include('functions.php');

timeout(1);//biztons�gi okokb�l

delCookie();

redir('index.php');
?>