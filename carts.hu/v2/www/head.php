<?php header("Content-Type: text/html; charset=utf-8"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <meta name="copyright" content="www.nextserver.hu" />
  <meta name="Author" content="www.nextserver.hu" />

  <meta name="googlebot" content="index, follow, archive" />
  <meta name="robots" content="all, index, follow" />
  <meta name="msnbot" content="all, index, follow" />

  <meta name="description" content="" />
  <meta name="keywords" content="" />

  <title>Carts</title>

  <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <link href="images/favicon.ico" rel="icon" type="image/x-icon" />

  <link rel="stylesheet" type="text/css" href="main.css" />

  <!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" href="main_lte_ie6.css" />
  <![endif]-->
  <!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="main_lte_ie7.css" />
  <![endif]-->
  
  <!--
	<link type="text/css" rel="stylesheet" href="/floatbox/floatbox.css" />
	<script type="text/javascript" src="/floatbox/floatbox.js"></script>
  
  <script type="text/javascript" src="default_main.js"></script>
  <script type="text/javascript" src="tooltip.js"></script>
  
  <script type="text/javascript" src="dbg_040.js"></script>
  <script type="text/javascript" src="swfobject.js"></script>
  -->
</head>
<body>

  <div id="container_frame">
  <div id="container">
    
    <div id="head">
      
      <a href="index.php" id="head_logo">
        
      </a>
      
      <div id="head_menu">
        
        <ul id="menu_ul">
        <?php
          $links['index'] = 'Főoldal,images/menu_fooldal';
          $links['szolgaltatasok'] = 'Szolgáltatlások,images/menu_szolg';
          $links['referenciak'] = 'Referenciák,images/menu_ref';
          $links['portfolio'] = 'Portfólió,images/menu_portf';
          $links['extrak'] = 'Extrák,images/menu_extrak';
          $links['kapcsolat'] = 'Kapcsolat,images/menu_kapcs';
          
          foreach($links as $page => $pic) {
            $pic = explode(',', $pic, 2);
            if($active_link == $page) echo '<li><a href="'.$page.'.php"><img alt="'.$pic[0].'" src="'.$pic[1].'_hover.png" /></a></li>';
            else echo '<li><a href="'.$page.'.php"><img alt="'.$pic[0].'" src="'.$pic[1].'.png" onmouseover="this.oldSrc=this.src;this.src=\''.$pic[1].'_hover.png\';" onmouseout="this.src=this.oldSrc;" /></a></li>';
            
          }
        ?>
        </ul>
        
      </div><!--/head_menu-->
      
    </div><!--/head-->
    
    <div id="body">