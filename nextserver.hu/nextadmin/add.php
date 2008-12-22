<?

if (isset($_POST['booyaki'])) {
	//új felvétele
	if ($_POST['id'] == '') {

		if ($_POST['contact_id'] == '') {

			$query = "INSERT INTO contacts (ugyfelnev,cim,email,telefon,szamlazasiadatok) VALUES (
			'" . mysql_real_escape_string($_POST['ugyfelnev']) . "',
			'" . mysql_real_escape_string($_POST['cim']) . "',
			'" . mysql_real_escape_string($_POST['email']) . "',
			'" . mysql_real_escape_string($_POST['telefon']) . "',
			'" . mysql_real_escape_string($_POST['szamlazasiadatok']) . "'
			)";
			$result = mysql_query($query) or die("$query\n" . mysql_error());
			$contact_id = mysql_insert_id();

		} else {
			$contact_id = $_POST['contact_id'];
		}

		$query = "INSERT INTO domains (nev,contact_id,letrehozva,regisztralva,domainervenyesseg,domainfizetett,tarhely,tarhelyfizetett,tarhelyervenyesseg,megjegyzes,livee,udvozolvee,dokumentumok,login,password) VALUES (
		'" . mysql_real_escape_string($_POST['nev']) . "',
		'" . mysql_real_escape_string($contact_id) . "',
		'" . mysql_real_escape_string(date("U")) . "',
		'" . mysql_real_escape_string($_POST['regisztralva'] / 1000) . "',
		'" . mysql_real_escape_string($_POST['domainervenyesseg'] / 1000) . "',
		'" . mysql_real_escape_string($_POST['domainfizetett']) . "',
		'" . mysql_real_escape_string($_POST['tarhely']) . "',
		'" . mysql_real_escape_string($_POST['tarhelyfizetett']) . "',
		'" . mysql_real_escape_string($_POST['tarhelyervenyesseg'] / 1000) . "',
		'" . mysql_real_escape_string($_POST['megjegyzes']) . "',
		'" . mysql_real_escape_string($_POST['livee']) . "',
		'" . mysql_real_escape_string($_POST['udvozolvee']) . "',
		" . (count($_POST['files']) > 0 && $_POST['files'][0] != '' ? "'" . mysql_real_escape_string(implode(";",$_POST['files'])) . "', " : "'',") . "
		'" . mysql_real_escape_string($_POST['login']) . "',
		'" . mysql_real_escape_string($_POST['password']) . "'
		)";
		$result = mysql_query($query) or die("$query\n" . mysql_error());
		$domain_id = mysql_insert_id();

		echo '<script type="text/javascript">
		window.location = "main.php?tab=show&id=' . $domain_id . '";
		</script>';
	
	//módosítás
	} else {

		//új ügyfél felvétele
		if ($_POST['switch_contact_id'] == '') {
			echo 'contact fork';
			$query = "INSERT INTO contacts (ugyfelnev,cim,email,telefon,szamlazasiadatok) VALUES (
			'" . mysql_real_escape_string($_POST['ugyfelnev']) . "',
			'" . mysql_real_escape_string($_POST['cim']) . "',
			'" . mysql_real_escape_string($_POST['email']) . "',
			'" . mysql_real_escape_string($_POST['telefon']) . "',
			'" . mysql_real_escape_string($_POST['szamlazasiadatok']) . "'
			)";
			$result = mysql_query($query) or die("$query\n" . mysql_error());
			$contact_id = mysql_insert_id();

		//ügyfél váltás
		} elseif ($_POST['switch_contact_id'] != $_POST['contact_id']) {
		$query = "UPDATE domains SET
			contact_id = '" . mysql_real_escape_string($_POST['switch_contact_id']) . "'
			WHERE id = '" . mysql_real_escape_string($_POST['id']) . "'
			LIMIT 1";
			$result = mysql_query($query);
			$contact_id = $_POST['switch_contact_id'];

		//régi ügyfél adatainak módosítása
		} else {
			$query = "UPDATE contacts SET
			ugyfelnev = '" . mysql_real_escape_string($_POST['ugyfelnev']) . "',
			cim = '" . mysql_real_escape_string($_POST['cim']) . "',
			email = '" . mysql_real_escape_string($_POST['email']) . "',
			telefon = '" . mysql_real_escape_string($_POST['telefon']) . "',
			szamlazasiadatok = '" . mysql_real_escape_string($_POST['szamlazasiadatok']) . "'
			WHERE contact_id = '" . mysql_real_escape_string($_POST['contact_id']) . "' LIMIT 1";
			$result = mysql_query($query);
			$contact_id = $_POST['contact_id'];

		}

		//live email kiküldés
		$query = "SELECT * FROM domains WHERE id = '" . mysql_real_escape_string($_POST['id']) . "' LIMIT 1";
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0) {
			$line = mysql_fetch_array($result, MYSQL_ASSOC);
			if ($line['livee'] == 0 && $_POST['livee'] == 1) {
				$sendmail = 1;
			}
		} else {
			$error .= 'omfg nem tudom megnézni hogy kell-e live emailt kuldeni';
		}

		//üdvözlő email küldés
		$query = "SELECT * FROM domains WHERE id = '" . mysql_real_escape_string($_POST['id']) . "' LIMIT 1";
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0) {
			$line = mysql_fetch_array($result, MYSQL_ASSOC);
			if ($line['udvozolvee'] == 0 && $_POST['udvozolvee'] == 1) {
				$sendmail = 2;
			}
		} else {
			$error .= 'omfg nem tudom megnézni hogy kell-e üdvözlő emailt kuldeni';
		}

		$query = "UPDATE domains SET
		nev = '" . mysql_real_escape_string($_POST['nev']) . "',
		contact_id = '" . mysql_real_escape_string($contact_id) . "',
		regisztralva = '" . mysql_real_escape_string($_POST['regisztralva'] / 1000) . "',
		domainervenyesseg = '" . mysql_real_escape_string($_POST['domainervenyesseg'] / 1000) . "',
		domainfizetett = '" . mysql_real_escape_string($_POST['domainfizetett']) . "',
		tarhely = '" . mysql_real_escape_string($_POST['tarhely']) . "',
		tarhelyfizetett = '" . mysql_real_escape_string($_POST['tarhelyfizetett']) . "',
		tarhelyervenyesseg = '" . mysql_real_escape_string($_POST['tarhelyervenyesseg'] / 1000) . "',
		megjegyzes = '" . mysql_real_escape_string($_POST['megjegyzes']) . "',
		livee = '" . mysql_real_escape_string($_POST['livee']) . "',
		udvozolvee = '" . mysql_real_escape_string($_POST['udvozolvee']) . "',
		login = '" . mysql_real_escape_string($_POST['login']) . "',
		password = '" . mysql_real_escape_string($_POST['password']) . "'
		" . (count($_POST['files']) > 0 ? ", dokumentumok = '" . mysql_real_escape_string(implode(";",$_POST['files'])) . "'" : "") . "
		WHERE id = '" . mysql_real_escape_string($_POST['id']). "' LIMIT 1";
		$result = mysql_query($query);

		if ($sendmail == 1) {
			$error .= mailkuldes(1,$_POST['id'],$_POST['email'],$_POST['ugyfelnev'],$_POST['nev'],$_POST['login'],$_POST['password']);
		}
		if ($sendmail == 2) {
			$error .= mailkuldes(2,$_POST['id'],$_POST['email'],$_POST['ugyfelnev'],$_POST['nev']);
		}

		if ($error == '') {
			echo '<script type="text/javascript">
			window.location = "main.php?tab=show&id=' . $_POST['id'] . '";
			</script>';
		}
	}
}
if (isset($_GET['modify'])) {
	$id = round($_GET['modify']);
	$query = "SELECT * FROM domains d JOIN contacts c ON d.contact_id = c.contact_id WHERE d.id = $id";
	$result = mysql_query($query) or die('this domain: ' . mysql_error());
	if (mysql_num_rows($result) > 0) {
		$line = mysql_fetch_array($result, MYSQL_ASSOC);
	}
}
if (isset($_GET['contact'])) {
	$id = round($_GET['contact']);
	$query = "SELECT * FROM contacts WHERE contact_id = '" . mysql_real_escape_string($id). "'";
	$result = mysql_query($query) or die('this contact: ' . mysql_error());
	if (mysql_num_rows($result) > 0) {
		$line = mysql_fetch_array($result, MYSQL_ASSOC);
	}
}
?>
<?= $error; ?>
<form method="post" action="main.php?tab=add" class="domainplus">
	<input type="hidden" name="id" value="<?= $line['id']; ?>" />
	<fieldset>
		<legend>Domain adatok</legend>
		<label>Domain név: <input type="text" name="nev" value="<?= $line['nev']; ?>" /></label>
		<label>Regisztrálva: <input type="text" name="regisztralva" value="<?= ($line['regisztralva'] != '' ? $line['regisztralva'] * 1000 : ''); ?>" class="thisisdate" /></label>
		<label>Domain lejárat: <input type="text" name="domainervenyesseg" value="<?= ($line['domainervenyesseg'] != '' ? $line['domainervenyesseg'] * 1000 : ''); ?>" class="thisisdate" /></label>

		<label>Tárhely típusa:
			<select name="tarhely">
				<option></option>
				<option value="Minimal"<? if ($line['tarhely'] == 'Minimal') echo ' selected="selected"'?>>Minimal</option>
				<option value="Basic"<? if ($line['tarhely'] == 'Basic') echo ' selected="selected"'?>>Basic</option>
				<option value="Standard"<? if ($line['tarhely'] == 'Standard') echo ' selected="selected"'?>>Standard</option>
				<option value="Extra"<? if ($line['tarhely'] == 'Extra') echo ' selected="selected"'?>>Extra</option>
				<option value="Medium"<? if ($line['tarhely'] == 'Medium') echo ' selected="selected"'?>>Medium</option>
				<option value="Ultra"<? if ($line['tarhely'] == 'Ultra') echo ' selected="selected"'?>>Ultra</option>
				<option value="Topschool"<? if ($line['tarhely'] == 'Topschool') echo ' selected="selected"'?>>Topschool</option>
				<option value="Unlimited"<? if ($line['tarhely'] == 'Unlimited') echo ' selected="selected"'?>>Unlimited</option>
				<option value="Nincs tárhely"<? if ($line['tarhely'] == 'Nincs') echo ' selected="selected"'?>>Nincs tárhely</option>
				<option value="Parked"<? if ($line['tarhely'] == 'Parked') echo ' selected="selected"'?>>Parkolt</option>
				<option value="Addon"<? if ($line['tarhely'] == 'Addon') echo ' selected="selected"'?>>Addon</option>
				<option value="VPS"<? if ($line['tarhely'] == 'VPS') echo ' selected="selected"'?>>VPS</option>
				<option value="Orange"<? if ($line['tarhely'] == 'Orange') echo ' selected="selected"'?>>Orange</option>
				<option value="Egyedi"<? if ($line['tarhely'] == 'Egyedi') echo ' selected="selected"'?>>Egyedi</option>
			</select>
		</label>
		<label>Tárhely lejárat: <input type="text" name="tarhelyervenyesseg" value="<?= ($line['tarhelyervenyesseg'] != '' ? $line['tarhelyervenyesseg'] * 1000 : ''); ?>" class="thisisdate" /></label>

		<label>Megjegyzés: <textarea name="megjegyzes"><?= $line['megjegyzes']; ?></textarea></label>
		<label>Live?: <input type="checkbox" name="livee" value="1" <?= ($line['livee'] == '1' || ($line['livee'] == '' && !isset($_GET['modify'])) ? ' checked="checked"' : ''); ?> /></label>
		<label>Üdvözölve?: <input type="checkbox" name="udvozolvee" value="1" <? if ($line['udvozolvee'] == '1') echo ' checked="checked"'; ?> /></label>
		<label>Login: <input type="text" name="login" value="<?= $line['login']; ?>" /></label>
		<label>Password: <input type="text" name="password" value="<?= $line['password']; ?>" /></label>

		<script type="text/javascript" src="ajaxupload.3.5.js"></script>
		<script type="text/javascript">
		$(function(){
		var btnUpload=$('#upload');
		var status=$('#status');
		new AjaxUpload(btnUpload, {
			action: 'upload-file.php',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				status.text('Uploading...');
			},
			onComplete: function(file, response){
				status.text('');
				if(response==="success"){
					$('<label/>').append( $('<input/>').attr({name: "files[]", type: "text"}).val("./uploads/" + file + "")  ).appendTo('#files');
				} else{
					$('<p/>').appendTo('#files').text(file).addClass('error');
				}
			}
		});
		});
		</script>

		<!-- Upload Button-->
		<div id="upload">Upload File</div><span id="status"></span>
		<!--List Files-->
		<div id="files" >
		<? if (isset($_GET['modify'])) {
			$storedfiles = explode(";",$line['dokumentumok']);
			if (count($storedfiles) > 0 && $storedfiles[0] != '') {
				foreach($storedfiles as $storedfile) {
					echo '<label><input type="text" name="files[]" value="' . $storedfile . '" /><span>Delete</span></label>';
				}
			}
		} ?>
		</div>
		<script type="text/javascript">
		$(function(){
			$("#files label span").click(function() {
				$(this).parent().hide("slow",function() { $(this).remove() });
			})
		})
		</script>
	</fieldset>

	<? if (isset($_GET['modify'])) { ?>
		<label>
			Régi vagy új ügyfél
			<select name="switch_contact_id">
				<option value="">- Új ügyfél -</option>
				<?
				$query = "SELECT contact_id, ugyfelnev FROM contacts ORDER BY ugyfelnev ASC";
				$result = mysql_query($query);
				if (mysql_num_rows($result) > 0) {
					while ($contact = mysql_fetch_array($result, MYSQL_ASSOC)) {
						?><option value="<?= $contact['contact_id'] ?>"<?= ($line['contact_id'] == $contact['contact_id'] ? ' selected ': '') ?>><?= $contact['ugyfelnev'] ?> (<?= $contact['contact_id'] ?>)</option><?
					}
				}
				?>
			</select>
		</label>
	<? } ?>

	<input type="hidden" name="contact_id" value="<?= $line['contact_id']; ?>" />

	<fieldset>
		<legend>Ügyfél adatok</legend>
		<label>Ügyfél név: <input type="text" name="ugyfelnev" value="<?= $line['ugyfelnev']; ?>" /></label>
		<label>Cím: <input type="text" name="cim" value="<?= $line['cim']; ?>" /></label>
		<label>Email: <input type="text" name="email" value="<?= $line['email']; ?>" /></label>
		<label>Telefonszám: <input type="text" name="telefon" value="<?= $line['telefon']; ?>" /></label>
		<label>Számlázási adatok: <input type="text" name="szamlazasiadatok" value="<?= $line['szamlazasiadatok']; ?>" /></label>
	</fieldset>

	<input type="submit" name="booyaki" value="Kész, mehet"/>

</form>
