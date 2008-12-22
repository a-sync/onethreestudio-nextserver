<?php

include_once "db_access.php";

function lejaratiertesito($to = '', $subject = '', $message = '') {
	global $phpmailer;

	if (!is_object($phpmailer) || !is_a($phpmailer, 'PHPMailer')) {
		include_once('phpmailer/class.phpmailer.php');
		include_once('phpmailer/class.smtp.php');
		$phpmailer = new PHPMailer();
	}

	$phpmailer -> ClearAllRecipients();
	$phpmailer -> ClearAttachments();
	if ($file_attachment != '') {
		$phpmailer -> AddAttachment($file_attachment);

	}
	
	// v- EZT RAKD BE
	$phpmailer -> AddAddress($to);
	
	// v- EZT MEG VEDD KI
	//$phpmailer -> AddAddress('kocsmy@gmail.com');
	
	$phpmailer -> CharSet = "UTF-8";
	$phpmailer -> AddReplyTo('domain@nextserver.hu');
	$phpmailer -> From = 'domain@nextserver.hu';
	$phpmailer -> FromName = 'Nextserver Kft.';
	$phpmailer -> AddBCC('kocsmy@gmail.com');
	$phpmailer -> AddBCC('tamas.f@nextserver.hu');
	$phpmailer -> AddBCC('szamlazas@nextserver.hu');
	$phpmailer -> Subject = $subject;
	$phpmailer -> Body = $message;


	$phpmailer -> IsMail();
	$phpmailer -> IsHtml(true);
	if ($phpmailer -> Send()) {
		return true;
	}

	return false;
}


//kikuldendo emailek
$query = "SELECT a.id, a.nev, a.domainervenyesseg, b.ugyfelnev, b.email FROM domains a JOIN contacts b ON a.contact_id = b.contact_id WHERE FLOOR((a.domainervenyesseg - " . time() . ") / 86400) IN (15,30,60,90) AND a.livee = 1";
$result = mysql_query($query);
if (mysql_num_rows($result) > 0) {
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$lines[] = $line;
	}
}

//template hozza
$query = "SELECT * FROM mailtemplates WHERE id = '3' LIMIT 1";
$result = mysql_query($query);
if (mysql_num_rows($result) > 0) {
	$template = mysql_fetch_array($result, MYSQL_ASSOC);
}

if (count($lines) > 0) {
	foreach($lines as $user) {
		$to = $user['email'];
		$subject = $template['subject'];
		$message = nl2br(sprintf($template['body'], $user['ugyfelnev'], $user['nev'], date("Y.m.d", $user['domainervenyesseg'])));
		lejaratiertesito($to, $subject, $message);
		
		$query = "INSERT INTO maillog (time,email,domain,subject,content) VALUES (" . mysql_real_escape_string(time()) . ",'" . mysql_real_escape_string($to) . "','" . mysql_real_escape_string($user['id']). "','" . mysql_real_escape_string($subject) . "','" . mysql_real_escape_string($message) . "')";
		mysql_query($query);
	}
}
unset($lines,$line,$query,$result,$template,$user,$to,$subject,$message);





//kikuldendo emailek
$query = "SELECT a.id, a.nev, a.tarhelyervenyesseg, a.tarhely, b.ugyfelnev, b.email FROM domains a JOIN contacts b ON a.contact_id = b.contact_id WHERE FLOOR((a.tarhelyervenyesseg - " . time() . ") / 86400) IN (15,30,60) AND a.livee = 1 AND a.tarhelyervenyesseg != a.regisztralva";
$result = mysql_query($query);
if (mysql_num_rows($result) > 0) {
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$lines[] = $line;
	}
}

//template hozza
$query = "SELECT * FROM mailtemplates WHERE id = '4' LIMIT 1";
$result = mysql_query($query);
if (mysql_num_rows($result) > 0) {
	$template = mysql_fetch_array($result, MYSQL_ASSOC);
}

if (count($lines) > 0) {
	foreach($lines as $user) {
		$to = $user['email'];
		$subject = $template['subject'];
		$message = nl2br(sprintf($template['body'], $user['ugyfelnev'], $user['tarhely'], date("Y.m.d",$user['tarhelyervenyesseg']), $user['nev']));
		lejaratiertesito($to, $subject, $message);
		
		$query = "INSERT INTO maillog (time,email,domain,subject,content) VALUES (" . mysql_real_escape_string(time()) . ",'" . mysql_real_escape_string($to) . "','" . mysql_real_escape_string($user['id']). "','" . mysql_real_escape_string($subject) . "','" . mysql_real_escape_string($message) . "')";
		mysql_query($query);
	}
}








?>
