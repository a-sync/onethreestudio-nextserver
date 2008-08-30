<?php
require('admin/config.php');
$active_link = 'referenciak';
include('head.php');

$references_res = mysql_query("SELECT * FROM `kepek` WHERE `tags` = ',referencia,'");
?>

      <div id="referenciak">
      
      <?php
        $n = 0;
        while(false !== ($ref = mysql_fetch_assoc($references_res))) {
          $n++;
          if($n == 1) {
            echo '<div class="kep_sor">';
          }
          
          echo '<a title="'.htmlspecialchars($ref['title']).'" href="'.$gal['kepek_mappa'].htmlspecialchars($ref['file']).'"><img align="left" border="0" src="'.$gal['kepek_thumb'].htmlspecialchars($ref['file']).'" alt="'.htmlspecialchars($ref['title']).'" /></a>';
          
          if($n == 6) {
            echo '</div>';
            $n = 0;
          }
        }
        if($n != 0) echo '</div>';
      ?>
      
      </div><!--/referenciak-->

<?php
include('foot.php');
?>