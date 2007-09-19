<?php
require('functions.php');

reqLogin('login.php');

delCookie();

redir('login.php');
?>