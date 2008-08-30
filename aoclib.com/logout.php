<?php
/* LOGOUT.PHP */

include('functions.php');

timeout(1);//biztonsági okokból

delCookie();

redir('index.php');
?>