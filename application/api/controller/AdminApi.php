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
    /**
     * 新建管理员
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
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

    /**
     * 获取管理员个人信息
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOneAdminInfo(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $name = $request->post('name');
            $admin = new AdminUser();
            if($name){
                try{
                    $res = $admin->getAdminInfoByName($name);
                    if($res){
                        return json([
                            'flag'=>'S',
                            'msg'=>$res[0]
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

    /**
     * 删除管理员信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function deleteAdmin(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $id = $request->post('id');
            $admin = new AdminUser();
            if($id){
                try{
                    $res = $admin->deleteAdminUser($id);
                    if($res){
                        return json([
                            'flag'=>'S',
                            'msg'=>'该管理员已被删除'
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

    /**
     * 更新管理员权限
     * @param Request $request
     * @return \think\response\Json
     */
    public function authorizationAdmin(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $id = $request->post('id');
            $authority = $request->post('authority');
            $admin = new AdminUser();
            if($id && strlen($authority)){
                try{
                    $res = $admin->authorization($id, $authority);
                    if($res){
                        return json([
                            'flag'=>'S',
                            'msg'=>'该管理员权限已经更新'
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

    /**
     * 修改密码
     * @param Request $request
     * @return \think\response\Json
     */
    public function changePassword(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $name = $request->post('name');
            $password = $request->post('password');
            $admin = new AdminUser();
            if($name && strlen($password)){
                try{
                    $res = $admin->changePassword($name, $password);
                    if($res){
                        return json([
                            'flag'=>'S',
                            'msg'=>'您的密码已经修改成功'
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