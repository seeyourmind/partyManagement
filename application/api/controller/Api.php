<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/5
 * Time: 10:14
 */

namespace app\api\controller;

use app\api\model\Userinfo;
use think\Controller;
use think\Exception;
use think\Request;

class Api extends Controller
{
    /**
     * 添加新用户数据
     * @param Request $request
     * @return string|\think\response\Json
     * @throws \Exception
     */
    public function insertUserinfo(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $datas = $request->post();
            $data = $datas['data'];
            $user = new Userinfo();
            try{
                $result = $user->insertUser($data);
            }catch (Exception $e){
                return json([
                    'flag'=>'F',
                    'code' => $e->getCode(),
                    'msg'  => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'   => $e->getLine()
                ]);
            }

            return json([
                'flag'=>'S',
                'data'=>$result
            ]);
        } else {
            return 'please use post';
        }
    }

    /**
     * 依据用户ID删除用户信息
     * @param Request $request
     * @return string|\think\response\Json
     */
    public function deleteUserWithID(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $post_params = $request->post();
            $ids = $post_params['id'];
            $user = new Userinfo();
            $delete_sum = 0;
            foreach ($ids as $id){
                try{
                    $result = $user->deleteUserWithID($id);
                }catch (Exception $e){
                    return json([
                        'flag'=>'F',
                        'code' => $e->getCode(),
                        'msg'  => $e->getMessage(),
                        'file'    => $e->getFile(),
                        'line'   => $e->getLine()
                    ]);
                }
                $delete_sum += $result;
            }
            return json([
                'flag'=>'S',
                'data'=>$delete_sum,
            ]);
        } else {
            return 'please use post';
        }
    }
    /**
     * 获取用户信息列表
     * @param Request $request
     * @return \think\response\Json
     */
    public function getAllUser(Request $request){

        is_null($request) && $request;
        if ($request->isGet()) {
            $user = new Userinfo();
            try{
                $user_list = $user->getUserinfo();
            }catch (Exception $e){
                return json([
                    'flag'=>'F',
                    'code' => $e->getCode(),
                    'msg'  => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'   => $e->getLine()
                ]);
            }

            encryptOpenid($user_list);

            $result_len = sizeof($user_list);
            if ($result_len <= 0){
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>'为获取到任何用户信息'
                ]);
            } else {
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>$user_list
                ]);
            }
        }
    }
    /**
     * 根据ID获取用户的个人信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function getUserWithID(Request $request){
        is_null($request) && $request;
        if ($request->isGet()) {
            $user_id = $request->get('id');
            $user = new Userinfo();

            try{
                $user_info = $user->getUserinfoWithID($user_id);
            }catch (Exception $e){
                return json([
                    'flag'=>'F',
                    'code' => $e->getCode(),
                    'msg'  => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'   => $e->getLine()
                ]);
            }
            encryptOpenid($user_info);
            $result_len = sizeof($user_info);

            if ($result_len <= 0){
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>'为获取到任何用户信息'
                ]);
            } else {
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>$user_info[$result_len-1]
                ]);
            }
        }

        if ($request->isAjax()){
            $user_id = $_POST['id'];
            $user = new Userinfo();

            try{
                $user_info = $user->getUserinfoWithID($user_id);
            }catch (Exception $e){
                return json([
                    'flag' => 'F',
                    'code' => $e->getCode(),
                    'msg'  => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'   => $e->getLine()
                ]);
            }

            return json([
                'way' => 'ajax',
                'flag'=>'S',
                'length'=>sizeof($user_info),
                'data'=>$user_info
            ]);
        }
    }
    /**
     * 根据姓名获取用户个人信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function getUserWithName(Request $request){
        is_null($request) && $request;
        if ($request->isGet()){
            $user_name = $request->get('name');
            $user = new Userinfo();
            try{
                $user_info = $user->getUserinfoWithName($user_name);
            }catch (Exception $e){
                return json([
                    'flag' => 'F',
                    'code' => $e->getCode(),
                    'msg'  => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'   => $e->getLine()
                ]);
            }
            encryptOpenid($user_info);
            $result_len = sizeof($user_info);

            if ($result_len <= 0){
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>'为获取到任何用户信息'
                ]);
            } else {
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>$user_info
                ]);
            }
        }

        if ($request->isAjax()) {
            $user_name = $_POST['name'];
            $user = new Userinfo();

            try {
                $user_info = $user->getUserinfoWithName($user_name);
            } catch (Exception $e) {
                return json([
                    'flag' => 'F',
                    'code' => $e->getCode(),
                    'msg' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }

            return json([
                'way' => 'ajax',
                'flag' => 'S',
                'length' => sizeof($user_info),
                'data' => $user_info
            ]);
        }
    }

    /**
     * 更新用户个人信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function updateUserInfo(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $datas = $request->post();
            $data = $datas['data'];
            $user  = new Userinfo();
            try{
                $result = 0;
                foreach ($data as $d){
                    $result += $user->updateUserInfo($d);
                }
            }catch (Exception $e){
                return json([
                    'flag'=>'F',
                    'code' => $e->getCode(),
                    'msg'  => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'   => $e->getLine()
                ]);
            }

            return json([
                'flag'=>'S',
                'data'=>$result
            ]);
        }
    }
}