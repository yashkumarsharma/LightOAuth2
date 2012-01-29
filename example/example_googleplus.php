<?php
require_once('LightOAuth2.php');
// sample for Google+ API using OAuth 2.0 draft
define('CLIENT_ID','<your client id>');
define('CLIENT_SECRET','<your client secret>');

define('SCOPE','https://www.googleapis.com/auth/plus.me');
$entry = array('authorize'=>'https://accounts.google.com/o/oauth2/auth',
	       'access_token'=>'https://accounts.google.com/o/oauth2/token');

// path for the application script (it should be registered on Google console)
define('CALLBACK','http://www.example.com/example_googleplus.php');

// 'cainfo' should be specified if the default CA info isn't available.
//$copts = array('cainfo'=>'<path for local CA info file>'); 
//$oauth = new LightOAuth2(CLIENT_ID, CLIENT_SECRET, $copts);
$oauth = new LightOAuth2(CLIENT_ID, CLIENT_SECRET);
   
session_start();
if (!isset($_SESSION['access_token'])) {
  if (!isset($_GET['code'])) { // get authorization code
    $opts = array('scope'=>SCOPE);
    $url = $oauth->getAuthUrl($entry['authorize'], CALLBACK, $opts);
    header("Location: " . $url);
    exit();
  }
  // get access token
  $obj = $oauth->getToken($entry['access_token'], CALLBACK, $_GET['code']);
  $_SESSION['access_token'] = $obj->access_token;
}

// access to proteced resource
$oauth->setToken($_SESSION['access_token']);
$url = "https://www.googleapis.com/plus/v1/people/me/activities/public";
$response = $oauth->fetch($url);
$obj = json_decode($response);
print_r($obj);
?>
