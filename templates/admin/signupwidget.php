<h3>Landing Page</h3>
<p>
    <select class="widefat" id="<?php echo $this->get_field_id( 'landing_page_id' ); ?>" name="<?php echo $this->get_field_name( 'landing_page_id' ); ?>">
        <option value="">None (disabled)</option>
        <?php foreach( $landingPages AS $landingPage ): ?>
            <option value="<?php echo $landingPage->page_id; ?>"<?php if( $instance[ 'landing_page_id' ] == $landingPage->page_id ):?> SELECTED<?php endif; ?>><?php echo $landingPage->title; ?></option>
        <?php endforeach; ?>
    </select>
</p>
<h3>Configuration</h3>
<p>
    <label for="<?php echo $this->get_field_id( 'signup_text' ); ?>">Signup Text</label>
    <input type="text" id="<?php echo $this->get_field_id( 'signup_text' ); ?>" name="<?php echo $this->get_field_name( 'signup_text' ); ?>" value="<?php echo $instance[ 'signup_text' ]; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'placeholder_text' ); ?>">Placeholder Text</label>
    <input type="text" id="<?php echo $this->get_field_id( 'placeholder_text' ); ?>" name="<?php echo $this->get_field_name( 'placeholder_text' ); ?>" value="<?php echo $instance[ 'placeholder_text' ]; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'button_text' ); ?>">Button Text</label>
    <input type="text" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo $instance[ 'button_text' ]; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'share_text' ); ?>">Thank you message</label>
    <input type="text" id="<?php echo $this->get_field_id( 'share_text' ); ?>" name="<?php echo $this->get_field_name( 'share_text' ); ?>" value="<?php echo $instance[ 'share_text' ]; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'influenced_count_text' ); ?>">Influenced Count Text</label>
    <input type="text" id="<?php echo $this->get_field_id( 'influenced_count_text' ); ?>" name="<?php echo $this->get_field_name( 'influenced_count_text' ); ?>" value="<?php echo $instance[ 'influenced_count_text' ]; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'promote_text' ); ?>">Promote Text</label>
    <input type="text" id="<?php echo $this->get_field_id( 'promote_text' ); ?>" name="<?php echo $this->get_field_name( 'promote_text' ); ?>" value="<?php echo $instance[ 'promote_text' ]; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'error_message' ); ?>">Error Message</label>
    <input type="text" id="<?php echo $this->get_field_id( 'error_message' ); ?>" name="<?php echo $this->get_field_name( 'error_message' ); ?>" value="<?php echo $instance[ 'error_message' ]; ?>" />
</p>
<h3>CSS Classes</h3>
<p>
    <label for="<?php echo $this->get_field_id( 'button_css_class' ); ?>">Button Class</label>
    <input type="text" id="<?php echo $this->get_field_id( 'button_css_class' ); ?>" name="<?php echo $this->get_field_name( 'button_css_class' ); ?>" value="<?php echo $instance[ 'button_css_class' ]; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'input_css_class' ); ?>">Input Class</label>
    <input type="text" id="<?php echo $this->get_field_id( 'input_css_class' ); ?>" name="<?php echo $this->get_field_name( 'input_css_class' ); ?>" value="<?php echo $instance[ 'input_css_class' ]; ?>" />
</p>