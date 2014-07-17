<div class="actions" style="margin-bottom: 40px;padding:0;">
	<h2>Publish Landing Page</h2>
	<p>
		<select name="kickofflabs_landing_page_id" id="kickofflabs_landing_page_id" style="float: none;">
			<option value="">Select Landing Page&hellip;</option>
			<?php foreach( $this->kickofflabsLandingPages AS $kickofflabsLandingPage ): ?>
				<option value="<?php echo $kickofflabsLandingPage->page_id; ?>"><?php echo $kickofflabsLandingPage->title; ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<h4>
		<label for="kickofflabs_landing_page_path">Publish to Path</label>
	</h4>
	<p>
		<?=get_bloginfo('url');?><input autocomplete="no" type="text" value="/" name="kickofflabs_landing_page_path" id="kickofflabs_landing_page_path" />
	</p>
	<p>
		<?php
		do_action( 'restrict_manage_posts' );
		submit_button( 'Add Landing Page', 'secondary', 'action', false, array( 'id' => 'kickofflabs-add-landing-page' ) );
		?>
	</p>
</div>
<h2 class="alignleft">Published Pages</h2>