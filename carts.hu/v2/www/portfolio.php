<?php
require('admin/config.php');
$active_link = 'portfolio';
include('head.php');

$categories_res = mysql_query("SELECT * FROM `galeria` ORDER BY `priority` DESC");

$where = "WHERE `cat_id` != '0'";
if(isset($_GET['kat']) && is_numeric($_GET['kat'])) $where .= " AND `cat_id` = '".$_GET['kat']."'";

$order = "ORDER BY RAND() LIMIT 8";
if(isset($_GET['kat']) && is_numeric($_GET['kat'])) $order = "";

$pictures_res = mysql_query("SELECT * FROM `kepek` {$where} {$order}");
?>
      <!--<div id="portfolio_title">Kategóriák</div>-->
      
      <div id="kategoriak">
      <?php
        while(false !== ($cat = mysql_fetch_assoc($categories_res))) {
          echo '<div class="kategoria"><a href="?kat='.$cat['cat_id'].'">'.htmlspecialchars($cat['name']).'</a></div>';
        }
      ?>
      </div><!--/kategoriak-->
      
      <div id="kepek">
      <?php
        $n = 0;
        while(false !== ($pic = mysql_fetch_assoc($pictures_res))) {
          $n++;
          if($n == 1) {
            echo '<div class="gal_sor">';
          }
          echo '<div class="kep"><div class="kep_cim">'.htmlspecialchars($pic['title']).'</div><a href="'.$gal['kepek_mappa'].htmlspecialchars($pic['file']).'"><img src="'.$gal['kepek_thumb'].htmlspecialchars($pic['file']).'" alt="'.htmlspecialchars($pic['title']).'"/></a></div>';
          
          if($n == 4) {
            echo '</div>';
            $n = 0;
          }
        }
        if($n != 0) echo '</div>';
      ?>
      </div><!--/kepek-->

<?php
include('foot.php');
?>