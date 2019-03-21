<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2019/3/21
 * Time: 19:34
 */

namespace app\api\controller;


use app\api\model\AdminUser;
use think\Controller;
use think\Request;

class AdminApi extends Controller
{
    public function newAdmin(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $addData = $request->post('data/a');
            $admin = new AdminUser();
            if($addData){
                if($admin->userIsExist($addData['username'])){
                    return json([
                        'flag' => 'F',
                        'msg' => '用户名已存在'
                    ]);
                } else {
                    try{
                        $res = $admin->insertAdminUser($addData);
                        if($res){
                            return json([
                                'flag'=>'S',
                                'msg'=>'添加数据成功',
                                'data'=>$res,
                            ]);
                        }
                    } catch (Exception $e){
                        return json([
                            'flag'=>'F',
                            'code' => $e->getCode(),
                            'msg'  => $e->getMessage(),
                            'file'    => $e->getFile(),
                            'line'   => $e->getLine()
                        ]);
                    }
                }
            } else {
                return json([
                    'flag' => 'F',
                    'msg' => '未获取到指定参数',
                ]);
            }
        } else {
            return json([
                'flag' => 'F',
                'msg' => '您的访问方式有误，请使用POST方式访问服务器！'
            ]);
        }
    }
}