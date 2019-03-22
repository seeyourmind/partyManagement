<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/9
 * Time: 15:02
 */

namespace app\admin\controller;

use app\api\model\AdminUser;
use app\api\model\Userinfo;
use think\Exception;
use think\Request;
use think\Session;

class UserManagement extends Base
{
    public function index(){
        $authority = session('admin_authority');
        if(!$authority){
            $admin = new AdminUser();
            $authority = $admin->getUserAuthority(session('admin_username'))[0]['authority'];
        }

        if($authority=='0' || stristr($authority,'1')!=false){
            $userinfo = new Userinfo();
            $user = [['name'=>'请选择需要查看的用户','stage'=>null,'id'=>null,'sex'=>null,'nation'=>null,'birthday'=>null]];

            $user = $userinfo->getUserinfo();
            $this->view->assign('users',$user);
            $this->view->assign('content_header', '党员信息管理');
            $this->view->assign('active1', 'active');
            return $this->view->fetch('user_management');
        } else {
            $this->view->assign('content_header', '党员信息管理');
            $this->view->assign('active1', 'active');
            return $this->view->fetch('/login/authority');
        }
    }

    public function searchUser(Request $request){
        is_null($request) && $request;
        $id = $_GET['id'];
        $name = $_GET['name'];
        $api = new Userinfo();

        try{
            if (!preg_match('/^[\d]+$/',$id)){
                $result = $api->getUserinfoWithName($name);
            } else {
                $result = $api->getUserinfoWithID($id);
            }
        } catch (Exception $e) {
            return json([
                'flag' => 'F',
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'   => $e->getLine()
            ]);
        }

        $this->view->assign('users', $result);
        $this->view->assign('content_header', '党员信息管理');
        $this->view->assign('active1', 'active');
        return $this->view->fetch('user_management');
    }
}