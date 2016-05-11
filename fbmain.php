<?php

date_default_timezone_set('UTC'); 

require('vendor/autoload.php');

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequestException;
use Facebook\FacebookRequest;
use Facebook\HttpClients\FacebookGuzzleHttpClient;
use Facebook\FacebookSDKException;

// Set Facebook App ID, App Secret and Site URL
$_CONFIG = [
    'appid' => getenv('FB_APPID') ?: '',
    'secret' => getenv('FB_SECRET') ?: '',
    'site_url' => getenv('SITE_URL') ?: '',
    'max_width' => 420,
    'access_token_key_prefix' => 'fbat_'
];

$access_token_key = $_CONFIG['access_token_key_prefix'] . $_CONFIG['appid'];

session_start();

// Setup application
FacebookSession::setDefaultApplication($_CONFIG['appid'], $_CONFIG['secret']);
FacebookRequest::setHttpClientHandler(new FacebookGuzzleHttpClient());

// Get the helper
$helper = new FacebookRedirectLoginHelper($_CONFIG['site_url']);

// Attempt to retrieve a session
if (isset($_GET['code'])) {
    $session = $helper->getSessionFromRedirect();

    if (!is_null($session)) {
        $_SESSION[$access_token_key] = $session->getToken();
    }

    header('Location: ' . $_CONFIG['site_url']);
}
if (isset($_SESSION[$access_token_key])) {
    $session = new FacebookSession($_SESSION[$access_token_key]);

    try {
        $session->validate();
    } catch (FacebookSDKException $e) {
        $session = null;
    }
}

if (is_null($session)) {
    header('Location: ' . $helper->getLoginUrl(['publish_actions']));
    exit;
}

$user_profile = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject('Facebook\GraphUser')->asArray();

// Generate an aleatory code to obtain always pics with different names
function code ($length)
{
	$pattern = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	$max = strlen($pattern) - 1;
    $key = '';
	for ($i = 0; $i < $length; $i++) {
        $key .= $pattern[rand(0, $max)];
    }

	return $key;
}

// Call to 'code' function with 10 aleatory characters
$code2 = code(10);

// Start the upload
if (isset($_POST['action']) && $_POST['action'] == "upload_fb_image") {
	// Include the class to merge the two pictures
    include("classes/merge.php");

    $message = ($_POST['message'] == 'Add a description...') ? 'This photo was created with the Cool Badges application. You can purchase the App here: http://bitly.com/Z0NKhn' : $_POST['message'];

	// $f1 is the profile picture
	$f1 = $_POST['photo'];
	// $f2 is the badge selected
	$f2 = $_POST['icon'];
	// Creating the object of the class
	$imagem = new mergePictures($f1,$f2);

	// Merge the pictures with the over method
	$imagem->over();
	// Save the new image in the fb_images folder
	$imagem->save("fb_images", $code2, "jpg");

	// The relative path to the file
	$file = realpath("fb_images/".$code2.".jpg");

	try {
		$response = (new FacebookRequest($session, 'POST', '/me/photos', [
            'source' => fopen($file, 'r'),
            'message' => $message
        ]))->execute();

		$success = 'true';
	} catch (FacebookRequestException $e) {
		$success = 'false';
	}

	// Remove the generated file
	unlink("fb_images/".$code2.".jpg");

	// Remove the original file
	unlink($_POST['photo']);

	header("Location: index.php?res=$success");
	// Ends the new code for the photo upload

}

// Downloads the profile image from Facebook server
function copy_remote_image($url, $cod, $m_width = 420 /*Default width*/){
	$remote_file = $url;
	$local_file = "fb_images/".$cod."_fb_photo.jpg";

    try {
        $data = (new GuzzleHttp\Client())->get($remote_file)->getBody();
    } catch (GuzzleHttp\Exception\RequestException $e) {
        die("We cannot read the remote file");
    }

	file_put_contents($local_file, $data)
	or die("We cannot write in the local file");
	// Get the image size saved in the fb_images
	$dim = getimagesize($local_file);

	if ($dim[0] > $m_width){

		// Read the downloaded image
		$src = imagecreatefromjpeg($local_file);
		// Create the new image dimensions
		$newheight = ($dim[1]/$dim[0]) * $m_width;
		$newwidth = $m_width;
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagealphablending($tmp, false);
		imagesavealpha($tmp,true);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$dim[0],$dim[1]);
		// Write the resized image to disk
		imagejpeg($tmp,$local_file,100);

	}else{

		// Create an image with 'imagecreatetruecolor' function with the new width and height
		$new_image = imagecreatetruecolor($dim[0],$dim[1]);
		// We need the original image to merge the new image
		$original_image = imagecreatefromjpeg($local_file);
		// Create the merge between the new image and the original image
		imagecopy($new_image, $original_image, 0, 0, 0, 0, $dim[0], $dim[1]);
		// Save the merged image
		imagejpeg($new_image, $local_file);

	}
}
