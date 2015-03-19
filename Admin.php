<?php

/**
 * kickofflabsAdminMenu
 * @description Called when the WP admin_menu hook is called to setup our KickoffLabs menu in the sidebar
 */
function kickofflabsAdminMenu()
{
	// Make sure all people in the admin area have privileges to make changes
	if ( current_user_can( 'manage_options' ) ) {
		add_menu_page( 'KickoffLabs', 'KickoffLabs', 'manage_options', 'kickofflabs-api', '', KICKOFFLABS_IMAGES . 'beaker_icon_size.png' );
		add_submenu_page( 'kickofflabs-api', 'KickoffLabs Setup', 'KickoffLabs Setup', 'manage_options', 'kickofflabs-api', array( new KickofflabsAPIAdmin(), 'display' ) );
		add_submenu_page( 'kickofflabs-api', 'Landing Page', 'Landing Page', 'manage_options', 'kickofflabs-landingpage', array( new KickofflabsLandingAdmin(), 'display' ) );
		add_submenu_page( 'kickofflabs-api', 'Signup Bar', 'Signup Bar', 'manage_options', 'kickofflabs-signupbar', array( new KickofflabsSignupBarAdmin(), 'display' ) );
		add_submenu_page( 'kickofflabs-api', 'Splash Page (BETA)', 'Splash Page (BETA)', 'manage_options', 'kickofflabs-welcomegate', array( new KickofflabsWelcomeGateAdmin(), 'display' ) );
	}
}

/**
 * Class KickofflabsAPIAdmin
 */
class KickofflabsAPIAdmin
{
	private $currentMessages = array();
	private $templateMessages = array(
		'api_cleared' => array('color' => 'aa0', 'text' => 'Your API key has been cleared.'),
		'no_api_key_available' => array('color' => 'aa0', 'text' => 'No API key is available. Please contact support@kickofflabs.com' ),
		'new_login_invalid' => array('color' => '888', 'text' => 'The login you entered is invalid. Please double-check it.')
	);
	private $updated = false;

	private $kickofflabsApi = null;

	public function __construct()
	{
		$this->kickofflabsApi = new KickofflabsAPI();
	}

	/**
	 * @description Display the admin page
	 */
	public function display()
	{
		// Because of WordPress workflow we call the save function here
		$this->save();

		// Get the config
		$currentConfig = $this->kickofflabsApi->getConfig();
		$apiKey = $currentConfig[ 'api_key' ];
		$email = array_key_exists( 'email', $currentConfig) ? $currentConfig[ 'email' ] : '';

		require( KICKOFFLABS_TEMPLATES . 'admin/api.php' );
	}

	/**
	 * @description Handle any data changes
	 */
	private function save()
	{
		// Update the landing pages
		if ( current_user_can( 'manage_options' ) ) {
			// Update our configuration
			if ( isset( $_POST['submit'] ) ) {
				check_admin_referer( KICKOFFLABS_NONCE_KEY );
				$email = $_POST[ 'kickofflabs_email' ];
				$password = $_POST[ 'kickofflabs_password' ];

				$this->updated = $this->updateCredentials( $email, $password );
			} elseif ( array_key_exists( 'action', $_GET )
				&& 'delete_credentials' == $_GET[ 'action' ]) {
				$this->updated = $this->deleteCredentials();
			}
		}
	}

	private function updateCredentials( $email, $password )
	{
		$remoteKickofflabsAuthenticate = new RemoteKickofflabsAuthenticate( $email, $password );
		if( false == $remoteKickofflabsAuthenticate->isValid() ){
			$this->currentMessages[] = 'new_login_invalid';
			return false;
		}

		$apiKey = $remoteKickofflabsAuthenticate->getApiKey();
		if( is_null( $apiKey ) ) {
			$this->currentMessage[] = 'no_api_key_available';
			return false;
		}

		$currentConfig = $this->kickofflabsApi->getConfig();
		$currentConfig[ 'api_key' ] = $apiKey;
		$currentConfig[ 'email' ] = $email;
		$this->kickofflabsApi->updateConfig( $currentConfig );

		return true;
	}

	private function deleteCredentials()
	{
		$this->currentMessages[] = 'api_cleared';
		$currentConfig = $this->kickofflabsApi->getConfig();
		$currentConfig[ 'api_key' ] = '';
		$this->kickofflabsApi->updateConfig( $currentConfig );

		return true;
	}
}

/**
 * Class KickofflabsSignupBarAdmin
 */
class KickofflabsSignupBarAdmin
{
	private $currentMessages = array();
	private $templateMessages = array(
		'signupbar_enabled' => array('color' => '4AB915', 'text' => 'Your signupbar has now been enabled.'),
		'signupbar_disabled' => array('color' => '888', 'text' => 'Your signupbar has now been DISABLED.'),
		'new_landing_page_id_invalid' => array('color' => '888', 'text' => 'The landing page is invalid. Please double-check it.'),
		'no_api_key' => array('color' => 'e81b1b', 'text' => 'Please visit the <a href="?page=kickofflabs-api">Setup</a> to complete the plug-in setup.' )
	);

	private $updated = false;

	private $signupBarConfig = null;
	private $kickofflabsLandingPages = null;

	public function __construct()
	{
		$this->signupBarConfig = new KickofflabsSignupBar();
	}

	/**
	 * @description Display the admin page
	 */
	public function display()
	{
		// Get the API Key
		$kickofflabsApi = new KickofflabsAPI();
		$generalConfigs = $kickofflabsApi->getConfig();

		if( empty( $generalConfigs[ 'api_key' ] ) ) {
			$this->currentMessages[] = 'no_api_key';
		}
		// Get the landing pages
		$this->kickofflabsLandingPages = new RemoteKickofflabsLandingPages( $generalConfigs[ 'api_key' ] );

		// Because of WordPress workflow we call the save function here
		$this->save();

		// Get the current signup bar configuration
		$currentConfig = $this->signupBarConfig->getConfig();

		// Load our assets for the page display
		$this->loadAssets();

		require( KICKOFFLABS_TEMPLATES . 'admin/signupbar.php' );
	}

	/**
	 * @description Load assets needed to display admin page correctly
	 * @global $wp_version From wp-includes/version.php
	 */
	private function loadAssets()
	{
		global $wp_version;
		// Add color picker ability if it is supported
		if( version_compare($wp_version, '3.5') >= 0 ) {
			// The color picker is supported!
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'kickofflabs-signupbar-admin', KICKOFFLABS_JS . 'admin/signupbar.js', array( 'wp-color-picker' ), false, true );
		}

		wp_enqueue_style( 'kickofflabs-signupbar-admin', KICKOFFLABS_CSS . 'admin/signupbar.css' );
	}

	/**
	 * @description Handle any data changes
	 */
	private function save()
	{
		// Update the signup bar settings
		if ( current_user_can( 'manage_options' ) ) {
			// Add a landing page
			if( isset( $_POST[ 'submit' ] ) ) {
				check_admin_referer( KICKOFFLABS_NONCE_KEY );
				$this->overwriteConfig( $_POST );
				$this->updated = true;
			} elseif( isset( $_POST[ 'remove' ] ) ) {
				check_admin_referer( KICKOFFLABS_NONCE_KEY );
				$removePageConfig = $_POST;
				$removePageConfig[ 'kickofflabs_landing_page_list_id' ] = null;
				$this->overwriteConfig( $removePageConfig );
				$this->updated = true;
			}
		}
	}

	/**
	 * @description Overwrite the Signup Bar config with a new one
	 * @param $signupBar
	 */
	private function overwriteConfig( $signupBar )
	{
		// Setup our new config
		$newConfig = array(
			'signup_text' => sanitize_text_field( $signupBar[ 'kickofflabs_signup_text' ] ),
			'placeholder_text' => sanitize_text_field( $signupBar[ 'kickofflabs_placeholder_text' ] ),
			'button_text' => sanitize_text_field( $signupBar[ 'kickofflabs_button_text' ] ),
			'share_text' => sanitize_text_field( $signupBar[ 'kickofflabs_share_text' ] ),
			'influenced_count_text' => sanitize_text_field( $signupBar[ 'kickofflabs_influenced_count_text' ] ),
			'bar_background_color' => $signupBar[ 'kickofflabs_bar_background_color' ],
			'bar_button_color' => $signupBar[ 'kickofflabs_bar_button_color' ],
			'bar_text_color' => $signupBar[ 'kickofflabs_bar_text_color' ]
		);

		// Check if we are enabling/disabling the signup bar
		$currentConfig = $this->signupBarConfig->getConfig();
		if( $signupBar[ 'kickofflabs_landing_page_list_id' ] > 0 ) {
			if( 0 == $currentConfig[ 'list_id' ] ) {
				$this->currentMessages[] = 'signupbar_enabled';
			}
			$newConfig[ 'list_id' ] = $signupBar[ 'kickofflabs_landing_page_list_id' ];
		} else {
			if( $currentConfig[ 'list_id' ] ) {
				$this->currentMessages[] = 'signupbar_disabled';
			}
			$newConfig[ 'list_id' ] = 0;
		}

		$this->signupBarConfig->updateConfig( $newConfig );
	}

	/**
	 * @description Validate a page exists on KickoffLabs
	 * @param $pageId
	 * @return bool
	 */
	private function validateLandingPageExists( $pageId )
	{
		// Verify the page_id exists in landing pages
		if( is_null( $this->kickofflabsLandingPages->findPageId( $pageId ) ) ) {
			$this->currentMessages[] = 'new_landing_page_id_invalid';
			return false;
		}

		return true;
	}
}

/**
 * Class KickofflabsLandingAdmin
 */
class KickofflabsLandingAdmin
{
	private $currentMessages = array();
	private $templateMessages = array(
		'new_landing_page_empty' => array('color' => 'aa0', 'text' => 'The landing page form was incomplete. Please try again.'),
		'new_login_valid' => array('color' => '4AB915', 'text' => 'Your login has been verified.'),
		'new_landing_page_id_invalid' => array('color' => '888', 'text' => 'The landing page is invalid. Please double-check it.'),
		'new_landing_page_path_invalid' => array('color' => '888', 'text' => 'The landing page path is invalid. Only letters, numbers, and dashes are allowed.'),
		'landing_page_updated' => array('color' => '4AB915', 'text' => 'The latest landing page data has been pulled from KickoffLabs'),
		'no_api_key' => array('color' => 'e81b1b', 'text' => 'Please visit the <a href="?page=kickofflabs-api">Setup</a> to complete the plug-in setup.' )
	);
	private $updated = false;

	private $landingPages = null;
	private $kickofflabsLandingPages = null;

	public function __construct()
	{
		// Get our list table
		require KICKOFFLABS_LIST_TABLES . 'KickofflabsLandingPageListTable.php';
		
		wp_enqueue_style( 'kickofflabs-signupbar-admin', KICKOFFLABS_CSS . 'admin/landingpage.css' );

		$this->landingPages = new KickofflabsLandingPages();
	}

	/**
	 * @description Display the admin page
	 */
	public function display()
	{
		// Get the API Key
		$kickofflabsApi = new KickofflabsAPI();
		$generalConfigs = $kickofflabsApi->getConfig();

		if( empty( $generalConfigs[ 'api_key' ] ) ) {
			$this->currentMessages[] = 'no_api_key';
		}

		// Retrieve our landing pages from kickofflabs
		$this->kickofflabsLandingPages = new RemoteKickofflabsLandingPages( $generalConfigs[ 'api_key' ] );

		// Because of WordPress workflow we call the save function here
		$this->save();

		// Get our list table
		$listTable = new KickofflabsLandingPageListTable( array(), $this->kickofflabsLandingPages );
		$listTable->prepare_items();

		require( KICKOFFLABS_TEMPLATES . 'admin/landingpage.php' );
	}

	/**
	 * @description Handle any data changes
	 */
	private function save()
	{
		// Update the landing pages
		if ( current_user_can( 'manage_options' ) ) {
			// Add a landing page
			if( isset( $_POST[ 'action' ] ) ) {
				check_admin_referer( KICKOFFLABS_NONCE_KEY );
				if( array_key_exists('kickofflabs_landing_page_id', $_POST) && $_POST[ 'kickofflabs_landing_page_id'] && array_key_exists('kickofflabs_landing_page_path', $_POST) &&  $_POST[ 'kickofflabs_landing_page_path' ] ) {
					$this->addLandingPage($_POST[ 'kickofflabs_landing_page_id'], $_POST[ 'kickofflabs_landing_page_path' ] );
				} else {
					$this->currentMessages[] = 'new_landing_page_empty';
				}
			} elseif ( isset($_GET['action'] ) && 'delete' === $_GET[ 'action' ] ) {
				$this->deleteLandingPage( $_GET[ 'hash' ] );
			} elseif ( isset($_GET['action'] ) && 'refresh' === $_GET[ 'action' ] ) {
				$this->refreshLandingPage( $_GET[ 'hash' ] );
			}
		}
	}
	
	/**
	 * @description Refresh a landing page
	 * @param $hash
	 * @return bool
	 */
	private function refreshLandingPage( $hash )
	{
		$currentConfig = $this->landingPages->getConfig();		
		$this->deleteLandingPage($hash);
		$this->addLandingPage($currentConfig[ $hash ][ 'page_id' ], $currentConfig[$hash][ 'path' ] );
		$this->currentMessages[] = 'landing_page_updated';
	}

	/**
	 * @description Add a landing page
	 * @param $kickofflabsPageId
	 * @param $pagePath
	 * @return bool
	 */
	private function addLandingPage( $kickofflabsPageId, $pagePath )
	{
		$foundLandingPage = $this->kickofflabsLandingPages->findPageId( $kickofflabsPageId );
		// Verify the page_id exists in landing pages
		if( is_null( $foundLandingPage ) ) {
			$this->currentMessages[] = 'new_landing_page_id_invalid';
			return false;
		}

		// Clean the path
		$pagePath = strtolower( trim( $pagePath, '/' ) );
		// Verify the path is valid
		$uniqueSlug = wp_unique_post_slug($pagePath, 0, 'publish', 'page', 0);
		if( $uniqueSlug != $pagePath ) {
			$this->currentMessages[] = 'new_landing_page_path_invalid';
			return false;
		}

		// Insert a page
		$wordpressPageId = wp_insert_post( array(
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_content' => '',
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_title' => $pagePath
		) );

		// Generate a unique hash
		$landingPageHash = md5( $foundLandingPage->page_id . $uniqueSlug );

		// Update the config
		$currentConfig = $this->landingPages->getConfig();
		$currentConfig[ $landingPageHash ] = array(
			'page_id' => $foundLandingPage->page_id,
			'path' => $uniqueSlug,
			'list_id' => $foundLandingPage->list_id,
			'list_name' => $foundLandingPage->list_name,
			'favicon_link' => $foundLandingPage->favicon_link,
			'title' => $foundLandingPage->title,
			'page_title' => $foundLandingPage->page_title,
			'meta_description' => $foundLandingPage->meta_description,
			'open_graph_title' => $foundLandingPage->open_graph_title,
			'open_graph_description' => $foundLandingPage->open_graph_description,
			'open_graph_image' => $foundLandingPage->open_graph_image,
			'wordpress_page_id' => $wordpressPageId
		);
		$this->landingPages->updateConfig( $currentConfig );

		return true;
	}

	/**
	 * @description Delete a landing page
	 * @param $hash
	 * @return bool
	 */
	private function deleteLandingPage( $hash )
	{
		$currentConfig = $this->landingPages->getConfig();

		// Check if we have this hash in the config
		if( array_key_exists( $hash, $this->landingPages->getConfig() ) ){
			// Delete the page from WordPress
			wp_delete_post( $currentConfig[ $hash ][ 'wordpress_page_id' ], true );

			// Delete the page from our config
			unset( $currentConfig[ $hash ] );
			$this->landingPages->updateConfig( $currentConfig );
		}

		return true;
	}
}

class KickofflabsWelcomeGateAdmin
{
	private $currentMessages = array();
	private $templateMessages = array(
		'new_landing_page_empty' => array('color' => 'aa0', 'text' => 'The landing page form was incomplete. Please try again.'),
		'welcomegate_enabled' => array('color' => '4AB915', 'text' => 'Your splash page has now been enabled.'),
		'welcomegate_disabled' => array('color' => '888', 'text' => 'Your splash page has now been DISABLED.'),
		'new_landing_page_id_invalid' => array('color' => '888', 'text' => 'The landing page is invalid. Please double-check it.'),
		'no_api_key' => array('color' => 'e81b1b', 'text' => 'Please visit the <a href="?page=kickofflabs-api">Setup</a> to complete the plug-in setup.' )
	);
	private $updated = false;

	private $kickofflabsLandingPages = null;
	private $welcomegate = null;

	public function __construct()
	{
		$this->welcomegate = new KickofflabsWelcomeGate();
	}

	/**
	 * @description Display the admin page
	 */
	public function display()
	{
		// Get the API Key
		$kickofflabsApi = new KickofflabsAPI();
		$generalConfigs = $kickofflabsApi->getConfig();

		if( empty( $generalConfigs[ 'api_key' ] ) ) {
			$this->currentMessages[] = 'no_api_key';
		}

		// Retrieve our landing pages from kickofflabs
		$this->kickofflabsLandingPages = new RemoteKickofflabsLandingPages( $generalConfigs[ 'api_key' ] );

		// Because of WordPress workflow we call the save function here
		$this->save();

		// Get the current signup bar configuration
		$currentConfig = $this->welcomegate->getConfig();

		// Load our assets for the page display
		$this->loadAssets();

		require( KICKOFFLABS_TEMPLATES . 'admin/welcomegate.php' );
	}

	/**
	 * @description Load assets needed to display admin page correctly
	 */
	private function loadAssets()
	{
		wp_enqueue_script( 'kickofflabs-welcomegate-admin', KICKOFFLABS_JS . 'admin/welcomegate.js', array( 'jquery' ), false, true );
		wp_enqueue_style( 'kickofflabs-welcomegate-admin', KICKOFFLABS_CSS . 'admin/welcomegate.css' );
	}

	/**
	 * @description Handle any data changes
	 */
	private function save()
	{
		// Update the signup bar settings
		if ( current_user_can( 'manage_options' ) ) {
			// Add a landing page
			if( isset( $_POST[ 'submit' ] ) ) {
				check_admin_referer( KICKOFFLABS_NONCE_KEY );
				$this->overwriteConfig( $_POST );
				$this->updated = true;
			} elseif( isset( $_POST[ 'remove' ] ) ) {
				check_admin_referer( KICKOFFLABS_NONCE_KEY );
				$removePageConfig = $_POST;
				$removePageConfig[ 'kickofflabs_landing_page_id' ] = 0;
				$this->overwriteConfig( $removePageConfig );
				$this->updated = true;
			}
		}
	}

	/**
	 * @description Overwrite the Welcome Gate config with a new one
	 * @param $welcomeGate
	 */
	private function overwriteConfig( $welcomeGate )
	{
		// Setup our new config
		$newConfig = array(
			'skip_text' => sanitize_text_field( $welcomeGate[ 'kickofflabs_skip_text' ] ),
			'repeat_visitors_cookie' => sanitize_text_field( $welcomeGate[ 'kickofflabs_repeat_visitors_cookie' ] )
		);

		// If we selected a page make sure it exists
		if( $welcomeGate[ 'kickofflabs_where_to_gate' ] === 'specific_page'
			&& is_numeric( $welcomeGate[ 'kickofflabs_where_to_gate_page' ] )
			&& get_post( $welcomeGate[ 'kickofflabs_where_to_gate_page' ] ) !== null ) {
			// Specific page
			$newConfig[ 'where_to_gate' ] = $welcomeGate[ 'kickofflabs_where_to_gate_page' ];
		} elseif( $welcomeGate[ 'kickofflabs_where_to_gate' ] === 'entire_site' ) {
			// Entire site
			$newConfig[ 'where_to_gate' ] = 'entire_site';
		} else {
			// Home
			$newConfig[ 'where_to_gate' ] = 'home';
		}

		switch( $welcomeGate[ 'kickofflabs_after_signup' ] ) {
			case 'immediate_redirect': {
				$newConfig[ 'after_signup' ] = 'immediate_redirect';
				break;
			}
			case 'delay_redirect': {
				$newConfig[ 'after_signup' ] = 'delay_redirect';
				break;
			}
			case 'stay_on_page':
			default: {
			$newConfig[ 'after_signup' ] = 'stay_on_page';
			break;
			}
		}

		$foundLandingPage = $this->kickofflabsLandingPages->findPageId( $welcomeGate[ 'kickofflabs_landing_page_id' ] );

		// Check if we are enabling/disabling the welcome gate
		$currentConfig = $this->welcomegate->getConfig();
		if( $welcomeGate[ 'kickofflabs_landing_page_id' ] > 0
			&& is_null( $foundLandingPage ) === false ) {
			if( 0 == $currentConfig[ 'page_id' ] ) {
				$this->currentMessages[] = 'welcomegate_enabled';
			}
			$newConfig[ 'page_id' ] = $foundLandingPage->page_id;
			$newConfig[ 'title' ] = $foundLandingPage->title;
			$newConfig[ 'page_title' ] = $foundLandingPage->page_title;
			$newConfig[ 'meta_description' ] = $foundLandingPage->meta_description;
			$newConfig[ 'open_graph_title' ] = $foundLandingPage->open_graph_title;
			$newConfig[ 'open_graph_description' ] = $foundLandingPage->open_graph_description;
			$newConfig[ 'open_graph_image' ] = $foundLandingPage->open_graph_image;
		} else {
			if( $currentConfig[ 'page_id' ] ) {
				$this->currentMessages[] = 'welcomegate_disabled';
			}
			$newConfig[ 'page_id' ] = 0;
		}

		$this->welcomegate->updateConfig( $newConfig );
	}
}
