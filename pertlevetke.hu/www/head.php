<?php header("Content-Type: text/html; charset=utf-8"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

  <!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />-->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content=""/>
  <meta name="keywords" content="" />
  <meta name="copyright" content="www.pertlevetke.hu" />
  <meta name="revisit-after" content="1 day" />
  <title>Pertl Evetke</title>

  <link rel="stylesheet" type="text/css" href="style.css" />

  <!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" href="ie_lte6_fix.css" />

    <script type="text/javascript" src="iepngfix20a3_tilebg.js"></script>
  <![endif]-->

  <script type="text/javascript">
  function link(to)
  {
    window.location = to;
    return true;
  }

    var preImg = [];

    preImg[0] = "images/kezdolap.png";
    preImg[1] = "images/kezdolap_hover.png";

    preImg[2] = "images/galeria.png";
    preImg[3] = "images/galeria_hover.png";

    preImg[4] = "images/biografia.png";
    preImg[5] = "images/biografia_hover.png";

    preImg[6] = "images/kapcsolat.png";
    preImg[7] = "images/kapcsolat_hover.png";

    var objImg = [];
    for(i in preImg)
    {
      objImg[i] = new Image();
      objImg[i].src = preImg[i];
    }

  function swap(e, n)
  {
    e.src = preImg[n];
    return true;
  }
  </script>

  <!--lightbox2-->
    <link rel="stylesheet" href="lightbox/lightbox.css" type="text/css" media="screen" />

    <script type="text/javascript" src="lightbox/prototype.js"></script>
    <script type="text/javascript" src="lightbox/scriptaculous.js?load=effects,builder"></script>
    <script type="text/javascript" src="lightbox/lightbox.js"></script>
  <!--/lightbox2-->

</head>
<body>

  <div id="container"><!--container-->

    <div id="head">
      <div id="menu">
        <a href="index.php"><img src="images/kezdolap.png" alt="Kezdőlap" onmouseout="swap(this, 0);" onmouseover="swap(this, 1);"/></a>
        <a href="galeria.php"><img src="images/galeria.png" alt="Galéria" onmouseout="swap(this, 2);" onmouseover="swap(this, 3);"/></a>
        <a href="biografia.php"><img src="images/biografia.png" alt="Biográfia" onmouseout="swap(this, 4);" onmouseover="swap(this, 5);"/></a>
        <a href="kapcsolat.php"><img src="images/kapcsolat.png" alt="Kapcsolat" onmouseout="swap(this, 6);" onmouseover="swap(this, 7);"/></a>
      </div>
    </div>


    <div id="content_frame"><!--content_frame-->