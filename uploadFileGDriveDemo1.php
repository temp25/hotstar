<?php

ini_set('memory_limit', '500M');
define('STDIN', fopen('php://stdin', 'r')); 
include 'vendor/autoload.php';

try {
	$client = new Google_Client();
	$client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
	$client->setClientId('905044047037-h0pl1t3r3qlimegtjd5h3q2u24pebqpl.apps.googleusercontent.com');
	$client->setClientSecret('Dc0BijZKsFzLYwCBm_eTY-Sf');
	$client->setRedirectUri("https://hotstar-test1.herokuapp.com");
	$client->setScopes(array('https://www.googleapis.com/auth/drive'));
	echo PHP_EOL."Auth url : ".$client->createAuthUrl().PHP_EOL."Enter Auth code : ";
	$authCode = trim(fgets(STDIN), " \r\n");
	echo PHP_EOL."AuthCode : ".$authCode.PHP_EOL;
	$token = $client->fetchAccessTokenWithAuthCode($authCode);
	$client->setAccessToken($token);
	$service = new Google_Service_Drive($client);
	shell_exec("wget -q http://mattmahoney.net/dc/enwik8.zip");
	$fileName="enwik8.zip";
	$data = file_get_contents($fileName);
	$file = new Google_Service_Drive_DriveFile();
	$file->title = "Big File";
	$file->name = "enwik8.zip";
	$chunkSizeBytes = 1 * 1024 * 1024;
	// $client->setDefer(true);
	// $mimeType = "application/zip";
	// $request = $service->files->create($file, array(
		  // 'data' => $data,
		  // 'mimeType' => 'application/zip',
		  // 'uploadType' => 'resumable',
	// ));
	// $media = new Google_Http_MediaFileUpload(
		// $client,
		// $request,
		// 'application/zip',
		// null,
		// true,
		// $chunkSizeBytes
	// );

	// $media->setFileSize(filesize("/app/enwik8.zip"));
	// $status = false;
	// $handle = fopen("/app/enwik8.zip", "rb");
	// while (!$status && !feof($handle)) {
		// $chunk = fread($handle, $chunkSizeBytes);
		// $status = $media->nextChunk($chunk);
	// }
	// $result = false;
	// if($status != false) {
	  // $result = $status;
	// }
	// fclose($handle);
	// $client->setDefer(false);
	
	$file = new Google_Service_Drive_DriveFile();
	$file->title = "Big File.zip";
	$file->name = "enwik8.zip";
	$chunkSizeBytes = 1 * 1024 * 1024;

	// Call the API with the media upload, defer so it doesn't immediately return.
	$client->setDefer(true);
	//$request = $service->files->insert($file);
	$request = $service->files->create($file, array(
		  'data' => $data,
		  'mimeType' => 'application/zip',
		  'uploadType' => 'resumable',
	));

	// Create a media file upload to represent our upload process.
	$media = new Google_Http_MediaFileUpload(
	  $client,
	  $request,
	  'application/zip',
	  null,
	  true,
	  $chunkSizeBytes
	);
	$media->setFileSize(filesize("/app/enwik8.zip"));

	// Upload the various chunks. $status will be false until the process is
	// complete.
	$status = false;
	$handle = fopen("/app/enwik8.zip", "rb");
	while (!$status && !feof($handle)) {
	  $chunk = fread($handle, $chunkSizeBytes);
	  $status = $media->nextChunk($chunk);
	 }

	// The final value of $status will be the data from the API for the object
	// that has been uploaded.
	$result = false;
	if($status != false) {
	  $result = $status;
	}

	fclose($handle);
	// Reset to the client to execute requests immediately in the future.
	$client->setDefer(false);

	echo PHP_EOL."Upload result : ".$result.PHP_EOL;

} catch(Exception $e) {
	echo 'Error Message: ' .$e->getMessage();
}

?>