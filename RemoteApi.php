<?php

/**
 * kickofflabsRemotePost
 * @param $path The path we want to call on the API
 * @param $body The body of information we want to send
 * @return array|bool
 */
function kickofflabsRemotePost( $path, $body )
{
    $host = parse_url( KICKOFFLABS_API_HOST, PHP_URL_HOST );
    // Build our HTTP call
    $http_args = array(
        'body'			=> $body,
        'headers'		=> array(
            'Content-Type'	=> 'application/x-www-form-urlencoded; ' .
                'charset=' . get_option( 'blog_charset' ),
            'Host'			=> $host,
            'User-Agent'	=> 'WordPress/' . $wp_version . 'KickoffLabsWP/' . KICKOFFLABS_PLUGIN_VERSION
        ),
        'httpversion'	=> '1.0',
        'timeout'		=> 15
    );

    // Make the HTTP call
    $response = wp_remote_post( KICKOFFLABS_API_HOST . $path, $http_args );
    // Check for a WP error
    if ( is_wp_error( $response ) )
        return false;

    return $response;
}

/**
 * kickofflabsRemotePost
 * @param $path The path we want to call on the API
 * @param $query The query of information we want to send
 * @return array|bool
 */
function kickofflabsRemoteGet( $path, $query )
{
    $host = parse_url( KICKOFFLABS_API_HOST, PHP_URL_HOST );
    // Build our HTTP call
    $http_args = array(
        'headers'		=> array(
            'Host'			=> $host,
            'User-Agent'	=> 'WordPress/' . $wp_version . 'KickoffLabsWP/' . KICKOFFLABS_PLUGIN_VERSION
        ),
        'httpversion'	=> '1.0',
        'timeout'		=> 15
    );

    // Create the url we are getting
    $url = KICKOFFLABS_API_HOST . "{$path}?{$query}";
    // Make the HTTP call
    $response = wp_remote_get( $url, $http_args );
    // Check for a WP error
    if ( is_wp_error( $response ) )
        return false;

    return $response;
}

/**
 * Class RemoteKickofflabsAuthenticate
 */
class RemoteKickofflabsAuthenticate
{
    private $email = null;
    private $password = null;
    private $authenticateResponse = array();

    /**
     * @description Store our email and password and make the authenticate call
     * @param $email
     * @param $password
     */
    public function __construct( $email, $password )
    {
        $this->email = $email;
        $this->password = $password;

        $this->authenticate();
    }

    /**
     * @description Check if the call was valid
     * @return bool
     */
    public function isValid()
    {
        if( array_key_exists( 'response', $this->authenticateResponse )
            && array_key_exists( 'code', $this->authenticateResponse[ 'response' ] )
            && 200 == $this->authenticateResponse[ 'response' ][ 'code' ] )
        {
            return true;
        }

        return false;
    }

    /**
     * @description Get the API Key returned in the call
     * @return null|string
     */
    public function getApiKey()
    {
        if( $this->isValid() ){
            if( array_key_exists( 'body', $this->authenticateResponse ) ) {
                $body = json_decode( $this->authenticateResponse[ 'body' ] );
                if( property_exists( $body, 'api_key' ) ){
                    return $body->api_key;
                }
            }
        }

        return null;
    }

    /**
     * @description Make the authenticate API call
     */
    private function authenticate()
    {
        // Get the properly formed body to send
        $body = http_build_query( array( 'email' => $this->email, 'password' => $this->password ) );
        $this->authenticateResponse = kickofflabsRemotePost( '/v1/authenticate', $body );
    }
}

/**
 * Class RemoteKickofflabsLandingPages
 * @description This is the landing pages directly from the KickoffLabs API
 */
class RemoteKickofflabsLandingPages implements Iterator
{
    private $apiKey = '';
    private $landingPages = array();
    private $position = 0;

    /**
     * @param $apiKey The Kickofflabs API key
     */
    public function __construct( $apiKey )
    {
        $this->apiKey = $apiKey;
        // Load our KickoffLabs landing pages
        $this->getLandingPages();
    }

    /**
     * @description Find a specific landing page by page_id
     * @param $pageId
     * @return null|StdObject
     */
    public function findPageId( $pageId )
    {
        foreach( $this->landingPages AS $landingPage ) {
            if( property_exists( $landingPage, 'page_id' )
                && $landingPage->page_id == $pageId )
            {
                return $landingPage;
            }
        }

        return null;
    }

    /**
     * @description Implements Traversable current method
     * @return mixed
     */
    public function current()
    {
        return $this->landingPages[ $this->position ];
    }

    /**
     * @description Implements Traversable key method
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @description Implements Traversable next method
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @description Implements Traversable rewind method
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @description Implements Traversable valid method
     * @return bool
     */
    public function valid()
    {
        return array_key_exists( $this->position, $this->landingPages );
    }

    /**
     * Retrieve the landing pages from KickoffLabs
     */
    private function getLandingPages()
    {
        // Create our query and make the call to the API
        $query = http_build_query( array( 'api_key' => $this->apiKey ) );
        $kickofflabsLandingPages = kickofflabsRemoteGet( '/v1/landing_pages', $query );

        // Parse the results
        if( array_key_exists( 'response', $kickofflabsLandingPages )
            && $kickofflabsLandingPages[ 'response' ][ 'code' ] === 200 )
        {
            $decodedBody = json_decode( $kickofflabsLandingPages[ 'body' ] );
            if( property_exists( $decodedBody, 'pages' ) ) {
                $this->landingPages = $decodedBody->pages;
            }
        }
    }
}