<?php if ( $this->updated ) : ?>
	<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
	<h2>Kickofflabs Signup Bar</h2>
	<p>The KickoffLabs Signup Bar allows you to embed a signup bar on any web page or blog as shown below.</p>
	<p><img src="<?php echo KICKOFFLABS_IMAGES; ?>api_signup_bar_short.png" /></p>
	<form action="" method="post" id="kickofflabs-config">
		<?php foreach ( $this->currentMessages as $message ) : ?>
			<p style="padding: .5em; background-color: #<?php echo $this->templateMessages[$message]['color']; ?>; color: #fff; font-weight: bold;"><?php echo $this->templateMessages[$message]['text']; ?></p>
		<?php endforeach; ?>
		<?php wp_nonce_field( KICKOFFLABS_NONCE_KEY ) ?>
		<h3>Landing Page</h3>
		<p><label for="kickofflabs_landing_page_id">Choose a Landing Page Customer List to Enable the Signup bar.</label></p>
		<p>
			<select name="kickofflabs_landing_page_id" id="kickofflabs_landing_page_id">
				<option value="">None (disabled)</option>
				<?php foreach( $this->kickofflabsLandingPages AS $kickofflabsLandingPage ): ?>
					<option value="<?php echo $kickofflabsLandingPage->page_id; ?>"<?php if( $kickofflabsLandingPage->page_id == $currentConfig[ 'page_id' ] ): ?> SELECTED<?php endif; ?>><?php echo $kickofflabsLandingPage->title; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<div class="signupbar-column">
			<h3>Text Options</h3>
			<p><label for="kickofflabs_signup_text">Signup Text</label></p>
			<p>
				<input class="signup-bar-settings" name="kickofflabs_signup_text" id="kickofflabs_signup_text" type="text" value="<?php echo $currentConfig[ 'signup_text' ]; ?>" />
			</p>
			<p><label for="kickofflabs_placeholder_text">Placeholder Text </label></p>
			<p>
				<input class="signup-bar-settings" name="kickofflabs_placeholder_text" id="kickofflabs_placeholder_text" type="text" value="<?php echo $currentConfig[ 'placeholder_text' ]; ?>" />
			</p>
			<p><label for="kickofflabs_button_text">Button Text</label></p>
			<p>
				<input class="signup-bar-settings" name="kickofflabs_button_text" id="kickofflabs_button_text" type="text" value="<?php echo $currentConfig[ 'button_text' ]; ?>" />
			</p>
			<p><label for="kickofflabs_share_text">Thank you message </label></p>
			<p>
				<input class="signup-bar-settings" name="kickofflabs_share_text" id="kickofflabs_share_text" type="text" value="<?php echo $currentConfig[ 'share_text' ]; ?>" />
			</p>
			<p><label for="kickofflabs_influenced_count_text">Influenced Count Text </label></p>
			<p>
				<input class="signup-bar-settings" name="kickofflabs_influenced_count_text" id="kickofflabs_influenced_count_text" type="text" value="<?php echo $currentConfig[ 'influenced_count_text' ]; ?>" />
			</p>
			<p class="submit">
				<input type="submit" name="submit" value="Update Signup Bar" />
				<?php if( $currentConfig[ 'page_id' ] ): ?>
					<input type="submit" name="remove" id="submit-remove" value="Remove Signup Bar" />
				<?php endif; ?>
			</p>
		</div>
		<div class="signupbar-column">
			<h3>Color Options</h3>
			<p><label for="kickofflabs_background_color">Background Color</label></p>
			<p>
				<input class="signup-bar-settings" name="kickofflabs_bar_background_color" id="kickofflabs_bar_background_color" data-default-color="#00A4D1" type="text" value="<?php echo $currentConfig[ 'bar_background_color' ]; ?>" />
			</p>
			<p><label for="kickofflabs_text_color">Text Color </label></p>
			<p>
				<input class="signup-bar-settings" name="kickofflabs_bar_text_color" id="kickofflabs_bar_text_color" type="text" data-default-color="#FFFFFF" value="<?php echo $currentConfig[ 'bar_text_color' ]; ?>" />
			</p>
			<p><label for="kickofflabs_button_color">Button Color </label></p>
			<p>
				<input class="signup-bar-settings" name="kickofflabs_bar_button_color" id="kickofflabs_bar_button_color" type="text" data-default-color="#FFFFFF" value="<?php echo $currentConfig[ 'bar_button_color' ]; ?>" />
			</p>
		</div>
	</form>
	<h2 style="clear:both;">See How It’s Done&hellip;</h2>
	<p><iframe src="http://player.vimeo.com/video/70063438" width="500" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></p>
</div>