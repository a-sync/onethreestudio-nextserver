<?php

if (isset($_POST['hiihaa']) && $_POST['id'] != '') {
	
	$query = "UPDATE mailtemplates SET body = '" . $_POST['body'] . "', subject = '" . $_POST['subject'] . "' WHERE id = '" . $_POST['id'] . "' LIMIT 1";
	$result = mysql_query($query) or die('updating: ' . mysql_error());

}

$query = "SELECT * FROM mailtemplates ORDER BY id ASC";
$result = mysql_query($query) or die('listing templates: ' . mysql_error());
if (mysql_num_rows($result) > 0) {
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo '<form action="main.php?tab=templates" method="post">
		<input type="hidden" name="id" value="' . $line['id'] . '" />
		<input type="text" name="subject" size="100" value="' . $line['subject'] . '"><br/>
		<textarea rows="22" name="body" cols="100">' . $line['body'] . '</textarea><br/>
		<input type="submit" name="hiihaa" value="Kész, mehet" />		
		</form>';
	}
}

?>
