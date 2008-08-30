<?php
require('admin/config.php');

$news_res = mysql_query("SELECT * FROM `hirek` ORDER BY `priority` DESC, `time` DESC LIMIT 0, 4");
while($hir = mysql_fetch_assoc($news_res)) { $news[$hir['hir_id']] = $hir; }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="Chromogenic Arts Kft honlapja, ipari, divat, modell és egyéb fotózás, professzionális minőségben!"/>
  <meta name="keywords" content="Chromogenic Arts, carts, fényképezés, fénykép, photo, fotó, fotosuli, fotósuli, Török György" />
  <meta name="copyright" content="www.carts.hu" />
  <meta name="revisit-after" content="1 day" />
  <title>Chromogenic Arts Kft.</title>

  <!--<link rel="stylesheet" type="text/css" href="default.css" title="basic" />-->

  <link rel="stylesheet" type="text/css" href="blue.css" title="kek" />
  <link rel="alternate stylesheet" type="text/css" href="red.css" title="piros" />
  <link rel="alternate stylesheet" type="text/css" href="green.css" title="zold" />

  <!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="ie_fix.css" />
  <![endif]-->

  <script type="text/javascript">
    function s(title) {
      var sheets = document.getElementsByTagName("link");
      for(i in sheets) {
        if(i != "length" && i != "item")
        {
          var s = sheets[i];
          s.disabled = true;
          if(s.getAttribute("title") == title && s.getAttribute("rel").indexOf("style") != -1) {
            s.disabled = false;
            // eseményváltó gombok színét is váltsa át
            selImg = "images/esem_" + title + ".png";
            n(n_sel);
          }
        }
      }
    }

      var preImg = [];
      var objImg = [];

      preImg[1] = "images/header_kek.png";
      preImg[2] = "images/hirbox_kek.png";
      preImg[3] = "images/ugras_kek.png";
      preImg[4] = "images/esem_kek.png";
      preImg[5] = "images/header_piros.png";
      preImg[6] = "images/hirbox_piros.png";
      preImg[7] = "images/ugras_piros.png";
      preImg[8] = "images/esem_piros.png";
      preImg[9] = "images/header_zold.png";
      preImg[10] = "images/hirbox_zold.png";
      preImg[11] = "images/ugras_zold.png";
      preImg[12] = "images/esem_zold.png";

      for(i in preImg)
      {
        objImg[i] = new Image();
        objImg[i].src = preImg[i];
      }

  </script>
</head>
<body>

	<div id="container">
    
    	<div id="header-container">
          <a name="up"></a>
          
          <div id="logo"> </div>
          
          <div id="menu">
              <div class="menu active"><a href="index.php"><img src="images/fooldal.png" alt="Főoldal"/></a></div>
              <div class="menu"><a href="galeria.php"><img src="images/galeria.png" alt="Galéria"/></a></div>
              <div class="menu"><a href="#arlistak"><img src="images/arlistak.png" alt="Árlisták"/></a></div>
              <div class="menu"><a href="#kapcsolat"><img src="images/kapcsolat.png" alt="Kapcsolat"/></a></div>
          </div>
      </div>
        
        <div id="content-container">

              <div id="camera">
                <div id="cam_inln">
                  <div class="box_bal" style="margin: 25px 0 0 0;">
                    <div id="hello">Üdvözöljük honlapunkon!</div>
                    <div class="boxhead">Miért pont mi?</div>
                    <div class="box">&nbsp;
                       Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.<br/>&nbsp;
                      Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
                    </div>
                  </div>
                  
                  <div class="box_jobb" style="margin: 85px 0 0 0;">
                  <img src="sample_08.jpg" alt="Pic-sample..."/>
                  </div>
                </div>
                
              </div>
              



              <div id="esemenykoveto">
                    <div id="hbox_bal_head">Eseménykövető</div>
                    <div id="hbox_jobb_head">Fotosuli - www.fotosuli.hu</div>
                    
                    <div id="hbox_bal">
                    <script type="text/javascript">
                    <!--
                        function _e(id, tag)
                        {
                          if (!tag || tag == "undefined") return document.getElementById(id); //get element by id
                          else return document.getElementsByTagName(tag)[id]; //get element by tag and tag id
                        }

                        var n_sel = 0;

                        var selImg = "images/esem_kek.png";
                        var unselImg = "images/esem_unsel.png";
                        var buttonPrefix = "esg-";
                        var boxPrefix = "est-";
                        function n(n_i)
                        {
                            _e(buttonPrefix + n_i).style.backgroundImage = "url('" + selImg + "')";
                            _e(boxPrefix + n_i).style.display = "";
                            if(n_sel != n_i)
                            {
                              _e(buttonPrefix + n_sel).style.backgroundImage = "url('" + unselImg + "')";
                              _e(boxPrefix + n_sel).style.display = "none";
                              n_sel = n_i;
                            }

                          return false;
                        }
                    -->
                    </script>
                    <script type="text/javascript">
                      <!--
                      n_sel = 1;
                      -->
                    </script>
                    
                        <div id="esgomb">
                        <?php
                          $sel = 0;
                          
                          foreach($news as $hir) {
                            $sel++;
                            $class = ($sel == 1) ? 'esgomb esgomb_sel' : 'esgomb';
                            
                            echo '<div id="esg-'.$sel.'" onclick="n('.$sel.');" class="'.$class.'">'.htmlspecialchars($hir['title']).'</div>';
                          }
                          
                        ?>
                        </div>
                        
                        
                        <?php
                          $sel = 0;
                          
                          foreach($news as $hir) {
                            $sel++;
                            $style = ($sel == 1) ? '' : ' style="display: none;"';

                            echo '<div id="est-'.$sel.'"'.$style .'>'
                                 .'<div class="estext">'.nl2br(htmlspecialchars($hir['text'])).'</div>'
                                 .'<span class="estext_date">'.date('Y.m.d.', $hir['time']).'</span>'
                                 .'</div>';
                          }
                        ?>
                        
                        
                    </div>
                    <div id="hbox_jobb">
                    Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius.
                    <br/>
                    <br/>
                      <div id="ugras">
                        <a href="http://www.fotosuli.hu">
                          <img src="images/blank.gif" width="207" height="33" alt="Ugrás a fotósulira!"/>
                        </a>
                      </div>
                    </div>
              </div>




              <div id="szolgaltatasaink">
                  <div id="szol_head">Szolgáltatásaink</div>
                  
                  
                  <!-- tömb eleje -->
                    <div class="box_bal">
                      <div class="boxhead">Variációk</div>
                      <div class="box">&nbsp;
                         There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. 
                      </div>
                    </div>
                    <div class="box_jobb">
                      <img src="sample_41.jpg" alt="Pic-sample..."/>
                    </div>
                  <!-- tömb vége -->
                  
                  
                  <!-- tömb eleje -->
                    <div class="box_bal">
                      <div class="boxhead">Reklám- és divatfotózás</div>
                      <div class="box">&nbsp;
                         It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                         The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.
                      </div>
                    </div>
                    <div class="box_jobb">
                      <img src="sample_08.jpg" alt="Pic-sample..."/>
                    </div>
                  <!-- tömb vége -->
                  
                  
                  <!-- tömb eleje -->
                    <div class="box_bal">
                      <div class="boxhead">Ipari fotózás</div>
                      <div class="box">&nbsp;
                         It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
                         The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
                      </div>
                    </div>
                    <div class="box_jobb">
                      <img src="sample_49.jpg" alt="Pic-sample..."/>
                    </div>
                  <!-- tömb vége -->
                    
                    
              </div>


        </div>
        
        
        <div id="foot-spacer"> </div>
        <div id="footer-container">
        
        	<div id="top-footer-container">
            <div class="thirtythree"> &nbsp; </div>
            <div id="copy">© 2008 Chromogenic Arts Kft.</div>
            <div id="top"><a href="#up"><img src="images/top.png" alt="Jump to Top!"/></a></div>
       		</div>
        
        	<div id="bottom-footer-container">
        	
              <div id="stripes">
              
                <script type="text/javascript">
                <!--

                -->
                </script>
              
              
                <img src="images/footer_stripes.png" usemap="#colors"/>
                <map name="colors">
                  <!--
                    stripes:
                    \g\ \r\ \b\
                     \g\ \r\ \b\
                      \g\ \r\ \b\
                       \g\ \r\ \b\
                  -->
                  <area shape="polygon" coords="0,50, 0,73, 110,174, 134,174" href="javascript:s('kek');" title="Kék színséma!">
                  <area shape="polygon" coords="0,76, 0,95, 87,174, 107,174" href="javascript:s('piros');" title="Piros színséma!">
                  <area shape="polygon" coords="0,100, 0,123, 56,174, 84,174" href="javascript:s('zold');" title="Zöld színséma!">
                </map>
              </div>
              
              <div id="foot_menu">
                Főoldal Galéria Árlisták Kapcsolat
                
                <div id="ots">
                  <img src="images/footer_ots.png" usemap="#ots"/>
                  <map name="ots">
                    <area shape="polygon" coords="0,20, 8,16, 21,18, 25,23, 65,23, 69,17, 102,17, 102,23, 159,24, 159,54, 0,54" href="http://onethreestudio.com"/>
                  </map>
                </div>
              </div>
              
              <div id="cam">
              </div>
              
        	</div>
        
        </div>
    
    
    </div>

</body>
</html>
