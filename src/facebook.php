<?php namespace src;
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../Entity/User.php';

use Facebook\Facebook;

$fb = new Facebook([
    'app_id' => '1483071235147868',
    'app_secret' => 'ce6fb0f4f38438f1112c07b6384b9c47',
    'default_graph_version' => 'v2.10',
]);

if(isset($_GET["logout"])) {
    unset($_SESSION['facebook_access_token']);
}

# login.php
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_likes']; // optional
$loginUrl = $helper->getLoginUrl('http://localhost/social_login/src/check_login.php', $permissions);

if(isset($_SESSION['facebook_access_token'])) {
    $token = $_SESSION['facebook_access_token'];
    $fb->setDefaultAccessToken($token);
    $profile = $fb->get('/me');
    $profile = $profile->getGraphUser();

    $user = new \Entity\User($profile->getId());
    echo "<h3> ". $user->getName()."</h3>";
    // showing picture on the screen
    echo "<img src='".$user->getProfile()."'/>";
    echo "<br> <a href='facebook.php?logout=true'?>Logout</a> ";
} else {
    echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
}


