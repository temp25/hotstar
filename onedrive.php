<?php

$config = include '/app/OneDriveConfig.php';
require_once 'vendor/autoload.php';
ini_set('memory_limit','2048M'); //set memory limit as 2GB

use GuzzleHttp\Client as GuzzleHttpClient;
use Krizalys\Onedrive\Client;
use Microsoft\Graph\Graph;
use Monolog\Logger;

function getFileOrFolderId($children, $type, $name) {
	foreach($children as $i => $child){
		$tmpFile = $child->file;
		$tmpFolder = $child->folder;

		//check for drive item type based on $type arg
		$isFolder = ($tmpFile === NULL) && ($tmpFolder != NULL);
		$typeCheck = $type === "folder" ? $isFolder : !$isFolder;
		
		//check if item is file/folder and has custom name specified above and if exists retrive it's id and return it
		if( $typeCheck && strcmp($child->name, $name)==0) {
			return $child;
		}
	}
	return NULL;
}

function resolveFileNameConflict($folderItems, $videoFileName) {
	$tmpFileName = getFileOrFolderId($folderItems, "file", $videoFileName);

	//return name directly as it doesn't conflict with existing ones.
	if($tmpFileName === NULL) {
		return $videoFileName;
	}else{
		$resolvedName="";
		
		if(preg_match('/(.*)\.(\w+)/', $videoFileName, $match)){
			$name = $match[1];
			$ext = $match[2];
			if (preg_match('/(.*)_(\d+)/', $name, $match1)) {
				$tmpName = $match1[1];
				$id = (int)$match1[2];
				$resolvedName = $tmpName . "_" . ($id + 1) . "." . $ext;
			} else {
				$resolvedName = $name . "_1". "." . $ext;
			}
		} else {
			if (preg_match('/(.*)_(\d+)/', $videoFileName, $match)) {
				$name = $match[1];
				$id = (int)$match[2];
				$resolvedName = $name . "_" . ($id + 1) . "." . $ext;
			} else {
				$resolvedName = $videoFileName . "_1" . $ext;
			}
		}
		return resolveFileNameConflict($folderItems, $resolvedName);
	}
}

if($argc < 3){
	die("Invalid script invocation");
}

$authCode = $argv[1];
$videoFileName = $argv[2];

try {
	$client = new Krizalys\Onedrive\Client(
		$config['ONEDRIVE_CLIENT_ID'],
		new Microsoft\Graph\Graph(),
		new GuzzleHttp\Client(
			['base_uri' => 'https://graph.microsoft.com/v1.0/']
		),
		new Monolog\Logger('Krizalys\Onedrive\Client')
	);

	$oauthUrl = $client->getLogInUrl([
		'files.read',
		'files.read.all',
		'files.readwrite',
		'files.readwrite.all',
		'offline_access',
	], $config['ONEDRIVE_REDIRECT_URI']);

	$client->obtainAccessToken($config['ONEDRIVE_CLIENT_SECRET'], $authCode);

	//Get all drives
	$drives = $client->getDrives();

	//Select personal drive
	$rootDrive = $drives[0]->getRoot();

	//Get drive items
	$children = $rootDrive->getChildren();

	$folderList = array();
	$folderName="HotstarVideos";
	$folderId=NULL;

	$folder=getFileOrFolderId($children, "folder", $folderName);

	//create a folder if doesn't exist
	if($folder === NULL){
		$folder = $rootDrive->createFolder($folderName, ['description' => 'Hotstar videos generated by downloader']);
	}
	$folderId = $folder->id;
	
	$folderItems = $folder->getChildren();

	$fileContents = file_get_contents($videoFileName);
	
	$resolvedVideoFileName = resolveFileNameConflict($folderItems, $videoFileName);

	//echo PHP_EOL."Resolved Filename : ".$resolvedVideoFileName;

	$videoFileItem = $folder->upload($resolvedVideoFileName, $fileContents, ['Content-Type' => 'application/zip', 'description' => $resolvedVideoFileName]);
	
	echo "File uploaded successfully";

} catch(Exception $e) {
	echo "Error occurred. Error Message : ".$e->getMessage();
}


?>