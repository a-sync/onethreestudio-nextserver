<?php
include 'functions.php';

$hirdetes = false; // debug

include 'head.php';
?>

      <div id="content_frame">
      
        <div id="content_head">
          <h1 id="content_title">
            Keresés
            <span></span>
          </h1>
          <div id="content_title_right">
            
          </div>
        </div><!--/content_head-->
        
        <div id="content">
          
          <form method="get" action="hirdetesek.php">

            <div class="reg_row">
                <div class="reg_col_left">
                  <div class="reg_col_left_row">Régió:</div>
                  <div class="reg_col_left_row">Település:</div>
                  <div class="reg_col_left_row">Városrész / kerület:</div>
                  <div class="reg_col_left_row">Faj:</div>
                  <div class="reg_col_left_row">Fajta:</div>
                  <div class="reg_col_left_row">Kor:</div>
                  <div class="reg_col_left_row">Ár (Ft):</div>
                  <div class="reg_col_left_row">Nem:</div>
                  <div class="reg_col_left_row">Pedigré:</div>
                </div>
                
                <div class="reg_col_right">
                  <div class="search_col_right_row">
                    <select name="regio" id="reg_regio" onchange="regio_valasztas(this);">
                      <option value="-">(Mindegy...)</option>
                      <?php
                        foreach($data['regio'] as $key => $val) {
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="search_col_right_row" id="reg_telepules_div">
                    <select name="telepules" id="reg_telepules" onchange="telepules_valasztas(this);"<?php if(!$data['telepules'][$hirdetes['regio']]) echo ' disabled="disabled"'; ?>>
                      <option value="-">(Mindegy...)</option>
                      <?php
                        if($hirdetes['regio'] != '' && $hirdetes['telepules'] != '') {
                          foreach($data['telepules'][$hirdetes['regio']] as $key => $val) {
                            echo '<option value="'.$key.'">'.$val.'</option>';
                          }
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="search_col_right_row" id="reg_varosresz_div">
                    <select name="varosresz" id="reg_varosresz" onchange="search_results(this);"<?php if(!$data['varosresz'][$hirdetes['regio']][$hirdetes['telepules']]) echo ' disabled="disabled"'; ?>>
                      <option value="-">(Mindegy...)</option>
                      <?php
                        if($hirdetes['regio'] != '' && $hirdetes['telepules'] != '' && $hirdetes['varosresz'] != '') {
                          foreach($data['varosresz'][$hirdetes['regio']][$hirdetes['telepules']] as $key => $val) {
                            echo '<option value="'.$key.'">'.$val.'</option>';
                          }
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="search_col_right_row">
                    <select name="faj" id="reg_faj" onchange="faj_valasztas(this);">
                      <option value="-">(Mindegy...)</option>
                      <?php
                        foreach($data['faj'] as $key => $val) {
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="search_col_right_row" id="reg_fajta_div">
                    <select name="fajta" id="reg_fajta" onchange="search_results(this);"<?php if(!$data['fajta'][$hirdetes['faj']]) echo ' disabled="disabled"'; ?>>
                      <option value="-">(Mindegy...)</option>
                      <?php
                        if($hirdetes['faj'] != '' && $hirdetes['fajta'] != '') {
                          foreach($data['fajta'][$hirdetes['faj']] as $key => $val) {
                            echo '<option value="'.$key.'">'.$val.'</option>';
                          }
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="search_col_right_row">
                    <select name="kor" id="reg_kor" onchange="search_results(this);">
                      <option value="">(Mindegy...)</option>
                      <?php
                        foreach($data['kor'] as $key => $val) {
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="search_col_right_row">
                    <input style="background-image: url('default_images/zero_bg.png');" onfocus="this.style.backgroundImage='none';" name="ar_tol" id="reg_ar_tol" onkeyup="search_results(this, 0);" type="text" maxlength="10" value="<?php echo $hirdetes['ar']; ?>" />
                     - 
                    <input style="background-image: url('default_images/inf_bg.png');" onfocus="this.style.backgroundImage='none';" name="ar_ig" id="reg_ar_ig" onkeyup="search_results(this, 0);" type="text" maxlength="10" value="<?php echo $hirdetes['ar']; ?>" />
                  </div>
                  
                  <div class="search_col_right_row">
                    <select name="nem" id="reg_nem" onchange="search_results(this);">
                      <option value="">(Mindegy...)</option>
                      <?php
                        foreach($data['nem'] as $key => $val) {
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="search_col_right_row">
                    <select name="pedigre" id="reg_pedigre" onchange="search_results(this);">
                      <option value="">(Mindegy...)</option>
                      <?php
                        foreach($data['pedigre'] as $key => $val) {
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <?php/*<div class="reg_col_right_row"><input name="kuldes" id="reg_submit" type="submit" value="Keresés (Találat: 0db)" /></div>*/?>
                  <div class="reg_col_right_row"><input id="reg_submit" type="submit" value="Keresés (Találat: <?php echo kategoriak_kiirasa(false); ?>db)" /></div>
                </div>
            </div>
            
          </form>
          
        </div><!--/content-->

        

      </div><!--/content_frame-->

<?php

include 'foot.php';
?>