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
        $this->view->assign('content_header', '视频管理');
        $this->view->assign('active4', 'active');
        $this->view->assign('video_list', array([1],[2],[3],[4],[5],[6]));
        return $this->view->fetch('video_management');
    }
}