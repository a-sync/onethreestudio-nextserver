<?php
require('admin/config.php');

$categories[0]['name'] = 'Főkategória';
$categories[0]['cat_id'] = 0;

$cat_res = mysql_query("SELECT * FROM `galeria` ORDER BY `name` ASC");
while($cat = mysql_fetch_assoc($cat_res)) { $categories[$cat['cat_id']] = $cat; }

if(!$categories[$_GET['cat']]) { $_cat = 0; }
else { $_cat = mysql_real_escape_string($_GET['cat']); }

$pic_res = mysql_query("SELECT * FROM `kepek` WHERE `cat_id` = '$_cat' ORDER BY `pic_id` ASC");
while($pic = mysql_fetch_assoc($pic_res)) { $pictures[$pic['pic_id']] = $pic; }

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
          }
        }
      }
    }

    if(document.images)
    {
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
    }
  </script>
</head>
<body>

	<div id="container">
    
    	<div id="header-container">
          <a name="up"></a>
          
          <div id="logo"> </div>
          
          <div id="menu">
              <div class="menu"><a href="index.php"><img src="images/fooldal.png" alt="Főoldal"/></a></div>
              <div class="menu active"><a href="galeria.php"><img src="images/galeria.png" alt="Galéria"/></a></div>
              <div class="menu"><a href="#arlistak"><img src="images/arlistak.png" alt="Árlisták"/></a></div>
              <div class="menu"><a href="#kapcsolat"><img src="images/kapcsolat.png" alt="Kapcsolat"/></a></div>
          </div>
      </div>
        
        <div id="content-container">

              <div id="camera">
              
                <div id="cam_inln">
                  <div class="box_bal" style="margin: 25px 0 0 0;">
                    <div id="hello">Galéria</div>
                    <div class="box">
<?php
  foreach($categories as $cat)
  {
    $mark = ($cat['cat_id'] == $_cat) ? ' &gt; ' : '';
    echo $mark.'<a href="?cat='.$cat['cat_id'].'">'.htmlspecialchars($cat['name']).'</a><br/>';
  }
?>
                    </div>
                  </div>
                </div>

                
              </div>

              <div id="szolgaltatasaink">
                  <div id="szol_head">Képek</div>
                  
<?php
  
  if(count($pictures) > 0) {
    $egy_sor = 3; // hány kép mehet egy sorba
    $szamlalo = 0;//minden harmadikat új sorba kell rakni
    foreach($pictures as $pic)
    {
      if($szamlalo == 0) {
        echo '<div class="gal_sor">';
      }

      $szamlalo++;

      echo '<div class="gal_box_bal">'
          .'<div class="gal_boxhead" title="Kategória: '.htmlspecialchars($categories[$pic['cat_id']]['name']).'">'.htmlspecialchars($pic['title']).'</div>'
          .'<div class="gal_box_jobb"><a href="'.$gal['kepek_mappa'].$pic['file'].'"><img src="'.$gal['kepek_thumb'].$pic['file'].'" alt="'.htmlspecialchars($pic['title']).'"/></a></div>'
          .'<div class="gal_box">&nbsp;'.str_replace('  ', ' $nbsp;', nl2br(htmlspecialchars($pic['desc']))).'</div>'
          .'</div>';
      
      if($szamlalo == $egy_sor) {
        echo '</div>';
        $szamlalo = 0;
      }
    }
    
    if($szamlalo != 0) {
      echo '</div>';
    }
  }
  else {
    echo '<div id="nincs_kep" class="box">Ebben a kategóriában nincsenek még képek...</div>';
  }
?>
    
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
