<?php
/* CONFIG.PHP */
//$rpl['akarmi'] helyett $rpl_akarmi
if($_SERVER['HTTP_HOST'] == 'aoc.onethreestudio.com') {
  $rpl['dbhost'] = 'localhost';
  $rpl['dbuser'] = 'portfolio';
  $rpl['dbpass'] = '13ssqlrule';
  $rpl['dbname'] = 'portfolio';
}
else {
  $rpl['dbhost'] = 'localhost';
  $rpl['dbuser'] = 'root';
  $rpl['dbpass'] = '';
  $rpl['dbname'] = 'rpl';
}

$rpl_reg_secret = 'uborka';//registration secret //register.php?secret=

?>