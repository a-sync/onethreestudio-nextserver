<?php
require('admin/config.php');

$pic_res = mysql_query("SELECT * FROM `kepek` ORDER BY RAND() LIMIT 2");
while($pic = mysql_fetch_assoc($pic_res)) { $pictures[$pic['pic_id']] = $pic; }

$_error = false;
if(count($_POST) > 0) {
  //email összeállítása
  $cim = 'pertlevetke@yahoo.com'; //a cím amire a küldött email menjen
  $targy = 'Kapcsolat.'; //az üzenet tárgya

  //valós email cím van-e megadva
  $email = mysql_real_escape_string(trim($_POST['email']));
  if(!preg_match('/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/', $email)) {
    $email = 'noreplay@pertlevetke.hu'; //fejlécbe kód injektálást elkerülendő
  }

  //fejléc küldővel, reply mezővel, karakterkódolással
  $fejlec =
     'From: PertlEvetke.hu <kapcsolat@pertlevetke.hu>'."\r\n"
    .'Reply-To: '.$email."\r\n"
    .'Content-Type: text/plain; charset="iso-8859-2"'."\r\n"
    .'X-Mailer: PHP/5'//.phpversion();
    ."\n\n";

  //üzenet
  $uzenet =
     'Név: '.mysql_real_escape_string($_POST['nev'])."\r\n"
    .'Email cím: '.$email."\r\n"
    .'Telefonszám: '.mysql_real_escape_string($_POST['telefon'])."\r\n"
    ."\r\n"
    .mysql_real_escape_string($_POST['uzenet'])
    ."\n\n";

  $uzenet = wordwrap($uzenet, 70);

  if(trim($_POST['email']) != $email) { $_error = '<b style="color: red;">Valós email címet kell megadnod.</b>'; }
  else {
    if(!mail($cim, $targy, $uzenet, $fejlec)) {
      $_error = '<b style="color: red;">Az e-mail küldése sikertelen volt, kérlek próbálkozz később, vagy írj a '.$cim.' címre.</b>';

      //hiba log
      /*if(!file_exists('email.error.log.php')) {
        @touch('email.error.log.php');
        file_put_contents('email.error.log.php', '<?php if(!defined("_LOG_")) { die("Failboat!"); } ?>'."\r\n");
      }
      @file_put_contents('email.error.log.php', $cim.' (Targy: '.$targy.')<br/><pre>'.$fejlec.'</pre><br/><pre>'.$uzenet.'</pre><br/><br/>', FILE_APPEND);*/
    }
    else { $_error = '<b style="color: gray;">Az emailt sikeresen elküldted.</b>'; }
  }
}
//die('<html><body><pre>'.print_r(array($_POST, $email, $uzenet, $_error), true).'</pre></body></html>');//dbg
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

        <!--kapcsolat-->
        <div id="contact">Kapcsolat</div>
        <div id="contact_info">
Amennyiben műveim felkeltették érdeklődését, kérem lépjen kapcsolatba velem az alábbi csatornák egyikén!
<br/><br/><br/>
Cím: 2040 Budaörs, Baross utca 14/1
<br/><br/>
Telefon: 06 (23) 415 264
<br/><br/>
Mobil: 06 (30) 688 3690
<br/><br/>
Email: kapcsolat@pertlevetke.hu
        </div>
        <div id="mailer">Üzenetküldő</div>
        
        <form id="mailer_form" method="post" name="kapcs-email" action="">
          Feladó neve:<br/>
          <input type="text" class="input_text" maxlength="250" name="nev"/>
          <br/>
          Email címe:<br/>
          <input type="text" class="input_text" maxlength="250" name="email"/>
          <br/>
          Telefonszáma:<br/>
          <input type="text" class="input_text" maxlength="250" name="telefon"/>
          <br/>
          Üzenet:<br/>
          <textarea class="input_textarea" name="uzenet"></textarea>
          <br/>
          <input type="submit" class="input_submit" value=" "/>
          
          <?php if($_error != false) { echo '<br/>'.$_error; } ?>
        </form>
        <!--/kapcsolat-->
        
      </div><!--/content-->
<?php include 'foot.php'; ?>