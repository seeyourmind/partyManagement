<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/12
 * Time: 15:00
 */

namespace app\admin\controller;

use think\Request;

class Login extends Base
{
    // 渲染登陆界面
    public function login(){
        $this->assign('html_title', '党建管理平台|登录');
        return $this->view->fetch();
    }
    // 登陆验证
    public function check_in(Request $request){
        $flag = 0;
        $result = null;
        $data = $request->param();

        // 创建验证规则
        $rule = [
            'username|用户名' => 'require',//必填
            'password|密码' => 'require',
            'verify_code|验证码' => 'require|captcha',//必填|验证
        ];

        // 进行验证
        $result = $this->validate($data, $rule);

        // 数据库验证
        if($result === true){
            $flag = 1;
            $result = '验证通过，点击[确定]进入主页';
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