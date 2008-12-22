<?php
require('admin/config.php');

$news_res = mysql_query("SELECT * FROM `hirek` WHERE `status` = '1' ORDER BY `priority` DESC, `time` DESC");
$news = Array();
while($hir = mysql_fetch_assoc($news_res)) { $news[$hir['hir_id']] = $hir; }

$pic_res = mysql_query("SELECT * FROM `kepek` ORDER BY RAND() LIMIT 2");
while($pic = mysql_fetch_assoc($pic_res)) { $pictures[$pic['pic_id']] = $pic; }

include 'head.php';
?>
      <div id="side_panel"><!--side_panel-->
        <div id="kepek_head">Képek a galériából</div>

<?php
  if(count($pictures) > 0) {
    foreach($pictures as $pic) {
      $desc = ($pic['desc'] == '') ? '' : ' - '.nl2br(htmlspecialchars($pic['desc']));
      echo '<a rel="lightbox[rand]" title="'.htmlspecialchars($pic['title']).$desc.'" href="'.$gal['kepek_mappa'].$pic['file'].'"><img src="'.$gal['kepek_thumb'].$pic['file'].'" alt="'.htmlspecialchars($pic['title']).'"/></a><br/>';
    }

    echo '<div id="irany_a_galeria"><a href="galeria.php">Irány a galéria!</a></div>';
  }
  else { echo '<div id="irany_a_galeria">Még nincsenek képek...</div>'; }
?>

      </div><!--/side_panel-->
      
      <div id="content"><!--content-->
        <div id="hello">
          Üdvözlöm honlapomon!
          <br/>
          <span id="i_am">PERTL EVETKE, szobrászművész</span>
        </div>

        <!--hírek-->
        <?php foreach($news as $hir) { ?>
        <div class="title">
          <div class="title_text"><?php echo htmlspecialchars($hir['title']); ?></div>
          <div class="title_time"><?php echo date('Y.m.d.', $hir['time']); ?></div>
        </div>
        <div class="text"><?php echo nl2br(htmlspecialchars($hir['text'])); ?></div>
        <?php } ?>
        <!--/hírek-->

      </div><!--/content-->
<?php include 'foot.php'; ?>