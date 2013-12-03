/**
 * Set our current visitor as a repeat visitor
 */
function setRepeatVisitor() {
    var exdate=new Date();//assigns the current date to the variable exDate
    exdate.setTime(exdate.getTime() + (3600000 * 24 * kickofflabs_welcomegate.repeat_visitors_cookie) );//sets the expiry date
    document.cookie='KOL_repeat_visitor=skip_welcome;expires='+exdate.toGMTString();//sets the cookie
}

/**
 * Check if this is a repeat visitor
 * @returns {boolean}
 */
function checkRepeatVisitor() {
    if( document.cookie.indexOf('KOL_repeat_visitor') != -1 ) {
        return true;
    }
    return false;
}

/**
 * Enable our Welcome Gate
 */
function enableWelcomeGate() {
    var kol_location = document.location.protocol +'//embed.kickoffpages.com';
    var body = jQuery('body');
    // Temporarily hide our body
    body.addClass('kickofflabs-inactive');

    // Clone our kol container
    var kol_container = jQuery('#kickofflabs-page-container').clone().removeClass('kickofflabs-inactive');
    // Store our kol p style
    var p_style_store = jQuery(kol_container.children( 'p')[ 0 ]).getStyles()[ 0 ];
    // Set our container to the body
    body.prepend(kol_container);

    // Set our new CSS
    jQuery('html').css('overflow', 'hidden');
    jQuery('*').css('margin', 0).css('padding', 0);
    // Restore our p style
    jQuery(kol_container.children('p')[ 0 ]).attr('style', p_style_store);

    // Get our iframe
    var kol_iframe = kol_container.children('#kol_iframe')[ 0 ];

    // Set our iframe attributes
    var frameSource = kol_location + '/' + kickofflabs_welcomegate.page_id + '/' + window.location.search;
    jQuery(kol_iframe).attr('title', kickofflabs_welcomegate.page_title);
    jQuery(kol_iframe).attr('src', frameSource);

    // Make sure we can get a contentWindow
    if( typeof kol_iframe.contentWindow == 'undefined' ) {
        setRepeatVisitor();
        window.location.reload();
    }
    // Make sure we can
    if( typeof kol_iframe.contentWindow.postMessage == 'undefined' ) {
        setRepeatVisitor();
        window.location.reload();
    }

    jQuery( kol_container.find('#skip-welcome-gate')[ 0 ] ).click(function(){
        setRepeatVisitor();
        window.location.reload();
    });

    function messageListener(e) {
        if( e.origin !== kol_location) {
            return;
        }
        if( e.data == 'kol_success' ) {
            setRepeatVisitor();
            switch( kickofflabs_welcomegate.after_signup ) {
                case 'immediate_redirect': {
                    window.location.reload();
                    break;
                }
                case 'delay_redirect': {
                    window.setTimeout( function(){window.location.reload()}, 5000 );
                    break;
                }
                default: {
                    break;
                }
            }
        }
    }

    if (window.addEventListener){
        addEventListener("message", messageListener, false)
    } else {
        attachEvent("onmessage", messageListener)
    }

    // Set our HTML title
    jQuery( 'title' ).text( kickofflabs_welcomegate.page_title );
    // Un-hide our body
    body.removeClass('kickofflabs-inactive').addClass('kickofflabs-body');
    body = null;
}

(function( $ ) {
    $.fn.getStyles = function() {
        var styles = [];
        if( window.getComputedStyle ) {
            $(this).each(function(){
                styles.push(window.getComputedStyle( this, null ));
            });
        }  else if( document.documentElement.currentStyle ) {
            $(this).each(function(){
                styles.push(this.currentStyle);
            });
        }
        return styles;
    }
}( jQuery ));

jQuery(document).ready(function(){
    if( checkRepeatVisitor() == false ) {
        enableWelcomeGate();
    }
});
