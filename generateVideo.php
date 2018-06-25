<?php
	
	if(isset($_POST['src'])){ //isset($_POST['videoUrl']) && isset($_POST['playlistId']) && isset($_POST['videoId']) && isset($_POST['videoFormat'])
	   
		$outputfile = "processOutput.log";
		$pidfile = "processVideoPid.log";
		
		$data = array();
		$data['src'] = $_POST['src'];
		$data['uniqueId'] = $_POST['uniqueId'];
		$data['videoUrl'] = $_POST['videoUrl'];
		$data['playlistId'] = $_POST['playlistId'];
		$data['videoId'] = $_POST['videoId'];
		$data['videoFormat'] = $_POST['videoFormat'];
		$data['title'] = $_POST['title'];
		$data['description'] = $_POST['description'];
		
		
		//exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile));
		
		$cmd = "php generateVideoBg.php ".$data;
		
		exec($cmd);
		
		echo "Generating video...";
	}
?>