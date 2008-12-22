<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE>
<html>
<head>
<title>Nextserver Admin</title>

<link href="images/favicon.png" rel="icon" type="images/png" />

<link rel="stylesheet" href="jquery-ui-1.7.2.custom.css" type="text/css" />
<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="datatables/media/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
jQuery(document).ready(function() {
	$(".thisisdate").datepicker({dateFormat: '@', changeYear: true, showAnim: 'slideDown'})
	$("#listtable").dataTable({"iDisplayLength": 25, "bProcessing": true, "bStateSave": true, "bPaginate": true, "aaSorting": <?php if ($_GET['tab'] == 'maillog') echo "[[2,'desc']]"; else echo "[[0,'asc']]";?>})	
	$("#listtable_filter input").focus()
	<?php if (isset($_GET['filter'])) echo '$("#listtable_filter input").val("' . urldecode($_GET['filter']) . '").keyup()'; ?>
})
</script>

<link rel="stylesheet" type="text/css" href="default.css" title="defult" />
<link rel="stylesheet" type="text/css" href="datatables/media/js/jquery.dataTables.min.js" title="defult" />

</head>
<body>

<div id="container">
<?php

include_once "db_access.php";
include_once "mailkuldes.php";

?>

<div id="menu">
<a href="main.php"><img src="images/list.png" alt="Ügyfelek" title="Ügyfelek listázása"/></a> 
<a href="main.php?tab=batchmail"><img src="images/email.png" alt="E-mail küldése" title="E-mail küldése"/></a> 
<a href="main.php?tab=maillog"><img src="images/log.png" alt="Log" title="Log megtekintése"/></a>
<a href="main.php?tab=templates"><img src="images/templates.png" alt="Templatek" title="E-mail sablonok"/></a>
<a href="main.php?tab=add"><img src="images/add.png" alt="Hozzáadás" title="Ügyfel hozzáadása"/></a>
</div>

<?php

switch ($_GET['tab']) {
	case 'add': add_domain(); break;
	case 'show': show_domain(); break;
	case 'maillog': mail_log(); break;
	case 'mail': view_mail(); break;
	case 'templates': mail_templates(); break;
	case 'batchmail': batch_mail(); break;
	default: case 'list': list_domains(); break;
}


function list_domains() {
	include("list.php");
}


function add_domain() {	
	include("add.php");
}


function show_domain() {
	include("show.php");
}


function mail_log() {
	include("maillog.php");
}


function view_mail() {
	include("mailview.php");
}

function mail_templates() {
	include("mailtemplates.php");
}


function batch_mail() {
	include("batchmail.php");
}

?>


</div>
</body>
</html>
