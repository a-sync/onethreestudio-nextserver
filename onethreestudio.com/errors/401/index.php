<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2002/REC-xhtml1-20020801/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>One Three Studio</title>

    <meta name="keywords" lang="hu" content="One Three Studio, kocsmy, haggi, vector, vector akashi, Webdesign, Creative, Design, Flash, Webprogramming, PhP, MySQL, Photoshop, Logodesign" />
    <meta name="keywords" lang="en" content="One Three Studio, kocsmy, haggi, vector, vector akashi, Webdesign, Creative, Design, Flash, Webprogramming, PhP, MySQL, Photoshop, Logodesign" />
    <meta name="description" content="One Three Studio - Webdesign, Creative, Design, Flash, Webprogramming, PhP, MySQL, Photoshop, Logodesign" />

    <meta name="author" content="One Three Studio" />
    <meta name="copyright" content="OneThreeStudio.com" />
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2" />

    <style type="text/css">
      body {
        text-align: center;
        background-image: url("../errors/spacer.png");
        background-repeat: repeat;
        margin: 0px;
      }
      #logo {
        text-align: center;
        background-image: url("../errors/logo.png");
        background-repeat: no-repeat;
        background-position: bottom center;
        margin: 0px;
        padding: 0px;
      }
      #welcome {
        text-align: center;
        background-image: url("../errors/welcome.png");
        background-repeat: no-repeat;
        background-position: top center;
        margin: 0px;
        padding: 0px;
      }
      #container {
        padding: 150px auto 0px auto;
        text-align: center;
      }
      #title {
        height: 35px;
        width: 40%;
        color: #f00;
        font-family: Arial, Verdana, Serif;
        font-weight: bolder;
        font-size: 22px;
        margin: 10px auto;
        padding: 25px auto;
        background-image: url("../errors/border.png");
        background-repeat: repeat-x;
        background-position: bottom center;
      }
      #text {
        color: #a7a7a7;
        font-family: Arial, Verdana, Serif;
        font-size: 16px;
      }
    </style>

  </head>
  <body>

    <div id="container">
      <div id="logo"><img src="../errors/logo.png" /></div>
      <div id="welcome"><img src="../errors/welcome.png" /></div>

      <h2 id="title">Error <?php print htmlspecialchars($_SERVER['REDIRECT_STATUS']);?>!</h2>

      <p id="text">

      <b>
        <?php print htmlspecialchars($_SERVER['REDIRECT_URL']);?>
      </b>

          <?php
            switch($_SERVER['REDIRECT_STATUS']) {
              case 401:
                print '<br /><br />Unauthorized!';
                break;
              case 403:
                print '<br /><br />Forbidden!';
                break;
              case 404:
                print '<br /><br />File/Directory Not Found!';
                break;
              case 500:
                print '<br /><br />Internal Server Error!';
                break;
            }
          ?>
      </p>

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