<?php

$now = date("U");

$query = "
	SELECT
		id,
		nev,
		email,
		ugyfelnev,
		telefon,
		regisztralva,
		IF(domainervenyesseg = regisztralva,0,1) AS nalunkvanadomain,
		IF(tarhelyervenyesseg = regisztralva,0,1) AS nalunkvanatarhely,
		FLOOR((domainervenyesseg - $now) / 86400) AS lejarodomain,
		FLOOR((tarhelyervenyesseg - $now) / 86400) AS lejarotarhely,
		livee
	FROM domains d 
	JOIN contacts c 
	ON d.contact_id = c.contact_id
	ORDER BY 
		lejarodomain DESC,
		lejarotarhely DESC,
		nev ASC
	";
$result = mysql_query($query) or die('listing all domains: ' . mysql_error());
if (mysql_num_rows($result) > 0) {
	
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$lines[] = $line;
	}
	?><table id="listtable"><thead><tr>
	<th>Domain név</th>
	<th>E-mail</th>
	<th>Contact név</th>
	<th>Telefon</th>
	<th>Regisztrálva</th>	
	<th>Lejárat</th>
	<th>Live?</th>
	</tr></thead><tbody><?
	foreach ($lines as $line) {	
		?><tr>
		<td><span><?= $line['nev'] ?></span><a href="main.php?tab=show&id=<?= $line['id'] ?>"><?= $line['nev'] ?></td>
		<td><a class="link_email" href="mailto:<?= trim($line['email']) ?>?subject=<?= urlencode($line['nev']) ?>&body=<?= rawurlencode('Tisztelt ' . $line['ugyfelnev'] . "!\n\n") ?>"><?= $line['email'] ?></a></td>
		<td><?= $line['ugyfelnev'] ?></td>
		<td><?= $line['telefon'] ?></td>
		<td><?= ($line['regisztralva'] > 0 ? date("Y m d",$line['regisztralva']) : '') ?></td>
		<td class="specko_kepek">
			<?
			if ($line['nalunkvanadomain'] == 0) {
			
				?><img class="domain_kep" src="images/domain_szurke.png" alt="" title="Nem nálunk van a domain" /><?
				
			} else {
			
				if ($line['lejarodomain'] > 60) {
					?><img class="domain_kep" src="images/domain_zold.png" alt="" title="<?= $line['lejarodomain'] ?> nap van hátra" /><?
				} elseif ($line['lejarodomain'] > 30) {
					?><img class="domain_kep" src="images/domain_sarga.png" alt="" title="<?= $line['lejarodomain'] ?> nap van hátra" /><?
				} elseif ($line['lejarodomain'] > 0) {
					?><img class="domain_kep" src="images/domain_piros.png" alt="" title="<?= $line['lejarodomain'] ?> nap van hátra" /><?
				} else {
					?><img class="domain_kep" src="images/domain_fekete.png" alt="" title="<?= $line['lejarodomain'] ?> napja lejárt te lusta geci" /><?
				}
				
			}			
			?> <?
			if ($line['nalunkvanatarhely'] == 0) {
			
				?><img class="tarhely_kep" src="images/tarhely_szurke.png" alt="" title="Nem nálunk van a tarhely" /><?
				
			} else {
			
				if ($line['lejarotarhely'] > 60) {
					?><img class="tarhely_kep" src="images/tarhely_zold.png" alt="" title="<?= $line['lejarotarhely'] ?> nap van hátra" /><?
				} elseif ($line['lejarotarhely'] > 30) {
					?><img class="tarhely_kep" src="images/tarhely_sarga.png" alt="" title="<?= $line['lejarotarhely'] ?> nap van hátra" /><?
				} elseif ($line['lejarotarhely'] > 0) {
					?><img class="tarhely_kep" src="images/tarhely_piros.png" alt="" title="<?= $line['lejarotarhely'] ?> nap van hátra" /><?
				} else {
					?><img class="tarhely_kep" src="images/tarhely_fekete.png" alt="" title="<?= $line['lejarotarhely'] ?> napja lejárt te lusta geci" /><?
				}
				
			}	
			?>
		</td>
		<td class="specko_jobbra">
			<?
		
			if ($line['livee'] == 1) {
				?><img class="livee_kep" src="images/online.png" alt="" /><?
			} else {
				?><img class="livee_kep" src="images/offline.png" alt="" /><?
			}
		
			?>
		</td>
		</tr>
		<?
	}
	echo '</tbody></table>';
}









?>
