	<?php
	
	require 'vendor/autoload.php';
 use Symfony\Component\Process\Process;
 
	if(isset($_POST['videoUrl']) && isset($_POST['playlistId']) && isset($_POST['videoId']) && isset($_POST['videoFormat'])){
	   
	   	$videoUrl=$_POST['videoUrl'];
		  $playlistId=$_POST['playlistId'];
		  $videoId=$_POST['videoId'];
		  $videoFormat=$_POST['videoFormat'];
		  $ipAddr_userAgent = $_POST['uniqueId'];
		  
		  exec("chmod a+rx youtube-dl");
		  exec("tar xvzf files.tar.gz");
		  
		  $downloadVideoAndZipQuery = "./youtube-dl -f ".$videoFormat." --playlist-items ".$playlistId." ".$videoUrl." --add-metadata --ffmpeg-location /app/ffmpeg --no-warnings --exec 'zip -D -m -9 -v ".$videoId.".zip {}'";
		  
		  
		  //$videoGenerateQuery="php downloadHotstarVideo.php ".$videoUrl." ".$playlistId." ".$videoId." ".$videoFormat;
		  
		  $process = new Process($downloadVideoAndZipQuery);
	   $process->start();
	   
	   foreach ($process as $type => $data) {
	   	   
	   	   $progress = array();
	   	   $progress['videoId'] = $videoId;
			    $progress['data'] = nl2br($data);
			    $progress['hasProgress']='false';
			    
			    /*
			    if(preg_match_all("/(\d{2})\:(\d{2})\:(\d{2})\.(\d{2})/", $data, $res, PREG_SET_ORDER)){
			    	   $hours = $res[0][1] * 1;
			    	   $minutes = $res[0][2] * 1;
			    	   $seconds = $res[0][3] * 1;
			    	   $microseconds = $res[0][4] * 1;
			    	   
			    	   $totalSeconds = $hours * 60 * 60 + $minutes * 60 + $seconds + ($microseconds/1000000);
			    	   
			    	   $completionPercentage=1;
			    	   
			    	   if($isTotalDuration ==='true'){
			    	   	   $isTotalDuration='false';
			    	   	   $totalDurationStr=$res[0][0];
			    	   	   $totalDuration=$totalSeconds;
			    	   }else{
			    	   	   //Calculate completion of the task for 1 less than 100
			    	   	   $completionPercentage = round((($totalSeconds/$totalDuration)*99), 2);
			    	   }
			    	   
			    	   $progress['completionPercentage']=$completionPercentage;
			    	   $progress['hasProgress']='true';
			    	   
			    }
			    */
			    
			    sendProgressToClient($progress, $ipAddr_userAgent);
			    
	   	}
	   	
	   	   $progress = array();
	   	   $progress['videoId'] = $videoId;
			    $progress['data'] = nl2br("\nVideo generation complete...");
			    $progress['hasProgress']='false';
			    
	   	sendProgressToClient($progress, $ipAddr_userAgent);
	   	
	   	/*
	   	$progress = array();
	   	$progress['videoId'] = $videoId;
	   	$progress['hasProgress']='false';
	   	
	   	if($isErrorInDownload === TRUE)
	      	$progress['data'] = nl2br("\n\nError occurred in downloading the file");
	   	else
	      	$progress['data'] = nl2br("\n\nDownload complete...");
	      	
	   sendProgressToClient($progress, $ipAddr_userAgent);
	   */
	   	
		  
	}else{
		//TODO: Add the code here to handle if post variables aren't set properly.
		
		echo "Invalid script invocation";
		$ipAddr_userAgent = $_POST['uniqueId'];
		$progress = array();
		$progress['hasProgress']='false';
		$progress['data'] = nl2br("Error occurred in receiving the post form data from the client");
		
		sendProgressToClient($progress, $ipAddr_userAgent);
		
	}
	
	
	
	function sendProgressToClient($progress, $ipAddr_userAgent){
		
		$options = array( 
      'cluster' => 'ap2', 
      'encrypted' => true 
   ); 
    
   $pusher = new Pusher\Pusher( 
      'a44d3a9ebac525080cf1', 
      '37da1edfa06cf988f19f', 
      '505386', 
      $options 
   );

    $message['message'] = $progress;
    
    $pusher->trigger(
       'test-hotstar-video-download1', 
       $ipAddr_userAgent, 
       $message
    );
		   
	}
	
	?>