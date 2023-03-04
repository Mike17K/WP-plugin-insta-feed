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

include 'functions.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '\\wordpress\\wp-load.php');
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

do_action('inline_style',"css/styles.css");
do_action('create-container-insta-feed','programmer.me'); // pass target username as arg




?>