<?php
	
	require 'vendor/autoload.php';
	use Symfony\Component\Process\Process;
 
	if(isset($_POST['src'])){ //isset($_POST['videoUrl']) && isset($_POST['playlistId']) && isset($_POST['videoId']) && isset($_POST['videoFormat'])
	   
		$src = $_POST['src'];
		
		exec("chmod a+rx youtube-dl");
		exec("tar xvzf files.tar.gz");
		exec("chmod +x ffmpeg");
		
		$ipAddr_userAgent = $_POST['uniqueId'];
		$videoUrl=$_POST['videoUrl'];
		$playlistId=$_POST['playlistId'];
		$videoId=$_POST['videoId'];
		
		
		if($src === "ydl"){
			
			
			$videoFormat=$_POST['videoFormat'];
			
			
			$downloadVideoAndZipQuery = "./youtube-dl -f ".$videoFormat." --playlist-items ".$playlistId." ".$videoUrl." --add-metadata --ffmpeg-location /app/ffmpeg --no-warnings --exec 'zip -D -m -9 -v ".$videoId.".zip {}'";
			
			process = new Process($downloadVideoAndZipQuery);
			$process->start();
			
			foreach ($process as $type => $data) {
			   $progress = array();
			   $progress['videoId'] = $videoId;
			   $progress['data'] = nl2br($data);
			   sendProgressToClient($progress, $ipAddr_userAgent);
			}
			
		}else{
			
			$videoTitle=$_POST['title'];
			$videoDescription=$_POST['description'];
			
			$outputFileName = .$videoId.".ts";
			
			zip
			
			$videoStreamQuery = "./ffmpeg -i \"".$videoUrl.
							"\" -c copy -metadata title=\"".$videoTitle.
							"\" -metadata episode_id=\"".$playlistId.
							"\" -metadata track=\"".$videoId.
							"\" -metadata description=\"".$videoDescription.
							"\" -metadata synopsis=\"".$videoDescription.
							"\" ".$outputFileName;
			
			exec zip -D -m -9 -v Video.zip $outputFileName
			
			process = new Process($videoStreamQuery);
			$process->start();
			
			foreach ($process as $type => $data) {
			   $progress = array();
			   $progress['videoId'] = $videoId;
			   $progress['data'] = nl2br($data);
			   sendProgressToClient($progress, $ipAddr_userAgent);
			}
			
		}
		
		progress = array();
		$progress['videoId'] = $videoId;
		$progress['data'] = nl2br("\nVideo generation complete...");
		$progress['hasProgress']='false';
				
		sendProgressToClient($progress, $ipAddr_userAgent);
		  
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