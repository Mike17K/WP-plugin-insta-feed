<?php

// Load the Facebook PHP SDK
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


use Instagram\User\MediaPublish;

$config = array(
    // instantiation config params
    'user_id' => $_ENV['USER_ID'],
    'access_token' => $_ENV['ACCESS_TOKEN'],
);

// instantiate media publish
$mediaPublish = new MediaPublish($config);

// post our container with its contents to instagram
$publishedPost = $mediaPublish->create('<CONTAINER_ID>');

echo 'ok';

?>