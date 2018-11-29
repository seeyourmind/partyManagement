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
    public function video(){
        Log::record('video get ');
    }

    public function images(){
        Log::record('images get ');
    }

    public function excel(){
        Log::record('excel get ');
    }

    private function newSymlink($path){
        $manual = $path;  // 原路径

        $manualLink = './uploadSymlink/'.date('Y-m-d H:i:s');   // 软连接路径

        $isExistFile = true;    // 原文件是否存在的标识

        if(is_file($manual) && !is_file($manualLink)){  // 原文件存在且软连接不存在时，创建软连接
            symlink($manual, $manualLink);              // 创建软连接
        }

        if(!is_file($manualLink))  {
            $isExistFile = false;
        }elseif(!is_file($manual)){ // 原文件不存在时
            $isExistFile = false;
        }
        return array('isExistFile'=>$isExistFile,'manual'=>$manualLink);
    }
}