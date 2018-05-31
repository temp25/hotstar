<?php

if(!isset($_POST['url'])){
	die("Error no POST url data given");
}

$videoUrl = $_POST['url'];//"http://www.hotstar.com/tv/chinnathambi/15301/chinnathambi-yearns-for-nandini/1100003795";

exec("cp /app/youtube-dl /app/hs");

exec("chmod a+rx youtube-dl");

$output = shell_exec("./youtube-dl -j --flat-playlist ".$videoUrl);

$endCurlySearch='}
]';

$endCurlyReplace='}]';

$output=str_replace($endCurlySearch, $endCurlyReplace,"[".$output."]");

$jsonOutput=str_replace("\n", ", ", $output);

$jsonArray=json_decode($jsonOutput, true);

if(strcasecmp($videoUrl[strlen($videoUrl)-1], "/") === 0){
	//Remove the '/' in the end of url if present
	$videoUrl = substr($videoUrl, 0, -1);
}

$videoId=end(preg_split('/\//', $videoUrl));
$availability='false';
$playlistId=0;
$formats = array();

if(!is_numeric($videoId)){
	$formats["status"]=$availability;
	$formats["errorMessage"]="Invalid video ID fetched from URL";
}else{
	
	foreach($jsonArray as $key => $value){
   if(strcmp($videoId, strval($value['id'])) == 0){
     $availability='true';
     $playlistId=$key+1;
     break;
   }
 }
 
 $formats['status'] = $availability;
 
 if($availability === 'true'){
 	   
 	   //Fetch available video formats
	   $formats['source']="ydl";
 	   $formats['videoId']=$videoId;
	   $formats['playlistId'] = $playlistId;
	   $formatsQuery = "./youtube-dl -F ".$videoUrl." --playlist-items ".$playlistId;
	   $formatsBuffer = shell_exec($formatsQuery);
	   if(preg_match_all("/(hls-[0-9]+)[\s]*mp4[\s]*([0-9]+x[0-9]+)/", $formatsBuffer, $formatsResult, PREG_SET_ORDER)){
	   	   foreach ($formatsResult as $key => $value) {
	   	      $formats[$value[1]] = $value[2];
	   	   }
	   	}else{
	   		  $formats["errorMessage"]="Error in fetching video formats for the given URL";
	   	}
	   
 }else{
 	  //$formats["errorMessage"]="Can't fetch video ID or Invalid URL";
 	  //Fetching video formats and url through api request
 	  $fetchVideoScriptQuery = "php getAvailableVideoFormatsThroughApi.php ".$videoUrl;
	  
	  $result="Invalid Response";
	  $tries=0;
	  
	  //Try to fetch the stream url for the given video URL for a certain time
	  while(stripos($result,"Invalid")!==false){
		$result=exec($fetchVideoScriptQuery);
		if(++$tries > 100){
			break;
		}
	  }
	  
	  if(stripos($result,"Invalid")!==false){
		  $formats['status'] = 'false';
		  $formats["errorMessage"]="Can't fetch video ID or Invalid URL";
	  }else{
		  $formats['status'] = 'true';
		  $metadata = json_decode($result, true);
		  $formats['source']="api";
		  $formats['videoId']=$metadata['videoId'];
		  $formats['episodeNumber'] = $metadata['episodeNumber'];
		  $formats['title'] = $metadata['episode'];
		  $formats['description'] = $metadata['description'];
		  
		  foreach($metadata as $key => $value){
			  if(stripos($key,"hls")!==false){
				$formats[ $key ] = $value;
			  }
		  }
	  }
	  
 	}
 
}

echo json_encode($formats, true);

?>