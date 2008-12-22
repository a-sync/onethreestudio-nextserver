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
                  <div class="reg_col_left_row">Kategória:</div>
                  <div class="reg_col_left_row">Jelleg:</div>
                  <div class="reg_col_left_row">Szobák száma:</div>
                  <div class="reg_col_left_row">Alapterület (m<sup>2</sup>):</div>
                  <div class="reg_col_left_row">Ár (Ft):</div>
                  <div class="reg_col_left_row">Szintek száma:</div>
                  <div class="reg_col_left_row">Garázs:</div>
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
                    <select name="kategoria" id="reg_kategoria" onchange="search_results(this);">
                      <option value="-">(Mindegy...)</option>
                      <?php
                        foreach($data['kategoria'] as $key => $val) {
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="search_col_right_row">
                    <select name="jelleg" id="reg_jelleg" onchange="search_results(this);">
                      <option value="">(Mindegy...)</option>
                      <?php
                        foreach($data['jelleg'] as $key => $val) {
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <div class="search_col_right_row">
                    <input name="szobak_tol" id="reg_szobak_tol" onkeyup="search_results(this, 1);" type="text" maxlength="10" value="<?php echo $hirdetes['szobak']; ?>" />
                     - 
                    <input name="szobak_ig" id="reg_szobak_ig" onkeyup="search_results(this, 1);" type="text" maxlength="10" value="<?php echo $hirdetes['szobak']; ?>" />
                  </div>
                  
                  <div class="search_col_right_row">
                    <input name="alapterulet_tol" id="reg_alapterulet_tol" onkeyup="search_results(this);" type="text" maxlength="10" value="<?php echo $hirdetes['alapterulet']; ?>" />
                     - 
                    <input name="alapterulet_ig" id="reg_alapterulet_ig" onkeyup="search_results(this);" type="text" maxlength="10" value="<?php echo $hirdetes['alapterulet']; ?>" />
                  </div>
                  
                  <div class="search_col_right_row">
                    <input name="ar_tol" id="reg_ar_tol" onkeyup="search_results(this);" type="text" maxlength="10" value="<?php echo $hirdetes['ar']; ?>" />
                     - 
                    <input name="ar_ig" id="reg_ar_ig" onkeyup="search_results(this);" type="text" maxlength="10" value="<?php echo $hirdetes['ar']; ?>" />
                  </div>
                  
                  <div class="search_col_right_row">
                    <input name="szintek_tol" id="reg_szintek_tol" onkeyup="search_results(this, 1);" type="text" maxlength="10" value="<?php echo $hirdetes['szintek']; ?>" />
                     - 
                    <input name="szintek_ig" id="reg_szintek_ig" onkeyup="search_results(this, 1);" type="text" maxlength="10" value="<?php echo $hirdetes['szintek']; ?>" />
                  </div>
                  
                  <div class="search_col_right_row">
                    <select name="garazs" id="reg_garazs" onchange="search_results(this);">
                      <option value="">(Mindegy...)</option>
                      <?php
                        foreach($data['garazs'] as $key => $val) {
                          echo '<option value="'.$key.'">'.$val.'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  
                  <?php/*<div class="reg_col_right_row"><input name="kuldes" id="reg_submit" type="submit" value="Keresés (Találat: 0db)" /></div>*/?>
                  <div class="reg_col_right_row"><input id="reg_submit" type="submit" value="Keresés (Találat: <?php echo $_osszes; ?>db)" /></div>
                </div>
            </div>
            
          </form>
          
        </div><!--/content-->

        

      </div><!--/content_frame-->

<?php

include 'foot.php';
?>