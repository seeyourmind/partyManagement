<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/11
 * Time: 20:45
 */

namespace app\admin\controller;


use think\Controller;

class Base extends Controller
{
    public function _initialize()
    {
        //判断有无admin_username这个session，如果没有，跳转到登陆界面
        if(!session('admin_username')){
            return $this->error('您没有登陆',url('/admin/login'));
        } else {
            $this->view->assign('username', session('admin_username'));
        }
    }
}