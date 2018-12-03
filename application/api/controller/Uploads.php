<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/29
 * Time: 11:49
 */

namespace app\api\controller;


use think\Controller;
use think\Log;

class Uploads extends Controller
{
    public function _initialize()
    {
        // TODO: Change the autogenerated stub
        header("content-type: image/jpeg");
    }

    public function video(){
        header("content-type: video/mp4");
        $request_url = $this->curPageURL();
        $url_array = explode('/', $request_url);
        $file_name = $url_array[sizeof($url_array) -1];

        $manual = ROOT_PATH.'uploads'.DS.'video'.DS.$file_name;
        $manualLink = ROOT_PATH.'public'.DS.'uploads_symlink'.DS.md5('video:'.$file_name);
        readfile($manual);
    }

    public function images(){
        header("content-type: image/jpeg");

        $request_url = $this->curPageURL();
        $url_array = explode('/', $request_url);
        $file_name = $url_array[sizeof($url_array)-2].DS.$url_array[sizeof($url_array) -1];
        $path = ROOT_PATH.'uploads'.DS.'images'.DS.$file_name;
        readfile($path, filesize($path));
    }

    public function excel(){
        Log::record('excel get ');
    }

    private function newSymlink($manual, $manualLink){
        $isExistFile = true;    // 原文件是否存在的标识

        if(is_file($manual) && !is_file($manualLink)){  // 原文件存在且软连接不存在时，创建软连接
             $out = exec("mklink $manualLink $manual");              // 创建软连接
        }

        if(!is_file($manualLink))  {
            $isExistFile = false;
        }elseif(!is_file($manual)){ // 原文件不存在时
            $isExistFile = false;
        }
        return array('isExistFile'=>$isExistFile,'manual'=>$manualLink);
    }

    private function curPageURL()
    {
        $pageURL = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on")
        {
            $pageURL .= "s";
        }
        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        }
        else
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}