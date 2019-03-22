<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/12/20
 * Time: 19:54
 */

namespace app\admin\controller;


use app\api\model\AdminUser;

class AdminManagement extends Base
{
    public function index(){
        if(session('admin_authority')==0){
            $admin = new AdminUser();
            $adminList = $admin->getAllAdmin();
            $this->view->assign('adminlist', $adminList);
            return $this->view->fetch('adminm');
        } else {
            $this->view->assign('name', session('admin_username'));
            return $this->view->fetch('admin_info');
        }
    }
}