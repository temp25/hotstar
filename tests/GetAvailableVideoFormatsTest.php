<?php
use PHPUnit\Framework\TestCase;

class GetAvailableVideoFormatsTest extends TestCase
{
	
    public function testGetFormats()
    {
		
		// create our http client (Guzzle)
		$client = new \GuzzleHttp\Client();
		
		$body['url'] = "http://www.hotstar.com/tv/chinnathambi/15301/chinnathambi-yearns-for-nandini/1100003795";
		$url = "http://hotstar-test1.herokuapp.com/getAvailableVideoFormats.php";
		$response = new \GuzzleHttp\Psr7\Request("POST", $url, ['body'=>$body]);
		$response = $client->send($response);
		
		$this->assertEquals(200, $response->getStatusCode());
		
		
		$url = 'http://hotstar-test1.herokuapp.com/getAvailableVideoFormats.php';
		$data = array('url' => 'http://www.hotstar.com/tv/chinnathambi/15301/chinnathambi-yearns-for-nandini/1100003795');

		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ }

		echo PHP_EOL."Result : ".PHP_EOL;
		var_dump($result);
		
    }
}
?>