<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/12/20
 * Time: 19:54
 */

namespace app\admin\controller;


use think\Controller;

class AdminManagement extends Controller
{
    public function index(){
        return $this->view->fetch('adminm');
    }
}