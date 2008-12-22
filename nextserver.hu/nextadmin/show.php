<?php

if (isset($_GET['delete'])) {
	$id = round($_GET['delete']);
	$query = "SELECT COUNT(d1.id) AS counter FROM `domains` d1 JOIN domains d2 ON d1.contact_id = d2.contact_id AND d1.id != d2.id WHERE d1.id = $id";
	$result = mysql_query($query) or die($query . mysql_error());
	if (mysql_num_rows($result) > 0) {
		$line = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$query = "SELECT * FROM domains d JOIN contacts c ON d.contact_id = c.contact_id WHERE d.id = $id";
		$result = mysql_query($query) or die($query . mysql_error());
		$info = mysql_fetch_array($result, MYSQL_ASSOC);

		if ($line['counter'] == 0) {
			$query = "DELETE FROM contacts WHERE contact_id = $info[contact_id] LIMIT 1";
			$result = mysql_query($query) or die($query . mysql_error());
		}
		$query = "DELETE FROM domains WHERE id = $id LIMIT 1";
		$result = mysql_query($query) or die($query . mysql_error());
	} else {
		echo 'nem megy';
	}
}

if (isset($_GET['id'])) {
	$id = round($_GET['id']);
	$query = "SELECT * FROM domains d JOIN contacts c ON d.contact_id = c.contact_id WHERE d.id = $id";
	$result = mysql_query($query) or die($query . mysql_error());
	if (mysql_num_rows($result) > 0) {
		$line = mysql_fetch_array($result, MYSQL_ASSOC);
		echo "<table><tr>
			<td>Domain ID</td>
			<td class=\"data\">$line[id]</td>
		</tr><tr>
			<td>Contact ID</td>
			<td class=\"data\">$line[contact_id]</td>
		</tr><tr>
			<td>Domain név</td>
			<td class=\"data\"><a class=\"link_external\" href=\"http://$line[nev]\">$line[nev]</a></td>
		</tr><tr>
			<td>Kiküldött emailek</td>
			<td class=\"data\"><a href=\"main.php?tab=maillog&filter=" . rawurlencode($line['nev']) . "\">link</a></td>
		</tr><tr>
			<td>Létrehozva</td>
			<td class=\"data\">" .date("Y m d H:i:s",$line['letrehozva']) . "</td>
		</tr><tr>
			<td>Regisztrálva</td>
			<td class=\"data\">" .date("Y m d",$line['regisztralva']) . "</td>
		</tr><tr>
			<td>Domain lejárat</td>
			<td class=\"data\">" .date("Y m d",$line['domainervenyesseg']) . "</td>
		</tr><tr>
			<td>Tárhely típusa</td>
			<td class=\"data\">$line[tarhely] csomag</td>
		</tr><tr>
			<td>Tárhely lejárat</td>
			<td class=\"data\">" .date("Y m d",$line['tarhelyervenyesseg']) . "</td>
		</tr><tr>
			<td>Megjegyzés</td>
			<td class=\"data\">$line[megjegyzes]</td>
		</tr><tr>
			<td>Kapcsolódó dokumentumok</td>
			<td class=\"data\">";
		$storedfiles = explode(";",$line['dokumentumok']);
		if (count($storedfiles) > 0 && $storedfiles[0] != '') {
			foreach($storedfiles as $storedfile) {
				echo '<a class="link_file" href="' . $storedfile . '">' . str_replace("./uploads/","",$storedfile) . '</a><br/>';
			}
		}
		echo "</td>
		</tr><tr>
			<td>Live</td>
			<td class=\"data\">" . ($line['livee'] ? 'igen' : 'nem') . "</td>
		</tr><tr>
			<td>Domain check</td>
			<td class=\"data\"><a class=\"link_external\" href=\"http://www.domain.hu/domain/regcheck/?dname=$line[nev]&host=nextserver.hu&K%E9rdezz%21=K%E9rdezz%21\">domain.hu</a></td>
		</tr><tr>
			<td>Üdvözölve</td>
			<td class=\"data\">" . ($line['udvozolvee'] ? 'igen' : 'nem') . "</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td class=\"data\">&nbsp</td>
		</tr>
		
		<tr>
			<td>Login</td>
			<td class=\"data\">$line[login]</td>
		</tr><tr>
			<td>Password</td>
			<td class=\"data\">$line[password]</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td class=\"data\">&nbsp</td>
		</tr>
		
		<tr>
			<td>Kapcsolattartó név</td>
			<td class=\"data\">$line[ugyfelnev]</td>
		</tr><tr>
			<td>Címe</td>
			<td class=\"data\">$line[cim]</td>
		</tr><tr>
			<td>E-mail</td>
			<td class=\"data\"><a class=\"link_email\" href=\"mailto:" . trim($line['email']) . "?subject=" . urlencode($line['nev']) . "&body=" . rawurlencode("Tisztelt $line[ugyfelnev]!\n\n") . "\">$line[email]</a></td>
		</tr><tr>
			<td>Telefon</td>
			<td class=\"data\">$line[telefon]</td>
		</tr><tr>
			<td>Számlázási adatok</td>
			<td class=\"data\">$line[szamlazasiadatok]</td>
		</tr></table>";
		?>
		<div id="footer">
		<a href="main.php?tab=add&contact=<?php echo $line['contact_id']; ?>"><img src="images/same.png" alt="Új domain ezzel a kapcsolattal" title="Új felvitele"/></a>
        <a href="main.php?tab=add&modify=<?php echo $line['id']; ?>"><img src="images/edit.png" alt="Módosítás" title="Módosítás"/></a>
        <a href="main.php?tab=show&delete=<?php echo $line['id']; ?>"><img src="images/delete.png" alt="Törlés" title="Elem törlése" align="right"/></a>
		</div>
		<?php
	} else {
		echo 'nincs ilyen';
	}
}

?>
