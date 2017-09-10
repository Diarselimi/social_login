<?php namespace src;
require __DIR__ . '/../vendor/autoload.php';
use Facebook\Facebook;

$fb = new Facebook([
    'app_id' => '1483071235147868',
    'app_secret' => 'ce6fb0f4f38438f1112c07b6384b9c47',
    'default_graph_version' => 'v2.10',
]);

# login.php
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_likes']; // optional
$loginUrl = $helper->getLoginUrl('http://localhost/social_login/src/check_login.php', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';