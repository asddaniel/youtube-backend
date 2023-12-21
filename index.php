<?php 
include("./vendor/autoload.php");
use YouTube\YouTubeDownloader;
use YouTube\Exception\YouTubeException;

function handleCors() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("HTTP/1.1 200 OK");
        exit();
    }
}

handleCors();

$youtube = new YouTubeDownloader();
header('Content-Type: application/json');
try {
    $action = isset($_GET['videourl']) ? $_GET['videourl'] : '';

     if(!$action) {
        echo json_encode(["info" => null, "options" => []]);
        die;
     }
    $downloadOptions = $youtube->getDownloadLinks($action);
    $options = $downloadOptions->getAllFormats();
    $info = $downloadOptions->getInfo();
    // print_r($options);
    echo json_encode(["info" => $info, "options" => $options]); //$options;
    die;

    if ($downloadOptions->getAllFormats()) {
        echo $downloadOptions->getFirstCombinedFormat()->url;
    } else {
        echo 'No links found';
    }

} catch (YouTubeException $e) {
    echo 'Something went wrong: ' . $e->getMessage();
}
