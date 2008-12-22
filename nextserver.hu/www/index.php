<?php

$head = array(
	'title' => 'Nextserver.hu - domain és tárhely - One-click CMS telepítés és egyéb hoszting szolgáltatások',
	'description' => 'Domain és Tárhely szolgáltatások kedvező áron, szuper egyszerű CMS telepítés - Joomla, WordPress és még rengeteg más hasznos program, amivel egyszerűen készíthetsz magadnak weboldalt!',
	'keywords' => 'tárhely, domain regisztráció, .hu domain, .eu domain, domain, tarhely, webhosting, webhoszting, 100mbit, olcsó, minőségi, cpanel, cms tárhely, drupal, joomla, wordpress, phpbb, egyszerű, költséghatékony, nextserver, next',
);




?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="verify-v1" content="iQPUHB+fTGjpXEGWZlEka78ca2VFm9aRRoJFf7NMl18=" />
	<title><?php echo $head['title']; ?></title>

	<script src="jquery.js" type="text/javascript"></script>
	<link href="images/favicon.png" rel="icon" type="images/png" />
	<script type="text/javascript">
	$(document).ready(function() {
		$("#domain_search select").change(function() { 
			if ($(this).val() == 'hu') {
				$(this).attr({"name":"tld"}).parent().attr({"action":"http://www.domain.hu/domain/szabad-e/"});
			} else {
				$(this).attr({"name":"tld[]"}).parent().attr({"action":"https://secure.domain.com/DDS/"});
			}
		});
	});
	</script>
	<link href="default.css" type="text/css" rel="stylesheet" />

	<meta name="description" content="<?php echo $head['description']; ?>" />
	<meta name="keywords" content="<?php echo $head['keywords']; ?>" />

	<meta name="revisit-after" content="2 days" />
	<meta name="googlebot" content="index, follow, archive" />
	<meta name="robots" content="all, index, follow" />
	<meta name="msnbot" content="all, index, follow" />

	<meta name="page-type" content="<?php echo $head['page-type']; ?>" />
	<meta name="subject" content="<?php echo $head['subject']; ?>" />

	<meta name="rating" content="general" />

	<meta name="Language" content="en" />
	<meta name="resource-type" content="document" />
	<meta name="Distribution" content="Global" />
	<meta name="Author" content="One Three Studio" />
	<meta name="Copyright" content="www.nextserver.hu" />

	<!--
	<link href="/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link href="/images/favicon.ico" rel="icon" type="image/x-icon" />
	<meta name="verify-v1" content="" />
	-->
	
	<!--[if lte IE 6]>
	  <link href="lte_ie6.css" type="text/css" rel="stylesheet" />
	<![endif]-->

</head>
<body>

	<div id="container">

	<div id="head">

		<div id="menu_nest">
		<a href="index.php" id="logo"> </a>
		<div id="menu">

			<a href="index.php" id="menu_fooldal" class="menu"> </a>
			<a href="index.php?module=domain" id="menu_domain" class="menu"> </a>
			<a href="index.php?module=tarhely" id="menu_tarhely" class="menu"> </a>
			<a href="index.php?module=segitseg" id="menu_segitseg" class="menu"> </a>
			<a href="/blog" id="menu_blog" class="menu"> </a>
			<a href="index.php?module=kapcsolat" id="menu_kapcsolat" class="menu"> </a>

		</div><!--/menu-->
		</div><!--/menu_nest-->
		
<?php

switch ($_REQUEST['module']) {
	case 'tarhely': include("tarhely.php"); break;
	case 'domain': include("domain.php"); break;
	case 'kapcsolat': include("kapcsolat.php"); break;
	case 'segitseg': include("segitseg.php"); break;
	case 'megrendeles': include("megrendeles.php"); break;
	case 'ugyfeleinkmondtak': include("ugyfeleinkmondtak.php"); break;
	case 'reszletek': include("reszletek.php"); break;

	default: include("main.php"); break;
}

?>

	<div id="foot_nest">

		<div id="foot">

		<div id="navigation">
			<ul>
			<li><a href="index.php">Főoldal</a></li>
			<li><a href="index.php?module=domain">Domain</a></li>
			<li><a href="index.php?module=tarhely">Tárhely</a></li>
			<li><a href="index.php?module=kapcsolat">Kapcsolat</a></li>
			</ul>
		</div><!--/navigation-->

		<div id="contact_info">
			<ul>
			<li>Telefon: 06 30 487 5479</li>
			<li>E-mail: hello@nextserver.hu</li>
			<li>Skype: nextserver.hello</li>
			</ul>
		</div><!--/contact_info-->
		<?php 

		if (isset($_REQUEST['hello_submit']) && $_REQUEST['contact'] != '' && $_REQUEST['contact'] == filter_var($_REQUEST['contact'], FILTER_VALIDATE_EMAIL) && $_REQUEST['text'] != '') {
			$to = 'hello@nextserver.hu';
			$subject = 'Nextserver hello üzenet';
			$header = 'From: hello@nextserver.hu' . "\r\n";
			$header .= 'Reply-To: hello@nextserver.hu' . "\r\n";
			$header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
			$msg = "Elérhetőség: " . $_REQUEST['contact'] . "\r\nSzöveg: " . $_REQUEST['text'] . "\r\n";
			@mail($to, $subject, $msg, $header);
			?>
			<h2>Köszönjük érdeklődését!</h2>
			<?php
		} else {
			if (isset($_REQUEST['hello_submit'])) {
				?><p class="hello_error">Nem megfelelő adatok</p><?php
			}
			?>
			<div id="hello">
				<form id="hello_box" method="post" action="">
					<h3>kapcsolat</h3>
					<input class="hello_contact" type="text" name="contact" maxlength="250" />
					<h3>üzenet</h3>
					<textarea class="hello_text" name="text" rows="3" cols="23"></textarea>
					<input class="hello_submit" type="submit" value="" name="hello_submit" />
				</form>
			</div><!--/hello-->
		<?php
		}		
		?>
		</div><!--/foot-->

	</div><!--/foot_nest-->

	</div><!--/container-->
	
<!-- Ez itt biza a GA code -->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-3450878-12");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>
