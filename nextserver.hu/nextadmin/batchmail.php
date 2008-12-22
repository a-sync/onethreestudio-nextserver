<?php
$now = date("U");

if (isset($_POST['batchkuldes'])) {

	switch($_POST['to']) {
		case 'mindenki': 
			$query = "SELECT email FROM contacts c JOIN domains d ON c.contact_id = d.contact_id GROUP BY c.contact_id";
			$result = mysql_query($query) or die($query . mysql_error());
			if (mysql_num_rows($result) > 0) {
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$to[] = $line['email'];
				}
			}
		break;
		case 'uj':
			$query = "SELECT email FROM contacts c JOIN domains d ON c.contact_id = d.contact_id WHERE d.regisztralva + 1814400 > $now GROUP BY c.contact_id";
			$result = mysql_query($query) or die($query . mysql_error());
			if (mysql_num_rows($result) > 0) {
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$to[] = $line['email'];
				}
			}
		break;
		case 'domain':
			$query = "SELECT email FROM contacts c JOIN domains d ON c.contact_id = d.contact_id WHERE (d.regisztralva + d.domainervenyesseg * 31536000) - $now < 2592000 AND (d.regisztralva + d.domainervenyesseg * 31536000) - $now >= 0 AND d.regisztralva > 0 GROUP BY c.contact_id";
			$result = mysql_query($query) or die($query . mysql_error());
			if (mysql_num_rows($result) > 0) {
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$to[] = $line['email'];
				}
			}			
		break;
		case 'tarhely':
			$query = "SELECT email FROM contacts c JOIN domains d ON c.contact_id = d.contact_id WHERE (d.regisztralva + d.tarhelyervenyesseg * 31536000) - $now < 2592000 AND (d.regisztralva + d.tarhelyervenyesseg * 31536000) - $now >= 0 AND d.regisztralva > 0 GROUP BY c.contact_id";
			$result = mysql_query($query) or die($query . mysql_error());
			if (mysql_num_rows($result) > 0) {
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$to[] = $line['email'];
				}
			}
		break;
		case 'egyeb':
			$to[] = $_POST['to2'];
		break;
	}
	print_r($to);
}
?>
<form action="main.php?tab=batchmail" method="post">
	<label>Kinek: <select name="to">
		<option value="mindenki">Mindenkinek</option>
		<option value="uj">Új ügyfelek</option>
		<option value="domain">Lejáró domain</option>
		<option value="tarhely">Lejáró tárhely</option>
		<option value="egyeb">Egyéb</option>
	</select></label>
	<label id="kinek2">Kinek: <input type="text" name="to2" value="" /></label>
	<label>Tárgy: <input type="text" name="subject" value="" /></label>
	<label>Szöveg: <textarea name="content" rows="5" cols="30"></textarea></label>
	<input type="submit" value="Küggyed!" name="batchkuldes" />
</form>


<script type="text/javascript">
$(document).ready(function() {

	$("#kinek2").hide();
	
	$("select[name=to]").change(function() {
		if ($(this).val() == 'egyeb') {
			$("#kinek2").show();
		} else {
			$("#kinek2").hide();
		}
	})
	
})
</script>
