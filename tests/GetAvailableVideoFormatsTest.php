<?php
use PHPUnit\Framework\TestCase;

class GetAvailableVideoFormatsTest extends TestCase
{
	
    public function testGetFormats()
    {
		
		// create our http client (Guzzle)
		$client = new Guzzle\Client('http://hotstar-test1.herokuapp.com', array(
			'request.options' => array(
				'exceptions' => false,
			)
		));
		
		$data = array();
		$data['url'] = "http://www.hotstar.com/tv/chinnathambi/15301/chinnathambi-yearns-for-nandini/1100003795";
		
		$request = $client->post('/getAvailableVideoFormats.php', null, json_encode($data));
		$response = $request->send();

		var_dump($response);
		
		$this->assertEquals(200, $response->getStatusCode());
		
    }
}
?>