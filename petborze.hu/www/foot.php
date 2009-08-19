
<?php
  if($right_bar == '') {
?>

      <div id="right_bar_frame">
        <div id="right_bar_title">
          Hirdetések
        </div>
        
        <div id="right_bar">
        
        <?php
          $reklam = rand_file('./reklamok/', 'html', 2);
          if($reklam[0] != '') include $reklam[0];
          if($reklam[1] != '') include $reklam[1];
        ?>
        
        </div><!--/right_bar-->
      </div><!--/right_bar_frame-->

<?php
  }
  elseif($right_bar == 'qlist') {
?>
      <div id="right_bar_frame">
        <div id="right_bar_title">
          <a href="hirdetesek.php?hirdetes=kedvencek">Őket szeretném</a>
        </div>
        
        <div id="right_bar">
        
<?php
    if(!is_array($_SESSION['qlist'])) $_SESSION['qlist'] = array();
    foreach($_SESSION['qlist'] as $hiid => $qhirdetes) {
      echo $qhirdetes['html'];
    }
?>
        
        </div><!--/right_bar-->
      </div><!--/right_bar_frame-->
<?php
  }
  elseif(is_numeric($right_bar) && check_admin()) {
?>
      <div id="right_bar_frame">
        <div id="right_bar_title">
          Hirdetés adminisztráció
        </div>
        
        <div id="right_bar">
          <br/>
          &nbsp; <a href="hirdetes-esemenyek.php?hirdetes=<?php echo $right_bar; ?>">Események</a>
          <br/><br/>
          &nbsp; <a href="hirdetes-esemenyek.php?tipus=log&hirdetes=<?php echo $right_bar; ?>">Log</a>
          <br/><br/>
          &nbsp; <a href="hirdetes-statisztika.php?hirdetes=<?php echo $right_bar; ?>">Statisztika</a>
          <br/><br/>
          &nbsp; <a target="_blank" href="hirdetes-nyomtatasa.php?hirdetes=<?php echo $right_bar; ?>">Nyomtatás</a>
          <br/><br/>
        
        </div><!--/right_bar-->
      </div><!--/right_bar_frame-->
<?php
  }
  else {
?>

      <div id="right_bar_frame">
        
      </div><!--/right_bar_frame-->
<?php
  }
?>
    </div><!--/body-->
    
    <div id="foot">
      <div id="copy">www.petborze.hu - minden jog fenntartva</div>
      <?php
      /*
      <!--<div id="print">Nyomtatás</div>
      <div id="mail">E-mail</div>-->
      */
      ?>
      <div id="stilusvalto">
      <?php
        unset($_GET['stilus']);
        
        $this_query = '';
        foreach($_GET as $key => $val) {
          $this_query .= '&amp;'.$key.'='.htmlspecialchars($val);
        }
        
        $this_script = substr($_SERVER['PHP_SELF'], 1);
        $sep = '';
        if(count($styles) > 1) {
          foreach($styles as $key => $val) {
            if($stylesheet == $key) echo '<em>';
            echo $sep.'<a title="Az oldal kinézete legyen '.$val.'" href="'.$this_script.'?stilus='.$key.$this_query.'">'.$val.'</a>';
            if($stylesheet == $key) echo '</em>';
            
            if($sep == '') $sep = ' / ';
          }
        }
      ?>
      </div>
    </div><!--/foot-->
  
  </div><!--/container-->
  </div><!--/container_frame-->

  <div id="dark_overlay" style="display: none;"></div><!---->
  <div id="video_box_frame" style="display: none;"><!---->
    <div id="video_box_outer1"><div id="video_box_outer2"><div id="video_box_outer3"><div id="video_box_outer4">
      <a name="videobox"> </a>
      <div id="video_box_inner">
      
        <div id="video_box_top">
          <div id="video_box_logo"> </div>
          <div id="video_box_close" onclick="close_box();">Ablak bezárása</div>
        </div>
        
        <div id="video_box_content"> </div>
<?php /*        
        <div id="video_box_bottom">
          
        </div>
*/ ?>
      </div>
    </div></div></div></div>
  </div>
<?php
/*
<pre style="clear: both;">

  //var_dump($_POST);

</pre>
*/
?>
</body>
</html>
<?php add_stat(0); ?>