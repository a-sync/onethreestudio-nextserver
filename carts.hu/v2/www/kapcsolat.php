<?php
require('admin/config.php');
$active_link = 'kapcsolat';
include('head.php');

?>

      <div id="kapcsolat_title">Kapcsolat</div>
      
      <div id="kapcsolat_bal">
        <span class="kapcsolat_info">Ahogy Ön is láthatta, cégünk erős múlttal és hatalmas referenciákkal rendelkezik, kezdve a kisebb projectektől a multi cégek termékeit megörökítő fotókig.
        <br /><br />
        Szivesen állunk bármilyen kihívás elébe, és örömmel vennénk ha együtt dolgozhatnánk. Ha kérdése vagy ötlete van, kérem vegye felvelünk a kapcsolatot, szivesen állunk rendelkezésére!
        </span>
        
        
        <div id="uzenetkuldo_head">Üzenetküldő</div>
        <form id="uzenetkuldo" action="" method="post">
          <input class="uzenetkuldo_input" type="text" name="nev" value="Név" onfocus="if(this.value == 'Név') this.value = '';" onblur="if(this.value == '') this.value = 'Név';" />
          <br />
          <input class="uzenetkuldo_input" type="text" name="email" value="E-mail cím" onfocus="if(this.value == 'E-mail cím') this.value = '';" onblur="if(this.value == '') this.value = 'E-mail cím';" />
          <br />
          <input class="uzenetkuldo_input" type="text" name="telefon" value="Telefonszám" onfocus="if(this.value == 'Telefonszám') this.value = '';" onblur="if(this.value == '') this.value = 'Telefonszám';" />
          <br />
          <textarea class="uzenetkuldo_textarea" name="uzenet" onfocus="if(this.value == 'Üzenet') this.value = '';" onblur="if(this.value == '') this.value = 'Üzenet';">Üzenet</textarea>
          <br />
          <input class="uzenetkuldo_submit" type="submit" name="uzenetkuldo" value=" " />
          <br />
        </form>
        
        
      </div><!--/kapcsolat_bal-->
      
      <div id="kapcsolat_jobb">
        <div id="kapcsolat_ceg">Chromogenic Arts Kft.</div>
        <div id="kapcsolat_info">
          Török György
          <br />
          E-mail: info@carts.hu
          <br />
          Telefon: 06 30 961 4662
          <br />
        </div>
      </div><!--/kapcsolat_jobb-->

<?php
include('foot_nl.php');
?>