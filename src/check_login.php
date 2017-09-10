<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../Entity/User.php';

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

$fb = new Facebook([
    'app_id' => '1483071235147868',
    'app_secret' => 'ce6fb0f4f38438f1112c07b6384b9c47',
    'default_graph_version' => 'v2.10',
]);

$helper = $fb->getCanvasHelper();
$htok = $fb->getRedirectLoginHelper();
if (isset($_GET['state'])) {
    $htok->getPersistentDataHandler()->set('state', $_GET['state']);
}

$_SESSION['facebook_access_token'] = $htok->getAccessToken();

$permissions = ['email']; // optionnal
try {
    if (isset($_SESSION['facebook_access_token'])) {
        $accessToken = $_SESSION['facebook_access_token'];
    } else {
        $accessToken = $helper->getAccessToken();
    }
} catch(FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$user = new \Entity\User();


if (isset($accessToken)) {
    $user->setToken($accessToken);
    if (isset($_SESSION['facebook_access_token'])) {
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    } else {
        $_SESSION['facebook_access_token'] = (string) $accessToken;

        // OAuth 2.0 client handler
        $oAuth2Client = $fb->getOAuth2Client();

        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    // validating the access token
    try {
        $request = $fb->get('/me');
    } catch(FacebookResponseException $e) {
        // When Graph returns an error
        if ($e->getCode() == 190) {
            unset($_SESSION['facebook_access_token']);
            $helper = $fb->getRedirectLoginHelper();
            $loginUrl = $helper->getLoginUrl('http://localhost/fb-login/index.php', $permissions);
            echo "<script>window.top.location.href='".$loginUrl."'</script>";
            exit;
        }
    } catch(FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    // getting profile picture of the user
    try {
        $requestPicture = $fb->get('/me/picture?redirect=false&height=300'); //getting user picture
        $requestProfile = $fb->get('/me'); // getting basic info
        $picture = $requestPicture->getGraphUser();
        $profile = $requestProfile->getGraphUser();
    } catch(FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    $user->setName($profile->getName());
    $user->setProfile($picture["url"]);
    $user->selfSave();

    echo "<h3> ". $profile->getName()."</h3>";
    // showing picture on the screen
    echo "<img src='".$picture['url']."'/>";
    // Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']
} else {
    $helper = $fb->getRedirectLoginHelper();
    $loginUrl = $helper->getLoginUrl('http://localhost/fb-login/index.php');
    echo "<script>window.top.location.href='".$loginUrl."'</script>";
}