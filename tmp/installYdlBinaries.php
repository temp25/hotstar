<?php
	$ydlReleaseUrl = "https://yt-dl.org/downloads/latest/youtube-dl";
	echo "Downloading youtube-dl binary...";
	shell_exec("wget -q ".$ydlReleaseUrl." -O youtube-dl");
	echo "youtube-dl binary downloaded...";
?>