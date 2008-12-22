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
        <div id="kepek_head">Admin</div>


<a href="?a=categories">Kategóriák kezelése</a>
<br/>
<a href="?a=pictures">Képek kezelése</a>
<br/>
<a href="?a=news">Hírek kezelése</a>
<br/>
<br/>
<a href="?a=logout">Kijelentkezés</a>
<br/>


      </div><!--/side_panel-->
      
      <div id="content"><!--content-->
        <?php include 'admin/'.$_i.'.php'; ?>
      </div><!--/content-->

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
      
      <div id="content"><!--content-->
        <?php include 'admin/login.php'; ?>
      </div><!--/content-->

<?php
  include 'foot.php';
}
?>