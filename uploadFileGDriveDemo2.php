<?php
include 'vendor/autoload.php';

function readVideoChunk ($handle, $chunkSize) {
    $byteCount = 0;
    $giantChunk = "";
    while (!feof($handle)) {
        // fread will never return more than 8192 bytes if the stream is read buffered and it does not represent a plain file
        $chunk = fread($handle, 8192);
        $byteCount += strlen($chunk);
        $giantChunk .= $chunk;
        if ($byteCount >= $chunkSize)
        {
            return $giantChunk;
        }
    }
    return $giantChunk;
}

echo "File Upload - Uploading a large file".PHP_EOL;

if($argc==1){
	die("Must pass atleast one parameter");
}

$authCode = $argv[1];

$client = new Google_Client();
$client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
$client->setClientId('905044047037-h0pl1t3r3qlimegtjd5h3q2u24pebqpl.apps.googleusercontent.com');
$client->setClientSecret('Dc0BijZKsFzLYwCBm_eTY-Sf');
$client->setRedirectUri("https://hotstar-test1.herokuapp.com");
$client->addScope("https://www.googleapis.com/auth/drive");
$service = new Google_Service_Drive($client);
$token = $client->fetchAccessTokenWithAuthCode($authCode);
$client->setAccessToken($token);
$authUrl = $client->createAuthUrl();
echo PHP_EOL."AuthUrl : ".$authUrl.PHP_EOL;
echo PHP_EOL."AuthCode : ".$authCode.PHP_EOL;
echo PHP_EOL."Token : ".$token.PHP_EOL;

  /************************************************
   * We'll setup an empty 20MB file to upload.
   ************************************************/
  // DEFINE("TESTFILE", 'testfile.txt');
  // if (!file_exists(TESTFILE)) {
    // $fh = fopen(TESTFILE, 'w');
    // fseek($fh, 1024*1024*20);
    // fwrite($fh, "!", 1);
    // fclose($fh);
  // }
  DEFINE("TESTFILE", 'enwik8.zip');
  shell_exec("wget -q http://mattmahoney.net/dc/enwik8.zip");

  $file = new Google_Service_Drive_DriveFile();
  $file->name = "enwik8.zip";
  $chunkSizeBytes = 1 * 1024 * 1024;

  // Call the API with the media upload, defer so it doesn't immediately return.
  $client->setDefer(true);
  $request = $service->files->create($file);

  // Create a media file upload to represent our upload process.
  $media = new Google_Http_MediaFileUpload(
      $client,
      $request,
      'application/zip', /*'text/plain',*/
      null,
      true,
      $chunkSizeBytes
  );
  $media->setFileSize(filesize(TESTFILE));
  echo PHP_EOL."filesize(TESTFILE) : ".filesize(TESTFILE);

  // Upload the various chunks. $status will be false until the process is
  // complete.
  $status = false;
  $handle = fopen(TESTFILE, "rb");
  $bytesRead = 0;
  while (!$status && !feof($handle)) {
    // read until you get $chunkSizeBytes from TESTFILE
    // fread will never return more than 8192 bytes if the stream is read buffered and it does not represent a plain file
    // An example of a read buffered file is when reading from a URL
    $chunk = readVideoChunk($handle, $chunkSizeBytes);
	$bytesRead += strlen($chunk);
	//echo PHP_EOL."chunk size(in bytes) : ".strlen($chunk);
	echo PHP_EOL."Bytes Read : ".$bytesRead;
    $status = $media->nextChunk($chunk);
  }

  // The final value of $status will be the data from the API for the object
  // that has been uploaded.
  $result = false;
  if ($status != false) {
    $result = $status;
  }

  fclose($handle);


?>