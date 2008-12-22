<?php
if (isset($_GET['id'])) {
	$id = round($_GET['id']);
	$query = "SELECT m.*, d.*, c.ugyfelnev FROM maillog m LEFT JOIN domains d ON m.domain = d.id LEFT JOIN contacts c ON d.contact_id = c.contact_id WHERE m.m_id = '" . mysql_real_escape_string($id) . "'";
	$result = mysql_query($query) or die('listing the email: ' . mysql_error());
	if (mysql_num_rows($result) > 0) {
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$lines[] = $line;
		}
		$line = $lines[0];
		echo "<table><tr>
			<td>kiküldve</td><td class=\"data\">" .date("Y m d H:i:s",$line['time']) . "</td>
		</tr><tr>
			<td>email cím</td><td class=\"data\"><a class=\"link_email\" href=\"mailto:" . trim($line['email']) . "?subject=" . urlencode($line['nev']) . "&body=" . rawurlencode("Tisztelt $line[ugyfelnev]!\n\n") . "\">$line[email]</a></td>
		</tr><tr>
			<td>domain</td><td class=\"data\"><a href=\"main.php?tab=show&id=$line[domain]\">$line[nev]</a></td>
		</tr><tr>
			<td> </td><td> </td>
		</tr><tr>
			<td>tárgy</td><td class=\"data\">$line[subject]</td>
		</tr><tr>
			<td valign=\"top\">szöveg</td><td>" . nl2br($line['content']) . "</td>
		</tr></table>";
	}
}

?>
