<?php

require_once("vendor/autoload.php");
use Symfony\Component\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

if(isset($_POST)){
	$authCode = urldecode($_POST["authCode"]);
	$videoFileName = $_POST["fileName"];
	$gdriveUploadCommand = "php gdriveUpload.php ".$authCode." ".$videoFileName;
	
	$process = new Symfony\Component\Process\Process(["php", "gdriveUpload.php", $authCode, $videoFileName]);
	//echo "Command is : "$process->getCommandLine();
	$process->run();
	
	if (!$process->isSuccessful()) {
		throw new Symfony\Component\Process\Exception\ProcessFailedException($process);
	}
	
	echo $process->getOutput();
}else{
	die("Invalid script invocation. Error : No POST data given");
}

?>