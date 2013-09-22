<?php if ( $this->updated ) : ?>
    <div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
    <h2>Kickofflabs Splash Page</h2>
	<p>
		<iframe src="http://player.vimeo.com/video/70144919" width="500" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
	</p>
    <form action="" method="post" id="kickofflabs-config">
        <?php foreach ( $this->currentMessages as $message ) : ?>
            <p style="padding: .5em; background-color: #<?php echo $this->templateMessages[$message]['color']; ?>; color: #fff; font-weight: bold;"><?php echo $this->templateMessages[$message]['text']; ?></p>
        <?php endforeach; ?>
        <?php wp_nonce_field( KICKOFFLABS_NONCE_KEY ) ?>
        <h3>Choose Landing Page</h3>
        <p><label for="kickofflabs_landing_page_id">Choose a landing page customer list to enable the splash page.</label></p>
        <p>
            <select name="kickofflabs_landing_page_id" id="kickofflabs_landing_page_id">
                <option value="">None (disabled)</option>
                <?php foreach( $this->kickofflabsLandingPages AS $kickofflabsLandingPage ): ?>
                    <option value="<?php echo $kickofflabsLandingPage->page_id; ?>"<?php if( $kickofflabsLandingPage->page_id == $currentConfig[ 'page_id' ] ): ?> SELECTED<?php endif; ?>><?php echo $kickofflabsLandingPage->title; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <h3>After Signup</h3>
        <div class="welcome-gate-column">
			<p>
				<label for="kickofflabs_after_signup">What do you want to do after a signup?</label><br />
				<select name="kickofflabs_after_signup" id="kickofflabs_after_signup">
					<option value="stay_on_page"<?php if( $currentConfig[ 'after_signup' ] == 'stay_on_page' ): ?> SELECTED<?php endif; ?>>Stay on page</option>
					<option value="delay_redirect"<?php if( $currentConfig[ 'after_signup' ] == 'delay_redirect' ): ?> SELECTED<?php endif; ?>>Redirect after 5 seconds</option>
					<option value="immediate_redirect"<?php if( $currentConfig[ 'after_signup' ] == 'immediate_redirect' ): ?> SELECTED<?php endif; ?>>Immediately redirect</option>
				</select>
			</p>
			<p>
                <label for="kickofflabs_skip_text">Skip Text: </label><br />
                <input type="text" name="kickofflabs_skip_text" id="kickofflabs_skip_text" value="<?php echo $currentConfig[ 'skip_text' ]; ?>" />
            </p>
            <p>
                <label for="kickofflabs_where_to_gate">Show Splash Page on:</label><br />
                <input type="radio" name="kickofflabs_where_to_gate" value="home"<?php if( $currentConfig[ 'where_to_gate' ] == 'home' ): ?> CHECKED<?php endif; ?> />Home<br />
                <input type="radio" name="kickofflabs_where_to_gate" value="entire_site"<?php if( $currentConfig[ 'where_to_gate' ] == 'entire_site' ): ?> CHECKED<?php endif; ?> />Entire Site<br />
                <input type="radio" name="kickofflabs_where_to_gate" value="specific_page"<?php if( is_numeric( $currentConfig[ 'where_to_gate' ] ) ): ?> CHECKED<?php endif; ?> />Specific Page
                <select name="kickofflabs_where_to_gate_page" id="kickofflabs_where_to_gate_page" DISABLED>
                    <option value="">Select Page&hellip;</option>
                    <?php foreach( get_pages() AS $page ): ?>
                        <option value="<?php echo $page->ID; ?>"<?php if( is_numeric( $currentConfig[ 'where_to_gate' ] ) && $page->ID == $currentConfig[ 'where_to_gate' ] ): ?> SELECTED<?php endif; ?>><?php echo $page->post_title; ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                Re-display splash page every <input type="text" name="kickofflabs_repeat_visitors_cookie" id="kickofflabs_repeat_visitors_cookie" value="<?php echo $currentConfig[ 'repeat_visitors_cookie' ]; ?>" /> days.
            </p>
			<p class="submit">
				<input type="submit" name="submit" value="Update Splash Page" />
				<?php if( $currentConfig[ 'page_id' ] ): ?>
					<input type="submit" name="remove" id="submit-remove" value="Remove Splash Page" />
				<?php endif; ?>
			</p>
        </div>
    </form>
</div>