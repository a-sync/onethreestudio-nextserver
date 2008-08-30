<?php
require('admin/config.php');
$active_link = 'szolgaltatasok';
include('head.php');

$services_result = mysql_query("SELECT * FROM `hirek` WHERE `meta` != '' AND `status` = '1' ORDER BY `priority` DESC, `time` DESC");
?>
      <div id="szolgaltatasok_bal">
      <?php
        while(false !== ($service = mysql_fetch_assoc($services_result))) {
      ?>
      
        <div class="szolg_blokk">
          <div class="szolg_cim"><?php echo htmlspecialchars($service['title']); ?></div>
          <div class="szolg_szoveg">
          <img class="szolg_kep" src="<?php echo $gal['kepek_thumb'].htmlspecialchars(substr($service['meta'], 2)); ?>" alt="<?php echo htmlspecialchars(substr($service['meta'], 2)); ?>" align="right" />
<?php echo nl2br(htmlspecialchars($service['text'])); ?>
          </div>
        </div>
        
      <?php
        }
      ?>
      </div><!--/szolgaltatasok_bal-->
      
      <div id="szolgaltatasok_jobb">
        
        <a id="arlista" href="arlista.pdf">Árlista letöltése</a>
        
        <div id="twitter_box">
          <a href="<?php echo htmlspecialchars($twitter_link); ?>" id="twitter_head">Eseménykövető</a>
          <?php
            include('admin/xmlthing.class.php');
            $xml_parser = new XMLThing;
            $rss = $xml_parser->load($twitter_rss);
            unset($xml_parser);
            //echo '<!--'.print_r($rss, true).'-->';
            for($i = 0; $i < $twitter_posts; $i++) {
              $post = $rss['rss']['channel']['item'][$i];
              echo '<div class="twitter_msg">'.nl2br($post['description']).'</div>';
            }
          ?>
          
        </div><!--/twitter_box-->
        
        <div id="hirlevel_head">Hírlevél</div>
        <form id="hirlevel_jobb" action="" method="post">
          Amennyiben érdeklik a jövőben akciók és kedvezmények, kérjem iratkozzon fel hírlevelünkre!
          <br /><br />
          <input class="hirlevel_input" type="text" name="nev" value="Név" onfocus="if(this.value == 'Név') this.value = '';" onblur="if(this.value == '') this.value = 'Név';" />
          <br />
          <input class="hirlevel_input" type="text" name="email" value="E-mail cím" onfocus="if(this.value == 'E-mail cím') this.value = '';" onblur="if(this.value == '') this.value = 'E-mail cím';" />
          <br />
          <input class="hirlevel_input" type="text" name="posta" value="Postacím" onfocus="if(this.value == 'Postacím') this.value = '';" onblur="if(this.value == '') this.value = 'Postacím';" />
          <br />
          <input class="hirlevel_submit" type="submit" name="hirlevel" value=" " />
          <br />
        </form>
        
      </div><!--/szolgaltatasok_jobb-->
<?php
include('foot.php');
?>