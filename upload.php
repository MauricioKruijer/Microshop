<?php
//$tempDir = __DIR__ . DIRECTORY_SEPARATOR . 'temp';
//if (!file_exists($tempDir)) {
//	mkdir($tempDir);
//}
//if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//	$chunkDir = $tempDir . DIRECTORY_SEPARATOR . $_GET['flowIdentifier'];
//	$chunkFile = $chunkDir.'/chunk.part'.$_GET['flowChunkNumber'];
//	if (file_exists($chunkFile)) {
//		header("HTTP/1.0 200 Ok");
//	} else {
//		header("HTTP/1.1 204 No Content");
//	}
//}
//// Just imitate that the file was uploaded and stored.
//sleep(2);
//echo json_encode([
//    'success' => true,
//    'files' => $_FILES,
//    'get' => $_GET,
//    'post' => $_POST,
//    //optional
//    'flowTotalSize' => isset($_FILES['file']) ? $_FILES['file']['size'] : $_GET['flowTotalSize'],
//    'flowIdentifier' => isset($_FILES['file']) ? $_FILES['file']['name'] . '-' . $_FILES['file']['size']
//        : $_GET['flowIdentifier'],
//    'flowFilename' => isset($_FILES['file']) ? $_FILES['file']['name'] : $_GET['flowFilename'],
//    'flowRelativePath' => isset($_FILES['file']) ? $_FILES['file']['tmp_name'] : $_GET['flowRelativePath']
//]);

require_once __DIR__ . '/vendor/autoload.php';

define("APP_ROOT", "/Users/Mauricio/Sites/microshop/app/");
define("PROJECT_ROOT", "/Users/Mauricio/Sites/microshop/");

require_once __DIR__ . '/app/config/config.php';

$chunkDir = PROJECT_ROOT . 'temp';
$uploadDir = PROJECT_ROOT . 'webroot/uploads';
$config = new \Flow\Config();
//    $config->setTempDir('./chunks_temp_folder');
$config->setTempDir($chunkDir);
$file = new \Flow\File($config);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($file->checkChunk()) {
        header("HTTP/1.1 200 Ok");
    } else {
        header("HTTP/1.1 204 No Content");
        return ;
    }
} else {
    if ($file->validateChunk()) {
        $file->saveChunk();
    } else {
        // error, invalid chunk upload request, retry
        header("HTTP/1.1 400 Bad Request");
        return ;
    }
}
if ($file->validateFile() && $file->save($uploadDir.'/final_file_name')) {
    // File upload was completed
    echo "JA";
} else {
    // This is not a final chunk, continue to upload
    echo "NEE";
}
