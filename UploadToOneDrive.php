<?php
include 'vendor/autoload.php';

if(isset($_POST)){
	$authCode = urldecode($_POST["authCode"]);
	$videoFileName = $_POST["fileName"];
	$oneDriveUploadCommand = "php onedrive.php ".$authCode." ".$videoFileName;
	
	$oneDriveUploadOutput = shell_exec($oneDriveUploadCommand);
	
	echo $oneDriveUploadOutput;
}