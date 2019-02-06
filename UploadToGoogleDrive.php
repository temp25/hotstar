<?php
include 'vendor/autoload.php';
use Symfony\Component\Process\Process;

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

/**
 * Respond 200 OK with an optional
 * This is used to return an acknowledgement response indicating that the request has been accepted and then the script can continue processing
 *
 * @param null $text
 */
function respondOK($text = null)
{
    // check if fastcgi_finish_request is callable
    if (is_callable('fastcgi_finish_request')) {
        if ($text !== null) {
            echo $text;
        }
        /*
         * http://stackoverflow.com/a/38918192
         * This works in Nginx but the next approach not
         */
        session_write_close();
        fastcgi_finish_request();
        
        return;
    }
    
    ignore_user_abort(true);
    
    ob_start();
    
    if ($text !== null) {
        echo $text;
    }
    
    $serverProtocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL', FILTER_SANITIZE_STRING);
    header($serverProtocol . ' 200 OK');
    // Disable compression (in case content length is compressed).
    header('Content-Encoding: none');
    header('Content-Length: ' . ob_get_length());
    
    // Close the connection.
    header('Connection: close');
    
    ob_end_flush();
    ob_flush();
    flush();
}

function sendProgressToClient($progress, $ipAddr_userAgent)
{
    
    $options = array(
        'cluster' => 'ap2',
        'encrypted' => true
    );
    
    $pusher = new Pusher\Pusher('a44d3a9ebac525080cf1', '37da1edfa06cf988f19f', '505386', $options);
    
    $message['message'] = $progress;
    
    $pusher->trigger('hotstar-video-download-v1-uploadVideoGoogleDrive', $ipAddr_userAgent, $message);
    
}


if(isset($_POST)){
	$authCode = urldecode($_POST["authCode"]);
	$ipAddr_userAgent = $_POST["uniqueId"];
	$videoFileName = $_POST["fileName"];
	$progress = array();
	$progress["uploadStatus"] = "uploading";
	
	respondOK(); //send the response to client
	
	$gdriveUploadCommand = "php gdrive.php ".$authCode." ".$videoFileName;
	$progress["uploadProgress"] = "__".$gdriveUploadCommand."__";
	sendProgressToClient($progress, $ipAddr_userAgent);
	
	$process = new Process($gdriveUploadCommand);
	$process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
	$process->start();

	foreach ($process as $type => $data) {
		$progress["uploadProgress"] = $data;
		sendProgressToClient($progress, $ipAddr_userAgent);
	}
	
	$progress["uploadStatus"] = "uploaded";
	sendProgressToClient($progress, $ipAddr_userAgent);
}