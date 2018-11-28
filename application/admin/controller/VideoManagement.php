<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/24
 * Time: 16:37
 */

namespace app\admin\controller;


use think\Controller;

class VideoManagement extends Controller
{
    public function index(){
        return $this->view->fetch('video_management');
    }
}