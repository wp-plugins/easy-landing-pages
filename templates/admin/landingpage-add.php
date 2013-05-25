<div class="actions" style="margin-bottom: 40px;padding:0;">
    <h2>Publish Landing Page</h2>
    <select name="kickofflabs_landing_page_id" id="kickofflabs_landing_page_id">
        <option value="">Select Landing Page&hellip;</option>
        <?php foreach( $this->kickofflabsLandingPages AS $kickofflabsLandingPage ): ?>
            <option value="<?php echo $kickofflabsLandingPage->page_id; ?>"><?php echo $kickofflabsLandingPage->title; ?></option>
        <?php endforeach; ?>
    </select>
    <label for="kickofflabs_landing_page_path">Publish to Path</label><input autocomplete="no" type="text" value="/" name="kickofflabs_landing_page_path" id="kickofflabs_landing_page_path" />
    <?php
    do_action( 'restrict_manage_posts' );
    submit_button( 'Add Landing Page', 'secondary', 'action', false, array( 'id' => 'kickofflabs-add-landing-page' ) );
    ?>
</div>
<h2 class="alignleft">Published Pages</h2>