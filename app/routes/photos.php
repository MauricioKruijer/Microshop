<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 16/06/15
 * Time: 11:20
 */

$this->respond(array("GET", "POST"), '/add.json', function($req, $res) {
    $chunkDir = PROJECT_ROOT . 'temp';
    $uploadDir = PROJECT_ROOT . 'webroot/uploads';

    $config = new \Flow\Config();
    $config->setTempDir($chunkDir);

    $file = new \Flow\File($config);

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($file->checkChunk()) {
//            header("HTTP/1.1 200 Ok");
//            $headerKey = "HTTP/1.1";
//            $headerVal = "200 Ok";
        } else {
//            header("HTTP/1.1 204 No Content");
            sleep(3);
            return $res->header("HTTP/1.1", "204 No Content")->send();
//            return ;
        }
    } else {
        if ($file->validateChunk()) {
            $file->saveChunk();
        } else {
            // error, invalid chunk upload request, retry
//            header("HTTP/1.1 400 Bad Request");
            return $res->header("HTTP/1.1", "400 Bad Request")->send();
        }
    }
    $length = 64;
    $bytes = openssl_random_pseudo_bytes($length * 2);
    $randomName = substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
    $uploadedFile = $uploadDir.'/'.$randomName;

    if ($file->validateFile() && $file->save($uploadedFile)) {

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileMimeType = finfo_file($finfo, $uploadedFile);
        finfo_close($finfo);

        $allowedExtension = [
            'image/gif' => '.gif',
            'image/jpeg' => '.jpg',
            'image/png' => '.png'
        ];

        if(in_array($fileMimeType, array_keys($allowedExtension))) {
            $newFileName = $uploadedFile . $allowedExtension[$fileMimeType];
            rename($uploadedFile, $newFileName);
            $res->json(['success',$newFileName]);
        } else {
            $res->json(['error']);
        }
        // File upload was completed
//        echo "JA";

    } else {
        // This is not a final chunk, continue to upload
        echo "NEE";
    }

});