<?php if ( isset( $updated ) ) : ?>
	<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
	<h2>Landing Page</h2>
	<p>Below you can select a KickoffLabs landing page to be published at any path.</p>
	<?php foreach ( $this->currentMessages as $message ) : ?>
		<p style="padding: .5em; background-color: #<?php echo $this->templateMessages[$message]['color']; ?>; color: #fff; font-weight: bold;"><?php echo $this->templateMessages[$message]['text']; ?></p>
	<?php endforeach; ?>
	<form id="kickofflabs-list-table" action="" method="post">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		<?php $listTable->display(); ?>
		<?php wp_nonce_field( KICKOFFLABS_NONCE_KEY ) ?>
	</form>
	<h2>See How It’s Done&hellip;</h2>
	<p><iframe src="http://player.vimeo.com/video/70063439" width="500" height="375" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></p>
</div>