<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/12/20
 * Time: 19:54
 */

namespace app\admin\controller;


use app\api\model\AdminUser;
use think\Controller;

class AdminManagement extends Controller
{
    public function index(){
        $admin = new AdminUser();
        $adminList = $admin->getAllAdmin();
        $this->view->assign('adminlist', $adminList);
        return $this->view->fetch('adminm');
    }
}