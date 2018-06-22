<?php
	
	include('src/VideoFormats.php');
	
	if(!isset($_POST['url'])){
		die("Error no POST url data given");
	}

	$videoUrl = $_POST['url'];

	$videoFormats = new VideoFormats($videoUrl);

	$formats = $videoFormats->isAvailable();

	echo json_encode($formats, true);

?>