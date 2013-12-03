<?php

/*
Plugin Name: Landing Pages for Wordpress
Plugin URI: http://www.kickofflabs.com
Description: Get More Leads - And Convert More of Them - With Effortless Landing Pages + Smart Email Marketing + Referral Generation - Now for WordPress!
Version: 1.2
Author: KickoffLabs, lonnylot
Author URI: http://www.kickofflabs.com
*/

// Make sure we are not being called directly
if ( defined( 'WPINC' ) === false ) {
	exit;
}

// Define path constants
define( 'KICKOFFLABS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'KICKOFFLABS_TEMPLATES', KICKOFFLABS_PLUGIN_PATH . 'templates/' );
define( 'KICKOFFLABS_LIST_TABLES', KICKOFFLABS_PLUGIN_PATH . 'list-tables/' );
define( 'KICKOFFLABS_JS', plugins_url( '', __FILE__ ) . '/js/' );
define( 'KICKOFFLABS_CSS', plugins_url( '', __FILE__ ) . '/css/' );
define( 'KICKOFFLABS_IMAGES', plugins_url( '', __FILE__ ) . '/img/' );


// Define general constants
define( 'KICKOFFLABS_PLUGIN_VERSION', '1.2' );
define( 'KICKOFFLABS_API_HOST', 'https://api.kickofflabs.com' );

// Define admin constants
define( 'KICKOFFLABS_NONCE_KEY', 'kickofflabs update' );

// Include files that are ALWAYS needed
include( KICKOFFLABS_PLUGIN_PATH . 'Controls.php' );
include( KICKOFFLABS_PLUGIN_PATH . 'Widget.php' );
include( KICKOFFLABS_PLUGIN_PATH . 'Shortcode.php' );

// Add our hooks
if ( is_admin() ) {
    // Include admin only files
    include( KICKOFFLABS_PLUGIN_PATH . 'Admin.php' );
    include( KICKOFFLABS_PLUGIN_PATH . 'RemoteApi.php' );
    // Add a hook for the menu function at the top of Admin.php
    add_action( 'admin_menu', 'kickofflabsAdminMenu' );
} else {
    // Start our controls (constructors are called and do all the hooking required)
	new KickofflabsLandingPages();
    new KickofflabsSignupBar();
    new KickofflabsWelcomeGate();
}

// Add a hook to register the widgets at the top of Widget.php
add_action( 'widgets_init', 'kickofflabsRegisterWidgets' );
