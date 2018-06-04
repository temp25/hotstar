<?php
use PHPUnit\Framework\TestCase;

class GetAvailableVideoFormatsTest extends TestCase
{
	protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => ''
        ]);
    }
	
    public function testGetFormats()
    {
		$data = array();
		$data['url'] = "http://www.hotstar.com/tv/chinnathambi/15301/chinnathambi-yearns-for-nandini/1100003795";
		
		$request = $client->post('http://hotstar-test1.herokuapp.com/getAvailableVideoFormats.php', null, json_encode($data));
		$response = $request->send();

		var_dump($response);
		
		$this->assertEquals(200, $response->getStatusCode());
		
    }
}
?>