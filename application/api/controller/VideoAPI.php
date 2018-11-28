<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/24
 * Time: 20:03
 */

namespace app\api\controller;


use app\extra\Vupload;
use think\Controller;
use think\Request;

class VideoAPI extends Controller
{
    const UPLOADS_BASE_PATH = '/public/upload/video';

    public function upload_video(Request $request){
        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Access-Control-Allow-origin:*");
        //header("Access-Control-Allow-Credentials:true");
        //header('Access-Control-Allow-Headers:x-requested-with,content-type');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }

        if ( !empty($_REQUEST[ 'debug' ]) ) {
            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
            if ( $random === 0 ) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }

// 5 minutes execution time
        @set_time_limit(5 * 60);

        $targetDir = 'upload_tmp';   //切片保留路径
        $uploadDir = 'upload';       //最终上传路径

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds


// Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

// Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir);
        }

// Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

// Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

// Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                // echo '123';
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id","uploadPath":$uploadPath}');
            }
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }
// Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }
        if ( $done ) {
            if (!$out = @fopen($uploadPath, "wb")) {
                // echo '1';
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
            if ( flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }

                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }

                flock($out, LOCK_UN);
            }
            @fclose($out);
        }
// Return Success JSON-RPC response
//echo ('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }

    public function chunk_upload(){
        //创建临时文件，里面放分片
        if(!file_exists(UPLOADS_BASE_PATH.$this->shardName)){
            @mkdir(UPLOADS_BASE_PATH.$this->shardName, 0777, true);
        }
        if(!file_exists(UPLOADS_BASE_PATH.$this->targetDir)){
            @mkdir(UPLOADS_BASE_PATH.$this->targetDir, 0777, true);
        }
        //创建合并所放后文件夹
        if(!file_exists(UPLOADS_BASE_PATH.$this->sourceFileName)){
            @mkdir(UPLOADS_BASE_PATH.$this->sourceFileName, 0777, true);
        }
        if(!file_exists(UPLOADS_BASE_PATH.$this->uploadDir)){
            @mkdir(UPLOADS_BASE_PATH.$this->uploadDir, 0777, true);
        }
        //创建图片文件夹
        if(!file_exists(UPLOADS_BASE_PATH.$this->imgFileName)){
            @mkdir(UPLOADS_BASE_PATH.$this->sourceFileName, 0777, true);
        }
        if(!file_exists(UPLOADS_BASE_PATH.$this->imgFile)){
            @mkdir(UPLOADS_BASE_PATH.$this->imgFile, 0777, true);
        }
        //创建转码后文件夹
        if(!file_exists(UPLOADS_BASE_PATH.$this->transcodeVideoName)){
            @mkdir(UPLOADS_BASE_PATH.$this->transcodeVideoName, 0777, true);
        }
        if(!file_exists(UPLOADS_BASE_PATH.$this->transcodeVideo)){
            @mkdir(UPLOADS_BASE_PATH.$this->transcodeVideo, 0777, true);
        }
        //创建短视频文件夹
        if(!file_exists(UPLOADS_BASE_PATH.$this->shortVideoName)){
            @mkdir(UPLOADS_BASE_PATH.$this->shortVideoName, 0777, true);
        }
        if(!file_exists(UPLOADS_BASE_PATH.$this->shortVideo)){
            @mkdir(UPLOADS_BASE_PATH.$this->shortVideo, 0777, true);
        }
    }

}