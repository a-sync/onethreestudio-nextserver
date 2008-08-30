<?php
define('_OTS_ADMIN_', 135);
require('admin/config.php');

if(checkLogin()) {
  
  if($_GET['a'] == 'logout') {
    $_i = 'login';
  }
  elseif($_GET['a'] == 'categories' || $_GET['cat'] != '') {
    $_i = 'categories';
  }
  elseif($_GET['a'] == 'pictures' || $_GET['pic'] != '') {
    $_i = 'pictures';
  }
  elseif($_GET['a'] == 'news' || $_GET['news'] != '') {
    $_i = 'news';
  }
  elseif($_GET['a'] == 'services' || $_GET['services'] != '') {
    $_i = 'services';
  }
  else {
    $_i = 'index';
  }
  
  include 'admin/'.$_i.'_handler.php';
  include 'head.php';
?>
<script type="text/javascript"><!--
function le(id){var e = document.getElementById(id); e.style.display = (e.style.display == 'none') ? '' : 'none'; }
--></script>

      <div id="side_panel"><!--side_panel-->
        <h3>Admin</h3>


<a href="?a=categories">Kategóriák kezelése</a>
 &nbsp; 
<a href="?a=pictures">Képek kezelése</a>
 &nbsp; 
<a href="?a=news">Hírek kezelése</a>
 &nbsp; 
<a href="?a=services">Szolgáltatások kezelése</a>
 &nbsp; 
 &nbsp; 
<a href="?a=logout">Kijelentkezés</a>


      </div><!--/side_panel-->
      
      <div id="tartalom"><!--tartalom-->
        <?php include 'admin/'.$_i.'.php'; ?>
      </div><!--/tartalom-->

<?php
  include 'foot.php';
}
else {
  include 'admin/login_handler.php';
  include 'head.php';
?>

      <div id="side_panel"><!--side_panel-->
        <div id="kepek_head">Admin</div>
        Kérlek jelentkezz be!
      </div><!--/side_panel-->
      
      <div id="tartalom"><!--tartalom-->
        <?php include 'admin/login.php'; ?>
      </div><!--/tartalom-->

<?php
  include 'foot.php';
}
?>