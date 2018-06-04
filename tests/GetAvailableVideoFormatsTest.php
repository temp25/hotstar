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
		
		$content = (string) $response->getBody();
		
		echo "Response : ".$content;
		var_dump($content);
		
		$this->assertEquals(200, $response->getStatusCode());
		
    }
}
?>