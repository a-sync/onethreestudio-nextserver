<?php

$query = "SELECT * FROM maillog m LEFT JOIN domains d ON m.domain = d.id ORDER BY m_id DESC";
$result = mysql_query($query) or die('listing all emails: ' . mysql_error());
if (mysql_num_rows($result) > 0) {
	
	echo '<table id="listtable"><thead><tr>
	<th>Domain</th>
	<th>Kinek</th>
	<th>Mikor</th>	
	<th>Tárgy</th>
	</tr></thead><tbody>';
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$lines[] = $line;
	}
	foreach ($lines as $line) {	
		echo "<tr>
		<td><span>$line[nev]</span><a href=\"main.php?tab=mail&id=$line[m_id]\">$line[nev]</a></td>
		<td>$line[email]</td>
		<td>" . date("Y m d H:i:s",$line['time']) . "</td>		
		<td>$line[subject]</td>
		</tr>";
	}
	echo '</tbody></table>';

}

?>
