<?

include '../vendor/autoload.php';
ini_set('max_execution_time', 300); // this will set max_execution time for 300 seconds

if(isset($_POST) && isset($_POST["action"])) {
	
	$action = $_POST["action"];
	
	//$ipAddr_userAgent = $_POST['uniqueId'];
	$client = new Google_Client();
	$client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
	$client->setClientId('905044047037-h0pl1t3r3qlimegtjd5h3q2u24pebqpl.apps.googleusercontent.com');
	$client->setClientSecret('Dc0BijZKsFzLYwCBm_eTY-Sf');
	$client->setRedirectUri("https://hotstar-test1.herokuapp.com");
	$client->setScopes(array('https://www.googleapis.com/auth/drive'));
	$service = new Google_Service_Drive($client);
	
	if($action=="getAuthUrl"){
		echo $client->createAuthUrl();
	} else if($action=="uploadFile" && isset($_POST["authCode"])){
		shell_exec("wget -q http://mattmahoney.net/dc/enwik8.zip");
		shell_exec("unzip -o -qq enwik8.zip");
		shell_exec("mv enwik8 enwik8.txt");
		
		respondOK();
		
		$authCode = $_POST["authCode"];
		
	}else{
		echo "Error occurred :-(";
	}

}else{
	echo "Invalid invocation";
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

function sendDataToClient($data, $ipAddr_userAgent)
{
    
    $options = array(
        'cluster' => 'ap2',
        'encrypted' => true
    );
    
    $pusher = new Pusher\Pusher('a44d3a9ebac525080cf1', '37da1edfa06cf988f19f', '505386', $options);
    
    $message['message'] = $data;
    
    $pusher->trigger('gdrive', $ipAddr_userAgent, $message);
    
}


?>