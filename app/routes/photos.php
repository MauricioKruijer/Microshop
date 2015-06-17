<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 16/06/15
 * Time: 11:20
 */

use Microshop\Models\Photo;
use Microshop\Services\PhotoService;

$this->respond(array("GET", "POST"), '/add.json', function($req, $res, $service, $app) {
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
//            sleep(3);
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
            $fullFilename = $randomName  . $allowedExtension[$fileMimeType];

            rename($uploadedFile, $newFileName);
            list($imageWidth, $imageHeight) = getimagesize($newFileName);

            $checksum = sha1_file($newFileName);

            $photo = [
                'width' => $imageWidth,
                'height' => $imageHeight,
                'name' => $fullFilename,
                'type' => $fileMimeType,
                'path' => str_replace(PROJECT_ROOT, '', $uploadDir),
                'checksum' => $checksum
            ];
//            var_dump($photo);
            $photoService = new PhotoService($app->db);
            $photo = new Photo($photo);

            $photoId = $photoService->persist($photo);
//            var_dump($photo);
            $res->json(['success' => ['message' => 'Successfully uploaded image', 'image_id' => $photoId]]);
        } else {
            unlink($uploadedFile);
            $res->json(['error' => ['message'=> 'Type not allowed']]);
        }
        // File upload was completed
//        echo "JA";

    } else {
        // This is not a final chunk, continue to upload
        echo "NEE";
    }

});