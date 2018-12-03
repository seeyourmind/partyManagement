<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/24
 * Time: 16:37
 */

namespace app\admin\controller;

use think\Exception;
use think\Request;

class VideoManagement extends Base
{
    public function index(){
        try{
            $vm = new \app\api\model\VideoManagement();
            $res = $vm->getAllVideo();
            $this->view->assign('video_list', $res);
        } catch (Exception $e){
            throw new \think\exception\HttpException(404, '页面不存在');
        }
        $this->view->assign('hide', $res->total()>0?'hidden':'');
        $this->view->assign('content_header', '视频管理');
        $this->view->assign('active4', 'active');

        return $this->view->fetch('video_management');
    }

    public function searchVideoBykw(Request $request){
        is_null($request) && $request;
        if(isset($_GET['k'])){$kw = $_GET['k'];} else {$kw = '';}
        try{
            $vm = new \app\api\model\VideoManagement();
            $res = $vm->searcherVideoByKeywords($kw);
            $this->view->assign('video_list', $res);
        } catch (Exception $e){
            abort(404, '页面不存在');
        }
        $this->view->assign('hide', $res->total()>0?'hidden':'');
        $this->view->assign('content_header', '视频管理');
        $this->view->assign('active4', 'active');

        return $this->view->fetch('video_management');
    }
}