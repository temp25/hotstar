<?php
	
	require 'vendor/autoload.php';
	use Symfony\Component\Process\Process;
 
	if($argc > 1 && isset($argv[1])){//isset($_POST['src'])){ //isset($_POST['videoUrl']) && isset($_POST['playlistId']) && isset($_POST['videoId']) && isset($_POST['videoFormat'])
	   
		$postData = unserialize($argv[1]);
		
		$src = $postData['src'];
		
		exec("chmod a+rx youtube-dl");
		exec("tar xvzf files.tar.gz");
		exec("chmod +x ffmpeg");
		
		$ipAddr_userAgent = $postData['uniqueId'];
		$videoUrl=$postData['videoUrl'];
		$playlistId=$postData['playlistId'];
		$videoId=$postData['videoId'];
		
		
		if($src === "ydl"){
			
			//echo "ydl source detected. generating video with that";
			$videoFormat=$postData['videoFormat'];
			
			
			$downloadVideoAndZipQuery = "./youtube-dl -f ".$videoFormat." --playlist-items ".$playlistId." ".$videoUrl." --add-metadata --ffmpeg-location /app/ffmpeg --no-warnings --exec 'zip -D -m -9 -v ".$videoId.".zip {}'";
			
			$process = new Process($downloadVideoAndZipQuery);
			$process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
			$process->start();
			
			foreach ($process as $type => $data) {
			   $progress = array();
			   $progress['videoId'] = $videoId;
			   $progress['data'] = nl2br($data);
			   sendProgressToClient($progress, $ipAddr_userAgent);
			}
			
		}else{
			
			//echo "api source detected. generating video with that";
			
			$videoTitle=$postData['title'];
			$videoDescription=$postData['description'];
			
			$outputFileName = $videoId.".ts";
			
			$zipOutputQuery = "zip -D -m -9 -v ".$videoId.".zip ".$outputFileName;
			
			$videoStreamQuery = "./ffmpeg -i \"".$videoUrl.
							"\" -c copy -metadata title=\"".$videoTitle.
							"\" -metadata episode_id=\"".$playlistId.
							"\" -metadata track=\"".$videoId.
							"\" -metadata description=\"".$videoDescription.
							"\" -metadata synopsis=\"".$videoDescription.
							"\" ".$outputFileName;
			
			$process = new Process($videoStreamQuery);
			$process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
			$process->start();
			
			foreach ($process as $type => $data) {
			   $progress = array();
			   $progress['videoId'] = $videoId;
			   $progress['data'] = nl2br($data);
			   sendProgressToClient($progress, $ipAddr_userAgent);
			}
			
			$process = new Process($zipOutputQuery);
			$process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
			$process->start();
			
			foreach ($process as $type => $data) {
			   $progress = array();
			   $progress['videoId'] = $videoId;
			   $progress['data'] = nl2br($data);
			   sendProgressToClient($progress, $ipAddr_userAgent);
			}
			
		}
		
		$progress = array();
		$progress['videoId'] = $videoId;
		$progress['data'] = nl2br("\nVideo generation complete...");
		$progress['hasProgress']='false';
				
		sendProgressToClient($progress, $ipAddr_userAgent);
		  
	}else{
		//TODO: Add the code here to handle if post variables aren't set properly.
		
		echo "Invalid script invocation";
		$ipAddr_userAgent = $postData['uniqueId'];
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