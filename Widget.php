<?php
/**
 * kickofflabsRegisterWidgets
 * @description Register our widgets
 */
function kickofflabsRegisterWidgets()
{
    register_widget( 'SignupWidget' );
}

/**
 * Class SignupWidget
 */
class SignupWidget extends WP_Widget
{
    private $defaultConfig = array(
        'landing_page_id' => 0,
        'signup_text' => 'Enter your email to subscribe:',
        'placeholder_text' => 'Type your email here...',
        'button_text' => 'Subscribe',
        'share_text' => 'Share our site with your friends!',
        'influenced_count_text' => 'Influenced so far:',
        'promote_text' => 'Check this out!',
        'error_message' => 'There was a problem with your email address!',
        'button_css_class' => 'button',
        'input_css_class' => 'input'
    );

    /**
     *
     */
    public function __construct()
    {
        parent::__construct( '', 'KickoffLabs Sign Up' );
    }

    /**
     * @description Display the widget on the page
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance )
    {
        if( $instance[ 'landing_page_id' ] > 0 ){
            include KICKOFFLABS_TEMPLATES . 'default-signupwidget.php';
            wp_enqueue_script( 'signup-widget', KICKOFFLABS_JS . 'signupwidget.js', array(), false, true );
        }
    }

    /**
     * @description Update the widget settings
     * @param array $newInstance
     * @param array $oldInstance
     * @return array
     */
    public function update( $newInstance, $oldInstance )
    {
        $instance = array(
            'signup_text' => sanitize_text_field( $newInstance[ 'signup_text' ] ),
            'placeholder_text' => sanitize_text_field( $newInstance[ 'placeholder_text' ] ),
            'button_text' => sanitize_text_field( $newInstance[ 'button_text' ] ),
            'share_text' => sanitize_text_field( $newInstance[ 'share_text' ] ),
            'influenced_count_text' => sanitize_text_field( $newInstance[ 'influenced_count_text' ] ),
            'promote_text' => sanitize_text_field( $newInstance[ 'promote_text' ] ),
            'error_message' => sanitize_text_field( $newInstance[ 'error_message' ] ),
            'button_css_class' => sanitize_text_field( $newInstance[ 'button_css_class' ] ),
            'input_css_class' => sanitize_text_field( $newInstance[ 'input_css_class' ] )
        );

        // Get the API Config
        $kickofflabsAPI = new KickofflabsAPI();
        $apiConfig = $kickofflabsAPI->getConfig();

        // Pull in the landing pages from KickoffLabs
        $landingPages = new RemoteKickofflabsLandingPages( $apiConfig[ 'api_key' ] );
        // Verify the landing page exists on KickoffLabs
        if( is_null( $landingPages->findPageId( $newInstance[ 'landing_page_id' ] ) ) ) {
            $instance[ 'landing_page_id' ] = 0;
        } else {
            $instance[ 'landing_page_id' ] = $newInstance[ 'landing_page_id' ];
        }

        return $instance;
    }

    /**
     * @description Display the widget form on the backend
     * @param array $instance
     * @return string|void
     */
    public function form( $instance )
    {
        // Get the API Config
        $kickofflabsAPI = new KickofflabsAPI();
        $apiConfig = $kickofflabsAPI->getConfig();

        // Pull in the landing pages from KickoffLabs
        $landingPages = new RemoteKickofflabsLandingPages( $apiConfig[ 'api_key' ] );

        // Merge instance w/ defaults
        $instance = array_merge( $this->defaultConfig, $instance );

        include KICKOFFLABS_TEMPLATES . 'admin/signupwidget.php';
    }

}