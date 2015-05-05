<?php if ( $this->updated ) : ?>
	<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
	<h2>KickoffLabs - Lead Generation & Landing Pages</h2>
	<?php foreach ( $this->currentMessages as $message ) : ?>
		<p style="padding: .5em; background-color: #<?php echo $this->templateMessages[$message]['color']; ?>; color: #fff; font-weight: bold;"><?php echo $this->templateMessages[$message]['text']; ?></p>
	<?php endforeach; ?>
	<?php if( $apiKey ): ?>
		<h3><label>KickoffLabs Account - Connected</label></h3>
		<p>
			<?php echo $email ? $email : $apiKey; ?> (<?php echo sprintf( '<a href="?page=%s&action=delete_credentials">Delete</a>', $_REQUEST[ 'page' ] ); ?>)
		</p>
		<h3>Setup Landing Pages</h3>
		<p>
			Publish KickoffLabs landing pages to your Wordpress site. Use them as pages off of your main site or as your home page if you are launching a new site.
		</p>
		<p>
			<a href="?page=kickofflabs-landingpage" class="button">Setup Landing Pages</a>
		</p>

		<h3>Setup Signup Bar</h3>
		<p>
			Add a bar to the top of your wordpress site, or specific pages, to capture and collect leads to your mailing list.
		</p>
		<p>
			<a href="?page=kickofflabs-signupbar" class="button">Setup Signup Bar</a>
		</p>

		<h3>Setup Splash Pages</h3>
		<p>
			Welcome new visitors to your site with a splash page that encourages them to sign up to your mailing list.
		</p>
		<p>
			<a href="?page=kickofflabs-welcomegate" class="button">Setup Splash Page</a>
		</p>
	<?php else: ?>
		<h3><label for="key">Add KickoffLabs Account</label></h3>
		<form action="" method="post" id="kickofflabs-config">
			<?php wp_nonce_field( KICKOFFLABS_NONCE_KEY ) ?>
			<p>
				<label>Kickofflabs.com Email <input autocomplete="off" name="kickofflabs_email" id="kickofflabs_email" type="email" value="<?php echo $email; ?>" /></label>
			</p>
			<p>
				<label>Kickofflabs.com Password <input autocomplete="off" name="kickofflabs_password" id="kickofflabs_password" type="password" value="" /></label>
			</p>
			<p>
				<input type="submit" name="submit" value="Enter KickoffLabs Login" /> or <a href="http://www.kickofflabs.com/" target="_blank"> Create New Account</a>
			</p>
		</form>
	<?php endif; ?>
</div>