		<div id="packages_nest">

		<div id="packages">

			<div id="packages_info">
			<h3>Üdvözöljük!</h3>
			<h1>Csapatunk olyan szolgáltatásokat kínál Önnek, melyek kényelmesebbé és hatékonyabbá teszik az Ön webes jelenlétét!</h1>
			<ul id="contact">
				<li>Telefon: 06 30 487 5479</li>
				<li>E-mail: hello@nextserver.hu</li>
				<li>Skype: nextserver.hello</li>
			</ul>
			</div><!--/packages_info-->

			<div id="basic_package" class="package">
			<ul class="package_info">
				<li>2,5 GB tárhely</li>
				<li>1 db .hu/.com/.net domain</li>
				<li>korlátlan subdomain</li>
				<li>5 db e-mail cím</li>
				<li>100 Mbites kapcsolat</li>
				<li>php + mysql</li>
				<li>1db FTP hozzáférés</li>
			</ul>
			<a id="basic_price" class="package_price" href="index.php?module=megrendeles&amp;csomag=basic"> </a>
			<a class="order_package2" href="index.php?module=tarhely"> </a>
			</div>

			<div id="extra_package" class="package">
			<ul class="package_info">
				<li>5 GB tárhely</li>
				<li>1 db .hu/.com/.net domain</li>
				<li>korlátlan subdomain</li>
				<li>15 db e-mail cím</li>
				<li>100 Mbites kapcsolat</li>
				<li>php + mysql</li>
				<li>3db FTP hozzáférés</li>
			</ul>
			<a id="extra_price" class="package_price" href="index.php?module=megrendeles&amp;csomag=extra"> </a>
			<a class="order_package2" href="index.php?module=tarhely"> </a>
			</div>

			<div id="ultra_package" class="package">
			<ul class="package_info">
				<li>10 GB tárhely</li>
				<li>1 db .hu/.com/.net domain</li>
				<li>korlátlan subdomain</li>
				<li>korlátlan e-mail cím</li>
				<li>100 Mbites kapcsolat</li>
				<li>php + mysql</li>
				<li>5db FTP hozzáférés</li>
			</ul>
			<a id="ultra_price" class="package_price" href="index.php?module=megrendeles&amp;csomag=ultra"> </a>
			<a class="order_package2" href="index.php?module=tarhely"> </a>
			</div>

		</div><!--/packages-->

		</div><!--packages_nest-->

	</div><!--/head-->

	<div id="content_nest">

		<div id="content">
		<!-- HEADER -->

		<div id="content_boxes">

			<div class="kapcsolatdoboz">

				<p>Amennyiben felkeltettük érdeklődését, vagy csupán a kíváncsiság vezérli, esetleg egyedi ajánlatra lenne szüksége, kérem, vegye fel velünk a kapcsolatot az alábbi elérhetőségek egyikén, vagy használja az üzenetküldő formunkat!</p><br/><br/><p>Ne felejtsen el email címet megadni, amin válaszunkkal elérhetjük!</p><br/>


				<b>Ügyfelkapcsolati vezető:</b><br/>
				Kocsmárszky Zsolt<br/><br/>

				<ul>
					<li>Telefon: 06 30 487 5479</li>
					<li>E-mail: hello@nextserver.hu</li>
					<li>Skype: nextserver.hello</li>
				</ul>

			</div>
			<div class="kapcsolatdoboz">
				<?php 

				if (isset($_REQUEST['submit']) && $_REQUEST['email'] != '' && $_REQUEST['email'] == filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL) && $_REQUEST['text'] != '' ) {
					$to = 'hello@nextserver.hu';
					$subject = 'Nextserver Kapcsolat üzenet';
					$header = 'From: hello@nextserver.hu' . "\r\n";
					$header .= 'Reply-To: hello@nextserver.hu' . "\r\n";
					$header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
					$msg = "Név: " . $_REQUEST['name'] . "\r\nEmail cím: " . $_REQUEST['email'] . "\r\nSzöveg: " . $_REQUEST['text'] . "\r\n";
					@mail($to, $subject, $msg, $header);
					?>
					<h2>Köszönjük érdeklődését!</h2>
					<?php
				} else {
					if (isset($_REQUEST['submit'])) {
						?><p class="contact_error">Nem megfelelő adatok!</p><?php
					}
					?>
					<form action="<?php echo $PHP_SELF; ?>" method="post">
						<h4>Név:</h4>
						<input type="text" maxlength="250" name="name" class="input_text" value="<?php echo $_REQUEST['name']; ?>"/><br/>
						<h4>Email cím:</h4>
						<input type="text" maxlength="250" name="email" class="input_text" value="<?php echo $_REQUEST['email']; ?>"/><br/>
						<h4>Üzenet:</h4>
						<textarea name="text" class="hello_text" rows="3" cols="20"><?php echo $_REQUEST['text']; ?></textarea><br/>
						<input type="submit" name="submit" value="" class="hello_submit" /><br/><br/>
					</form>
					<?php
				}
				?>
			</div>



		</div><!--/content_boxes-->

		<div id="plus_clear" style="margin-bottom:5px;"> </div>

		</div><!--/content-->

	</div><!--/content_nest-->
