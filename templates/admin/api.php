<?php if ( $this->updated ) : ?>
    <div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
    <h2>Kickofflabs Setup<?php if( $apiKey ): ?> - Account Configured!<?php endif; ?></h2>
    <?php foreach ( $this->currentMessages as $message ) : ?>
        <p style="padding: .5em; background-color: #<?php echo $this->templateMessages[$message]['color']; ?>; color: #fff; font-weight: bold;"><?php echo $this->templateMessages[$message]['text']; ?></p>
    <?php endforeach; ?>
    <?php if( $apiKey ): ?>
    <h3><label>KickoffLabs API Key</label></h3>
    <p>
        <?php echo $apiKey; ?> (<?php echo sprintf( '<a href="?page=%s&action=delete_credentials">Delete</a>', $_REQUEST[ 'page' ] ); ?>)
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