<?php

/**
 * Class KickofflabsAPI
 */
class KickofflabsAPI
{
    private $config = array();
    private $configName = 'kickofflabs_api';

    /**
     * @description Add in our hooks
     */
    public function __construct()
    {

    }

    /**
     * @description Get the Landing Pages configuration
     * @return array|mixed|void
     */
    public function getConfig()
    {
        if( empty( $this->config ) ){
            $this->config = get_option( $this->configName, array() );
        }
        return $this->config;
    }

    /**
     * @description Get the name of the configuration as it is stored in the DB
     * @return string
     */
    public function getConfigName()
    {
        return $this->configName;
    }

    /**
     * @description Update the configuration
     * @param $newConfig
     */
    public function updateConfig( $newConfig )
    {
        update_option( $this->getConfigName(), $newConfig );
        $this->config = $newConfig;
    }
}

class KickofflabsSignupBar
{
    private $config = array();
    private $configName = 'kickofflabs_signup_bar';
    private $configDefaults = array(
        'page_id' => 0,
        'signup_text' => 'Enter your email to subscribe:',
        'placeholder_text' => 'Type your email here...',
        'button_text' => 'Subscribe',
        'share_text' => 'Share our site with your friends!',
        'influenced_count_text' => 'Influenced so far:',
        'bar_background_color' => '#00A4D1',
        'bar_text_color' => '#FFFFFF',
        'bar_button_color' => '#FFFFFF'
    );

    /**
     * @description Add in our hooks
     */
    public function __construct()
    {
        // hook into 'get_footer' action to call sidebar
        $currentConfig = $this->getConfig();
        if( $currentConfig[ 'page_id' ] > 0 ) {
            add_action( 'wp_footer', array( $this, 'addSignupBar' ) );
            wp_enqueue_script( 'kickofflabs-signupbar', KICKOFFLABS_JS . 'signupbar.js', array(), false, true );
        }
    }

    /**
     * @description Get the Signup Bar configuration
     * @return array|mixed|void
     */
    public function getConfig()
    {
        if( empty( $this->config ) ){
            $this->config = get_option( $this->configName, array() );
            $this->config = array_merge( $this->configDefaults, $this->config );
        }
        return $this->config;
    }

    /**
     * @description Get the name of the configuration as it is stored in the DB
     * @return string
     */
    public function getConfigName()
    {
        return $this->configName;
    }

    /**
     * @description Update the configuration
     * @param $newConfig
     */
    public function updateConfig( $newConfig )
    {
        update_option( $this->getConfigName(), $newConfig );
        $this->config = $newConfig;
    }

    /**
     * @description Add the signup bar code to every page
     */
    public function addSignupBar()
    {
        $currentConfig = $this->getConfig();
        include KICKOFFLABS_TEMPLATES . 'default-signup-bar.php';
    }
}

/**
 * Class KickofflabsLandingPages
 * @description Handles the configuration and non-admin hooks
 */
class KickofflabsLandingPages
{
    private $config = array();
    private $configName = 'kickofflabs_landing_pages';

    /**
     * @description Add in our hooks
     */
    public function __construct()
    {
        add_filter( 'template_include', array( $this, 'templateOverride' ) );
    }

    /**
     * @description Get the Landing Pages configuration
     * @return array|mixed|void
     */
    public function getConfig()
    {
        if( empty( $this->config ) ){
            $this->config = get_option( $this->configName, array() );
        }
        return $this->config;
    }

    /**
     * @description Get the name of the configuration as it is stored in the DB
     * @return string
     */
    public function getConfigName()
    {
        return $this->configName;
    }

    /**
     * @description Update the configuration
     * @param $newConfig
     */
    public function updateConfig( $newConfig )
    {
        update_option( $this->getConfigName(), $newConfig );
        $this->config = $newConfig;
    }

    /**
     * @description Filter for selecting the landing page template
     * @param $template
     * @return string
     */
    public function templateOverride( $template )
    {
        global $post;

        // Check if post id is one of the landing pages
        $foundLandingPage = $this->findByWordpressPageId( $post->ID );
        if( is_null( $foundLandingPage ) ){
            // If we didn't find it in our list then lets just return what we were passed
            return $template;
        }

        return KICKOFFLABS_TEMPLATES . 'default-landing-page.php';
    }

    /**
     * @description Finds if the specific page is a Landing Page
     * @param $pageId
     * @return null
     */
    public function findByWordpressPageId( $pageId )
    {
        foreach( $this->getConfig() AS $config ) {
            if( $config[ 'wordpress_page_id' ] === $pageId ) {
                return $config;
            }
        }

        return null;
    }
}

/**
 * Class KickofflabsWelcomeGate
 * @description Handles the configuration and non-admin hooks
 */
class KickofflabsWelcomeGate
{
    private $config = array();
    private $configName = 'kickofflabs_welcome_gate';
    private $configDefaults = array(
        'page_id' => 0,
        'skip_text' => 'Skip to page',
        'repeat_visitors_cookie' => 30,
        'where_to_gate' => 'home',
        'after_signup' => 'stay_on_page'
    );

    /**
     * @description Add in our hooks
     */
    public function __construct()
    {
        // Make sure we are not hooking on an AJAX request
        if( !defined( 'DOING_AJAX' ) || DOING_AJAX == false ) {
            $currentConfig = $this->getConfig();
            if( $currentConfig[ 'page_id' ] > 0 ) {
                add_action( 'wp', array( $this, 'enableWelcomeGate' ) );
            }
        }

    }

    /**
     * @description Get the Landing Pages configuration
     * @return array|mixed|void
     */
    public function getConfig()
    {
        if( empty( $this->config ) ){
            $this->config = get_option( $this->configName, array() );
            $this->config = array_merge( $this->configDefaults, $this->config );
        }
        return $this->config;
    }

    /**
     * @description Get the name of the configuration as it is stored in the DB
     * @return string
     */
    public function getConfigName()
    {
        return $this->configName;
    }

    /**
     * @description Update the configuration
     * @param $newConfig
     */
    public function updateConfig( $newConfig )
    {
        update_option( $this->getConfigName(), $newConfig );
        $this->config = $newConfig;
    }

    /**
     * @description Enables our welcome gate
     */
    public function enableWelcomeGate()
    {
        // Check that this is not a repeat visitor and if this is where we want to show the gate
        if( $this->isWhereWeGate() ) {
			// Enqueue our JS
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'kickofflabs-welcomegate', KICKOFFLABS_JS . 'welcomegate.js', array( 'jquery' ), false, false );
			// Enqueue our CSS
			wp_enqueue_style( 'kickofflabs-welcomegate', KICKOFFLABS_CSS . 'welcomegate.css' );
			// Localize our WP KOL JS variables
			wp_localize_script( 'kickofflabs-welcomegate', 'kickofflabs_welcomegate', $this->getConfig() );

			// Add our footer action
			add_action( 'wp_footer', array( $this, 'addWelcomeGate' ) );
        }
    }

    /**
     * @description Checks if our current page is the page we are gating on
     * @return bool
     */
    public function isWhereWeGate()
    {
        $currentConfig = $this->getConfig();
        if( $currentConfig[ 'where_to_gate' ] === 'home'
            && is_home() ) {
            return true;
        } elseif( $currentConfig[ 'where_to_gate' ] === 'entire_site' ) {
            return true;
        } elseif( is_numeric( $currentConfig[ 'where_to_gate' ] )
            && is_page( $currentConfig[ 'where_to_gate' ] ) ) {
            return true;
        }

        return false;
    }

	/**
	 * Add our welcome gate
	 */
	public function addWelcomeGate() {
		$currentConfig = $this->getConfig();
		include KICKOFFLABS_TEMPLATES . 'default-welcome-gate.php';
	}
}