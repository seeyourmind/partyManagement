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

class VideoApi extends Controller
{

    public function upload_video(Request $request)
    {
        is_null($request) && $request;
        if($request->isPost()){

        }
    }

}