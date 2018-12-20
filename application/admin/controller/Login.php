<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/12
 * Time: 15:00
 */

namespace app\admin\controller;

use app\api\model\AdminUser;
use think\Controller;
use think\Request;
use think\Session;

class Login extends Controller
{
    // 渲染登陆界面
    public function login(){
        Session::delete('admin_username');
        $this->assign('html_title', '党建管理平台|登录');
        return $this->view->fetch();
    }
    // 登陆验证
    public function check_in(Request $request){
        $flag = 0;
        $result = null;
        $data = $request->param();

        // 进行验证
        $rule =   [
            'username'  =>  'require|max:20',
            'password'  =>  'require|length:6,16|regex:[A-Za-z0-9_.@-]*',
            'verify_code'   =>  'require|captcha',
        ];

        $message  =   [
            'username.require'  =>  '用户名不能为空',
            'username.max'  =>  '用户名最多不能超过20个字符',
            'password.require'  =>  '密码不能为空',
            'password.length'   =>  '密码长度应该在6-16个字符之间',
            'password.regex'  =>  '密码只能由字母、数字、_ . @ -组成',
            'verify_code.require'   =>  '验证码不能为空',
            'verify_code.captcha'   =>  '验证码错误'
        ];
        $result = $this->validate($data, $rule, $message);

        // 数据库验证
        if($result === true){
            $admin = new AdminUser();
            $res = $admin->loginValidate($data);
            if ($res){
                $flag = 1;
                $result = '验证通过，点击[OK]进入主页';
                Session::set('admin_username', $data['username']);
            } else {
                $result = '验证失败，请核对账号密码是否正确';
            }
        }

        return json([
            'flag'=>$flag,
            'data'=>$result,
            'param'=>$data
        ]);
    }
    // 退出登陆
    public function check_out(){

    }

}