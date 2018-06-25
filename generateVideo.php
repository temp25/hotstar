<?php
	
	if(isset($_POST['src'])){ //isset($_POST['videoUrl']) && isset($_POST['playlistId']) && isset($_POST['videoId']) && isset($_POST['videoFormat'])
	   
		$cmd = "php generateVideoBg.php ".$_POST;
		$outputfile = "processOutput.log";
		$pidfile = "processVideoPid.log";
		
		exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile));
		
		echo "Generating video...";
	}
?>