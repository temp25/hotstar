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
		$response = $client->createRequest("POST", $url, ['body'=>$body]);
		$response = $client->send($response);
		
		var_dump($response);
		
		$this->assertEquals(200, $response->getStatusCode());
		
    }
}
?>