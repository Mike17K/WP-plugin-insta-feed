<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '\\wordpress\\wp-load.php');
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed

// Load the Facebook PHP SDK
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// access token grand

use Instagram\FacebookLogin\FacebookLogin;
use Instagram\AccessToken\AccessToken;
//use Instagram\User\BusinessDiscovery; 
use Instagram\User\User;
use Instagram\User\Media;


///////////////////////////////////////////////////
///////////////////////////////////////////////////

function auth($data){ // here do all the logins with the insta api and set the $isAuthorized variable to true if is.
    $isAuthorized = false;
    $config = array(
        // instantiation config params
        'app_id' => $_ENV['FB_APP_ID'],
        'access_token' => $_ENV['ACCESS_TOKEN'],
        // facebook app id
        'app_secret' => $_ENV['FB_APP_SECRET'] // facebook app secret
    );

    // uri facebook will send the user to after they login
    $redirectUri = 'http://localhost/wordpress/wp-content/plugins/INSTAFEED/index.php';


    if (isset($_GET['code'])) {

        // instantiate our access token class
        $accessToken = new AccessToken($config);

        // exchange our code for an access token
        $newToken = $accessToken->getAccessTokenFromCode($_GET['code'], $redirectUri);

        if (!$accessToken->isLongLived()) { // check if our access token is short lived (expires in hours)
            // exchange the short lived token for a long lived token which last about 60 days
            $newToken = $accessToken->getLongLivedAccessToken($newToken['access_token']);
        }

        //print_r($newToken);
        
        global $config;
        $config = array(
            // instantiation config params
            'app_id' => $_ENV['FB_APP_ID'],
            'access_token' => $accessToken,
            // facebook app id
            'app_secret' => $_ENV['FB_APP_SECRET'] // facebook app secret
        );
        
        
        $media = new Media( $config );
        
        // initial user media response
        $userMedia = $media->getSelf();
        

        $isAuthorized = true;
        
    } else {

        $permissions = array(
            // permissions to request from the user
            'instagram_basic',
            'instagram_content_publish',
            'instagram_manage_insights',
            'instagram_manage_comments',
            'pages_show_list',
            'ads_management',
            'business_management',
            'pages_read_engagement'
        );

        // instantiate new facebook login
        $facebookLogin = new FacebookLogin($config);

        // display login dialog link
        echo '<a href="' . $facebookLogin->getLoginDialogUrl($redirectUri, $permissions) . '">' .
            'Log in with Facebook' .
            '</a>';
    }

    return $isAuthorized;
}
add_filter('auth-action','auth',10,1);

function echo_posts($conndata){                     ///////////////////////////////// fix
    // here i need to find the posts of username and take the 8 last
    global $config;

    print_r($config);

    echo '<div class="insta-feed-container-item"><img src="img1.png"></div>';
    echo '<div class="insta-feed-container-item"><img src="img1.png"></div>';
    echo '<div class="insta-feed-container-item"><img src="img1.png"></div>';
    echo '<div class="insta-feed-container-item"><img src="img1.png"></div>';
    echo '<div class="insta-feed-container-item"><img src="img1.png"></div>';
    echo '<div class="insta-feed-container-item"><img src="img1.png"></div>';
    echo '<div class="insta-feed-container-item"><img src="img1.png"></div>';
    echo '<div class="insta-feed-container-item"><img src="img1.png"></div>';
}
add_action('echo-posts','echo_posts',10,1);

///////////////////////////////////////////////////
///////////////////////////////////////////////////




function createContainer($conndata){
    ?>    
    <div class="insta-feed-container">
        <?php 
        if(apply_filters('auth-action',$conndata)){
            do_action('echo-posts',$conndata);
        }else{
            echo 'not auth corectly';
        }
        ?>
    </div>
    <?php
}
add_action('create-container-insta-feed','createContainer',10,1);

function my_custom_styles($filename){
    ?>
    <link rel="stylesheet" type="text/css" href='<?php echo $filename?>'> <!-- add here the css filename u want -->
    <?php    
}
add_action('inline_style', 'my_custom_styles',10,1);
  



?>