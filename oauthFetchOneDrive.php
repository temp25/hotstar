<?php
	$config = include '/app/OneDriveConfig.php';
	require_once 'vendor/autoload.php';

	use GuzzleHttp\Client as GuzzleHttpClient;
	use Krizalys\Onedrive\Client;
	use Microsoft\Graph\Graph;
	use Monolog\Logger;

	$client = new Krizalys\Onedrive\Client(
		$config['ONEDRIVE_CLIENT_ID'],
		new Microsoft\Graph\Graph(),
		new GuzzleHttp\Client(
			['base_uri' => 'https://graph.microsoft.com/v1.0/']
		),
		new Monolog\Logger('Krizalys\Onedrive\Client')
	);

	$oauth_auth_url = $client->getLogInUrl([
		'files.read',
		'files.read.all',
		'files.readwrite',
		'files.readwrite.all',
		'offline_access',
	], $config['ONEDRIVE_REDIRECT_URI']);
	
	$sanitized_auth_url = filter_var($oauth_auth_url, FILTER_SANITIZE_URL);
	echo $sanitized_auth_url;
	//header('Location: ' . $sanitized_auth_url);
?>