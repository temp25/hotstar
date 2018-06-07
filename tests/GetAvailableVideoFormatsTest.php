<?php
use PHPUnit\Framework\TestCase;

class GetAvailableVideoFormatsTest extends TestCase
{
	
    public function testGetFormatsThroughYdl()
    {
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
		
		$this->assertTrue($result !== FALSE);
		
		$response = json_decode($result, true);
		
		$expected = array();
		$expected['status'] = "true";
		$expected['source'] = "ydl";
		$expected['videoId'] = "1100003795";
		$expected['playlistId'] = 29;
		$expected['hls-121'] = "320x180";
		$expected['hls-241'] = "320x180";
		$expected['hls-461'] = "416x234";
		$expected['hls-861'] = "640x360";
		$expected['hls-1362'] = "720x404";
		$expected['hls-2063'] = "1280x720";
		$expected['hls-3192'] = "1600x900";
		$expected['hls-4694'] = "1920x1080";
		
		$this->assertEquals($expected, $response);
		
    }
	
	 public function testGetFormatsThroughApi()
    {
		$url = 'http://hotstar-test1.herokuapp.com/getAvailableVideoFormats.php';
		$data = array('url' => 'http://www.hotstar.com/tv/khoka-babu/8828/tori-a-pampered-child/1000093817');

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
		
		$this->assertTrue($result !== FALSE);
		
		$response = json_decode($result, true);
		
		$expected = array();
		$expected['status'] = "true";
		$expected['source'] = "api";
		$expected['videoId'] = "1000093817";
		$expected['episodeNumber'] = 1;
		$expected['title'] = "Tori, a Pampered Child";
		$expected['description'] = "Starting tonight, Khoka Babu is about a happy-go-lucky girl, Tori. The only daughter of industrialist Rajsekhar, she is pampered by her father. Tori's mother, Anuradha is worried about her carefree nature. Her father fixes her engagement with Preet, but Anuradha is unhappy with the alliance.";
		$expected['hls-64 - 250x140'] = "https://staragvod2-vh.akamaihd.net/i/videos/jalsha/kb/1/1000093817_,16,54,106,180,400,800,1300,2000,3000,4500,_STAR.mp4.csmil/index_1_av.m3u8?null=0&id=AgDUL+9aX2T8HljTGFuqyb4TWT5w3SX2sFRnuzSETVywc+4lQcg5crMCw8Vqa%2fhuoOOXFvsGhmMhkw%3d%3d";
		$expected['hls-121 - 320x180'] = "https://staragvod2-vh.akamaihd.net/i/videos/jalsha/kb/1/1000093817_,16,54,106,180,400,800,1300,2000,3000,4500,_STAR.mp4.csmil/index_2_av.m3u8?null=0&id=AgDUL+9aX2T8HljTGFuqyb4TWT5w3SX2sFRnuzSETVywc+4lQcg5crMCw8Vqa%2fhuoOOXFvsGhmMhkw%3d%3d";
		$expected['hls-241 - 320x180'] = "https://staragvod2-vh.akamaihd.net/i/videos/jalsha/kb/1/1000093817_,16,54,106,180,400,800,1300,2000,3000,4500,_STAR.mp4.csmil/index_3_av.m3u8?null=0&id=AgDUL+9aX2T8HljTGFuqyb4TWT5w3SX2sFRnuzSETVywc+4lQcg5crMCw8Vqa%2fhuoOOXFvsGhmMhkw%3d%3d";
		$expected['hls-461 - 416x234'] = "https://staragvod2-vh.akamaihd.net/i/videos/jalsha/kb/1/1000093817_,16,54,106,180,400,800,1300,2000,3000,4500,_STAR.mp4.csmil/index_4_av.m3u8?null=0&id=AgDUL+9aX2T8HljTGFuqyb4TWT5w3SX2sFRnuzSETVywc+4lQcg5crMCw8Vqa%2fhuoOOXFvsGhmMhkw%3d%3d";
		$expected['hls-861 - 640x360'] = "https://staragvod2-vh.akamaihd.net/i/videos/jalsha/kb/1/1000093817_,16,54,106,180,400,800,1300,2000,3000,4500,_STAR.mp4.csmil/index_5_av.m3u8?null=0&id=AgDUL+9aX2T8HljTGFuqyb4TWT5w3SX2sFRnuzSETVywc+4lQcg5crMCw8Vqa%2fhuoOOXFvsGhmMhkw%3d%3d";
		$expected['hls-1361 - 720x404'] = "https://staragvod2-vh.akamaihd.net/i/videos/jalsha/kb/1/1000093817_,16,54,106,180,400,800,1300,2000,3000,4500,_STAR.mp4.csmil/index_6_av.m3u8?null=0&id=AgDUL+9aX2T8HljTGFuqyb4TWT5w3SX2sFRnuzSETVywc+4lQcg5crMCw8Vqa%2fhuoOOXFvsGhmMhkw%3d%3d";
		$expected['hls-2060 - 1280x720'] = "https://staragvod2-vh.akamaihd.net/i/videos/jalsha/kb/1/1000093817_,16,54,106,180,400,800,1300,2000,3000,4500,_STAR.mp4.csmil/index_7_av.m3u8?null=0&id=AgDUL+9aX2T8HljTGFuqyb4TWT5w3SX2sFRnuzSETVywc+4lQcg5crMCw8Vqa%2fhuoOOXFvsGhmMhkw%3d%3d";
		$expected['hls-3061 - 1600x900'] = "https://staragvod2-vh.akamaihd.net/i/videos/jalsha/kb/1/1000093817_,16,54,106,180,400,800,1300,2000,3000,4500,_STAR.mp4.csmil/index_8_av.m3u8?null=0&id=AgDUL+9aX2T8HljTGFuqyb4TWT5w3SX2sFRnuzSETVywc+4lQcg5crMCw8Vqa%2fhuoOOXFvsGhmMhkw%3d%3d";
		$expected['hls-4562 - 1920x1080'] = "https://staragvod2-vh.akamaihd.net/i/videos/jalsha/kb/1/1000093817_,16,54,106,180,400,800,1300,2000,3000,4500,_STAR.mp4.csmil/index_9_av.m3u8?null=0&id=AgDUL+9aX2T8HljTGFuqyb4TWT5w3SX2sFRnuzSETVywc+4lQcg5crMCw8Vqa%2fhuoOOXFvsGhmMhkw%3d%3d";
		
		echo PHP_EOL."Expected : ".PHP_EOL;
		var_dump($expected);
		
		echo PHP_EOL.PHP_EOL."Actual : ".PHP_EOL;
		var_dump($response);
		
		$this->assertEquals($expected, $response);
		
    }
	
}
?>