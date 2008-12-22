<?php
function mailkuldes($template,$log_id,$to1,$to2 = '',$param1 = '',$param2 = '',$param3 = '',$param4 = '') {

	$query = "SELECT * FROM mailtemplates WHERE id = $template LIMIT 1";
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) {
		$line = mysql_fetch_array($result, MYSQL_ASSOC);
		if ($line['subject'] != '' && $line['body'] != '') {
			$to = "$to2<$to1>";
			$subject = $line['subject'];
			$message = sprintf($line['body'],$to2,$param1,$param2,$param3,$param4);
			$headers = "From: Zsolt Kocsmárszky <hello@nextserver.hu>\n";
			$headers .= "Bcc: kocsmy@gmail.com\n";
			$headers .= "Reply-To: hello@nextserver.hu\n";
			$headers .= "Content-type: text/plain; charset=utf-8\n";
			$headers .= "X-Mailer: PHP/" . phpversion();
			$headers .= "\n\n";

			$success = mail(utf8_decode($to),utf8_decode($subject),$message,utf8_decode($headers));
			if ($success == 0) {
				$error .= 'omfg nem ment el az email';
			}
			$query = "INSERT INTO maillog (time,email,domain,subject,content) VALUES (" . mysql_real_escape_string(time()) . ",'" . mysql_real_escape_string($to1) . "','" . mysql_real_escape_string($log_id). "','" . mysql_real_escape_string($subject) . "','" . mysql_real_escape_string($message) . "')";
			$result = mysql_query($query) or die($query . mysql_error());
			if (mysql_affected_rows() != 1) {
				$error .= 'omfg nem tudtam lelogolni az emailküldést';
			}
		} else {
			$error .= 'megvan a template de baszki üres';
		}
	} else {
		$error .= 'nincs ilyen template, nem tudok így emailt küldeni';
	}
	return $error;
}
?>
