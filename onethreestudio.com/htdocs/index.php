<?php
require('admin/functions.php');

function infoTexts() {
  $query = "SELECT * FROM ots_info WHERE status != '0' ORDER BY status DESC";
  $queryresult = mysql_query($query);
  $resultnum = mysql_num_rows($queryresult);

  if($resultnum < 1) { echo '<div class="text-content">No info entry\'s</div>'; }//Nincsenek Portfolio info bejegyzések
  else {
    for($i = 0; $i < $resultnum; $i++) {
      $a = mysql_fetch_array($queryresult, MYSQL_ASSOC);
      echo '<div class="text-title">'.$a['title'].'</div><div class="text-content">'.$a['text'].'</div>';
    }
  }
}

function worksContents() {
  $query = "SELECT * FROM ots_references WHERE status != '0' ORDER BY status DESC";
  $queryresult = mysql_query($query);
  $resultnum = mysql_num_rows($queryresult);

  if($resultnum < 1) { echo '<div class="work">No work entry\'s</div>'; }//Nincsenek Referencia bejegyzések
  else {
    for($i = 0; $i < $resultnum; $i++) {
      $a = mysql_fetch_array($queryresult, MYSQL_ASSOC);
      $part1 = '<div class="work"><a title="'.$a['pic_text'].'" rel="lightbox[works]" href="'.$a['full_pic'].'"><img alt="" src="'.$a['crop_pic'].'" /></a><br />'.$a['pic_title'];
      if($a['link']) { $part2 = ' :: <a href="'.$a['link'].'">'.$a['link'].'</a>'; } else { $part2 = ''; }
      if($a['download']) { $part3 = ' :: <a href="'.$a['download'].'">Download!</a>'; } else { $part3 = ''; }
      $part4 = '</div>';
      echo $part1.$part2.$part3.$part4;
    }
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>

    <title>One Three Studio</title>

    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />

    <meta name="keywords" lang="hu" content="One Three Studio, kocsmy, haggi, vector, vector akashi, Webdesign, Creative, Design, Flash, Webprogramming, PhP, MySQL, Photoshop, Logodesign" />
    <meta name="keywords" lang="en" content="One Three Studio, kocsmy, haggi, vector, vector akashi, Webdesign, Creative, Design, Flash, Webprogramming, PhP, MySQL, Photoshop, Logodesign" />
    <meta name="description" content="One Three Studio - Webdesign, Creative, Design, Flash, Webprogramming, PhP, MySQL, Photoshop, Logodesign" />
    <meta name="author" content="One Three Studio" />
    <meta name="copyright" content="OneThreeStudio.com" />
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2" />
    <meta name="verify-v1" content="shWnYPO8nD7NHh+U3qPINBP6WEJo+EAJfMioxcTz974=" />

<!-- lightbox 2.04 -->
    <script src="js/prototype.js" type="text/javascript"></script>
    <script src="js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
    <script src="js/lightbox.js" type="text/javascript"></script>
<!-- lightbox 2.04 -->

  </head>
  <body>

    <div id="container">

      <div id="header">

        <div id="logo"></div>
        <div id="welcome-logo"></div>

      </div>

      <div id="content">

        <div id="info">

          <div id="texts">

            <?php infoTexts(); ?>

          </div>
          <div class="pic"><a href="http://onethreestudio.com/cmd" title="Alternative, command line version!"><img alt="" src="images/map.png" /></a></div>

        </div>

        <div id="works">

          <div id="works-title"><img alt="" src="images/title3.png" /></div>
          <div id="works-content">

            <?php worksContents(); ?>

          </div>

        </div>

      </div>

      <div id="footer">
        <a href="http://www.onethreestudio.com">www.onethreestudio.com</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        All Rights Reserved!
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <i style="display: none;">&lt;</i>info<i style="display: none;">_</i>@<i style="display: none;">_</i>onethreestudio.com<i style="display: none;">&gt;</i>
        <br />
        <div id="foot-border"></div>
      </div>

    </div>

    <script type="text/javascript">
      var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
      document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
      var pageTracker = _gat._getTracker("UA-3450878-1");
      pageTracker._initData();
      pageTracker._trackPageview();
    </script>

  </body>
</html>