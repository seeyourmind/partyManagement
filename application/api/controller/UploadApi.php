<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/3
 * Time: 22:15
 */

namespace app\api\controller;

use app\api\model\VideoManagement;
use think\Controller;
use think\Log;
use think\Request;

class UploadApi extends Controller
{

    public function uploadImage(Request $request){
        is_null($request) && $request;

        if($request->isPost()){
            $image = $request->file('upload');
            $flag = 'F';
            $msg = '上传图片失败!';
//            return json([
//                'image' => $image
//            ]);
            if(!empty($image)){
                $info = $image->move((ROOT_PATH.'public'.DS.'uploads'.DS.'images'));
                if($info){
                    $filePath = $info->getSaveName();
                    $filePath = str_replace('\\', '/', $filePath);
                    $flag = 'S';
                    $msg = $filePath;
                    return json([
                        'uploaded' => 1,
                        'url' => '/uploads/images/'.$msg,
                        'flag' => $flag,
                        'msg' => '/uploads/images/'.$msg
                    ]);
                } else {
                    return json([
                        'uploaded' => 0,
                        'url' => $msg,
                        'flag' => $flag,
                        'msg' => $msg
                    ]);
                }
            } else {
                $msg = '未获取到上传的图像数据';
                return json([
                    'uploaded' => 0,
                    'url' => $msg,
                    'flag' => $flag,
                    'msg' => $msg
                ]);
            }
        } else {
            return json([
                'flag' => 'F',
                'msg' => '您的访问方式有误，请使用POST方式访问服务器！'
            ]);
        }
    }

    public function  uploadVideoFromMine(){
        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        session_start();
        // Support CORS
        // header("Access-Control-Allow-Origin: *");
        // other CORS headers if any...
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
        // header("HTTP/1.0 500 Internal Server Error");
        // exit;
        // 5 minutes execution time
        @set_time_limit(5 * 60);
        // Uncomment this one to fake upload time
        // usleep(5000);
        // Settings
        // $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $targetDir = ROOT_PATH.'public'.DS.'uploads'.DS.'video'.DS.'tmp';
        $uploadDir = ROOT_PATH.'public'.DS.'uploads'.DS.'video';
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
        // Create target dir
        Log::record('Create target temp dir');
        if (!file_exists($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }
        Log::record('Create target upload dir');
        // Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }
        // 写入分片
        if(!isset($_POST['status'])){
            if(isset($_SESSION['chunkName'])){
                $fileName = $_SESSION['chunkName'];
            } else {
                $fileName = uniqid("file_");
            }

            Log::record('Create file name use uniqid '.$fileName);
            $filePath = $targetDir . DS . $fileName;
            $uploadPath = $uploadDir . DS . $fileName;
            // Chunking might be enabled
//            $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
//            $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
            $chunk = 0;
            $chunks = 1;
            Log::record('check chunk:'.$chunk.' and chunks:'.$chunks);
            // Remove old temp files
            if ($cleanupTargetDir) {
                Log::record('remove old temp files');
                if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                    Log::record('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
                    die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
                }
                while (($file = readdir($dir)) !== false) {
                    Log::error('readdir file path => '.$file);
                    $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
                    //$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR;
                    Log::error('tmp file path => '.$tmpfilePath);
                    // If temp file is current file proceed to the next
                    if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                        Log::record('If temp file is current file proceed to the next');
                        continue;
                    }
                    // Remove temp file if it is older than the max age and is not the current file
                    if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                        Log::record('Remove temp file if it is older than the max age and is not the current file');
                        @unlink($tmpfilePath);
                    }
                }
                closedir($dir);
            }
            // Open temp file
            if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
                Log::record('open temp file');
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
//            $writ_point = 0;
            if (!empty($_FILES)) {
                Log::record('_files is true::'. $_FILES["file"]["size"].$_FILES['file']['name']. $_FILES["file"]["error"]);
                $_SESSION['file_name'] = $_FILES['file']['name'];
                if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                    Log::record('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
                    die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
                }
                // Read binary input stream and append it to temp file
                Log::record('Read binary input stream and append it to temp file'.$_FILES["file"]["tmp_name"]);
                if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                    Log::record('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                }
            } else {
                Log::record('php://input is true');
                if (!$in = @fopen("php://input", "rb")) {
                    Log::record('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                }
//                $writ_point = strlen(file_get_contents("php://input")) - $_SESSION['chunk_size'];
//                $writ_point = $writ_point > 0? $writ_point: 0;
                Log::record('file size:'.'ppppppp');

            }
            Log::record('write file data '.$_SESSION['chunk_size']);
//            fseek($in, $writ_point);
            Log::record("{$filePath}_{$chunk}:");
            while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
            }
            @fclose($out);
            @fclose($in);
            Log::record('rename for write file');
            rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
            $index = 0;
            $done = true; //判断上传分片是否成功
            Log::record('判断上传分片是否成功');
            for( $index = 0; $index < $chunks; $index++ ) {
                Log::record('file name::'."{$filePath}_{$index}.part");
                if ( !file_exists("{$filePath}_{$index}.part") ) {
                    $done = false;
                    Log::record('判断上传分片失败');
                    break;
                }
            }
            if ( $done ) {
                Log::record('判断上传分片成功');
                if (!$out = @fopen($uploadPath, "wb")) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
                }
                if ( flock($out, LOCK_EX) ) {
                    Log::record('锁定文件，写入');
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
                    Log::record('解锁');
                }
                @fclose($out);
            }
            // Return Success JSON-RPC response
            Log::record('Return Success JSON-RPC response');
            die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
        }
        if ($_POST['status'] == 'md5Check'){ //文件校验，避免重复上传，秒传
            Log::record('post status:: md5Check');
            //todo 查询数据库，获取文件名和md5校验名
            $dataArr = array('b0201e4d41b2eeefc7d3d355a44c6f5a' => 'kazaff2.jpg');
            //todo 对比上传文件是否已经上传过
            if(isset($dataArr[$_POST['md5']])){
                die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
//                return json(['ifExist'=>1, 'path'=>$dataArr[$_POST['md5']]]);
            } else {
                die('{"exist": 0}');
//                return json(['ifExist'=>0]);
            }
        } elseif($_POST['status'] == 'chunkCheck'){ //分片校验，用于断点续传，验证指定分块是否已经存在，避免重复上传
            Log::record('post status:: chunkCheck');
            $target =  $uploadDir.DS.$_POST['name'].'_'.$_POST['chunkIndex'];
            $_SESSION['file_length_from_header'] = isset($_SERVER['content-length'])? intval($_SERVER['content-length']):0;
            $_SESSION['chunk_size'] = $_POST['size'];
            Log::record('file length from header:'.$_SESSION['file_length_from_header'].'  chunk size:'.$_SESSION['chunk_size']);
            if(file_exists($target) && filesize($target) == $_POST['size']){
                Log::record('chunkCheck:: file is exited, need not upload');
                die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
//                return json(['ifExist' => 1]);
            } else {
                Log::record('chunkCheck:: file is not exited, this is new chunk and need upload');
                $_SESSION['chunkName'] = $_REQUEST["name"].'_'.$_REQUEST['chunkIndex'];
                die('{"exist": 0}');
//                return json(['ifExist' => 0]);
            }
        } elseif ($_POST['status'] == 'chunksMerge'){//合并分片
            Log::record('post status:: chunksMerge');
            //todo 合并分片
            $file_name = $_SESSION['file_name'];
            $file_tmp_name = $_REQUEST['name'];
            $file_md5_name = $_REQUEST['md5'];
            $file_ext = $_REQUEST['ext'];
            $file_chunks = $_REQUEST['chunks'];
            $uploadPath = $uploadDir.DS.$file_md5_name.'.'.$file_ext;
            $uploadedFiles = $uploadDir.DS.$file_tmp_name;
            Log::record('file name:'.$file_name.'::file tmp name:'.$file_tmp_name.'::file md5 name:'.$file_md5_name.'::file ext:'.$file_ext);
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
            if ( flock($out, LOCK_EX) ) {
                Log::record('锁定文件，写入');
                for( $index = 0; $index < $file_chunks; $index++ ) {
                    if (!$in = @fopen("{$uploadedFiles}_{$index}", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$uploadedFiles}_{$index}");
                }
                flock($out, LOCK_UN);
                Log::record('解锁');

                $vm = new VideoManagement();
                $insert_video_path = 'uploads'.DS.'video'.DS.$file_md5_name.'.'.$file_ext;
                $insert_video_path = str_replace('\\', '/', $insert_video_path);
                $insert_thumbnail_path = 'uploads'.DS.'video'.DS.'thumbnail'.DS.$file_md5_name.'.png';
                $insert_thumbnail_path = str_replace('\\', '/', $insert_thumbnail_path);
                $insert_video_name = substr($file_name, 0, (strlen($file_name)-strlen($file_ext)-1));

                $video_file = $uploadDir.DS.$file_md5_name.'.'.$file_ext;
                exec("ffmpeg -i $video_file 2>&1", $cmd_output_2, $cmd_return_var_2);
                if (is_array($cmd_output_2)) {
                    foreach ($cmd_output_2 as $v) {
                        if (strpos($v, 'Duration') !== false) {
                            $times = substr($v, stripos($v, '.') - 8, 8);//'  Duration: 00:24:28.14, start: 0.000000, bitrate: 486 kb/s'
                            break;
                        }
                    }
                    Log::error('执行读取视频时长的命令：cmd_output_2 is ' . $times);
                }

                if($vm->insertNewVideo($insert_video_path, $_POST['detail'], $insert_video_name, $times, $insert_thumbnail_path)){
                    Log::error('开始保存缩略图');
                    $video_file = $uploadDir.DS.$file_md5_name.'.'.$file_ext;
                    $video_jpeg = $uploadDir.DS.'thumbnail'.DS.$file_md5_name.'.png';
                    $ffmpeg_cmd = "ffmpeg -i $video_file -y -f mjpeg -ss 3 -t 2 -s 200*200 $video_jpeg";
                    exec($ffmpeg_cmd,$cmd_output,$cmd_return_var);

                    //$vtime = exec("ffmpeg -i ".$video_file." 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");//Linux下总长度

                    die('{"flag":"S", "msg":"视频上传成功"}');
                } else {
                    die('{"flag": "F", "msg":"视频上传失败"}');
                }
            } else {
                die('{"flag": "F", "msg":"视频上传失败"}');
            }
            @fclose($out);
            session_destroy();
        }
    }
}