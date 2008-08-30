<?php
require('admin/config.php');
$active_link = 'index';
include('head.php');

$pic_res = mysql_query("SELECT * FROM `kepek` WHERE `tags` != '' AND `tags` != ',' AND `tags` != ',referencia,' ORDER BY `tags` ASC LIMIT 15");
$pictures = array();
while($pic = mysql_fetch_assoc($pic_res)) { $pictures[$pic['pic_id']] = $pic; }

$active_pic = (isset($_GET['kep']) && is_numeric($_GET['kep'])) ? $_GET['kep'] : 1;
$all_pic = count($pictures);
if($active_pic < 1 || $active_pic > $all_pic) $active_pic = 1;

$elozo = ($active_pic > 1) ? ($active_pic - 1) : 1;
$kovetkezo = ($all_pic > $active_pic) ? ($active_pic + 1) : $all_pic;
?>
      
      <div id="lapozo">
        
        <a id="elozo" href="?kep=<?php echo $elozo; ?>"> </a>
        
        <div id="szamok">
        <?php
          
          $n = 0;
          $the_pic = false;
          foreach($pictures as $pic) {
            $n++;
            $active = '';
            if($active_pic == $n) {
              $active = '_aktiv';
              $the_pic = $pic;
            }
            echo '<a class="szam'.$active.'" href="?kep='.$n.'">'.$n.'</a>';
          }
        ?>
        </div>
        
        <a id="kovetkezo" href="?kep=<?php echo $kovetkezo; ?>"> </a>
        
      </div><!--/lapozo-->
      
      <div id="tartalom">
      <?php
        echo '<img src="'.$gal['kepek_mappa'].htmlspecialchars($the_pic['file']).'" width="900" alt="'.htmlspecialchars($the_pic['title']).'" />';
      ?>
      </div><!--/tartalom-->
      
<?php
include('foot_nl.php');
?>