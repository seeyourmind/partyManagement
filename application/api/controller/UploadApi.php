<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/3
 * Time: 22:15
 */

namespace app\api\controller;


use think\Controller;
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
}