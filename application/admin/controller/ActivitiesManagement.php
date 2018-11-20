<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/7
 * Time: 21:46
 */

namespace app\admin\controller;


use app\api\model\Activities;
use think\Exception;

class ActivitiesManagement extends Base
{
    public function index(){
        $activities = new Activities();
        $res = $activities->getAllActivities();
        $this->view->assign('activities', $res);
        $this->view->assign('content_header', '专题活动管理');
        $this->view->assign('active5', 'active');
        return $this->view->fetch('thematic_activities');
    }
}