<?php
require('admin/config.php');
$active_link = 'extrak';
include('head.php');

$news_result = mysql_query("SELECT * FROM `hirek` WHERE `meta` = '' AND `status` = '1' ORDER BY `priority` DESC, `time` DESC");
?>

      <!--<div id="extrak_title">Extrák</div>-->
      <?php
        while(false !== ($news = mysql_fetch_assoc($news_result))) {
      ?>
      <div class="extrak_blokk">
        <div class="extrak_cim"><?php echo htmlspecialchars($news['title']); ?></div>
        <div class="extrak_szoveg"><?php echo nl2br(htmlspecialchars($news['text'])); ?>
          <div class="extrak_datum"><?php echo date('Y.m.d. - H:i:s', $news['time']); ?></div>
        </div>
      </div>
      <?php
        }
      ?>

<?php
include('foot.php');
?>