jQuery(document).ready(function($){
    $('#kickofflabs_bar_background_color').wpColorPicker();
    $('#kickofflabs_bar_text_color').wpColorPicker();
    $('#kickofflabs_bar_button_color').wpColorPicker();
    $('#submit-remove').click(function(){
        // Remove the landing page id
        $('#kickofflabs_landing_page_id').val(0);
    });
});