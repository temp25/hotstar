<?php

if (!isset($_GET['videoId'])) {
    die("Invalid invocation of file");
}

$filename = $_GET["videoId"] . ".zip";
$absoluteFilePath = getcwd() . DIRECTORY_SEPARATOR . $filename;

if(is_file($absoluteFilePath)){
    // http headers for zip downloads
    
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-type: application/zip, application/octet-stream");
    header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . filesize($absoluteFilePath));
    
    $file = fopen($absoluteFilePath, "r");
    
    while(!feof($file)) {
        print(fread($file, 8 * 1024)); // 8KiB
        ob_flush();
        flush();
        if(connection_status()!=0){
            fclose($file);
            exit;
        }
    }
    
    fclose($file);
    exit;
} else {
    die("File $filename doesn't exist...");
}

?>