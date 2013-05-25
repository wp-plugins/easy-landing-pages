function check_gate( jQueryObject ) {
    if( jQueryObject.val() == 'specific_page' ) {
        jQuery( '#kickofflabs_where_to_gate_page' ).removeAttr( 'DISABLED' );
    } else {
        jQuery( '#kickofflabs_where_to_gate_page' ).attr( 'DISABLED', true );
    }
}

jQuery(document).ready(function(){
    jQuery( 'input[name="kickofflabs_where_to_gate"]' ).change(function(){
        check_gate( jQuery( this ) );
    })
    check_gate( jQuery( 'input[name="kickofflabs_where_to_gate"]:checked' ) );
});