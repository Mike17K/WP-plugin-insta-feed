<?php
/*
Plugin Name: Insta Feed
Plugin URI:  https://example.com/my-plugin
Description: This is my second WordPress plugin.
Version:     1.0
Author:      Mike Kaipis
Author URI:  https://example.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: my-plugin
*/


require_once($_SERVER['DOCUMENT_ROOT'] . '\\wordpress\\wp-load.php');
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();




// access token grand

use Instagram\FacebookLogin\FacebookLogin;
use Instagram\AccessToken\AccessToken;
//use Instagram\User\BusinessDiscovery; 
use Instagram\User\User;

$config = array(
    // instantiation config params
    'app_id' => $_ENV['FB_APP_ID'],
    // facebook app id
    'app_secret' => $_ENV['FB_APP_SECRET'] // facebook app secret
);

// uri facebook will send the user to after they login
$redirectUri = 'https://localhost/wordpress/wp-content/plugins/INSTAFEED/index.php';


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

    // Load the Facebook PHP SDK
    $config = array(
        'access_token' => $accessToken,
    );

    $user = new User($config);
    $pages = $user->getUserPages();

    print_r($pages);



















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


?>