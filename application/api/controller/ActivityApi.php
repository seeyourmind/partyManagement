<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/29
 * Time: 21:58
 */

namespace app\api\controller;


use app\api\model\Activities;
use think\Controller;
use think\Exception;
use think\Request;

class ActivityApi extends Controller
{
    /**
     * 获取某ID对应的活动详情
     * @param Request $request
     * @return \think\response\Json
     */
    public function getActivityWithId(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $acid = $request->post('acid');
            if(!$acid){
                return json([
                    'flag' => 'F',
                    'msg' => '获取参数有误'
                ]);
            } else {
                try{
                    $ac = new Activities();
                    $res = $ac->getActivityWithID($acid);
                    if($res){
                        return json([
                            'flag' => 'S',
                            'msg' => $res[0]
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '未获取到任何数据'
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
                'msg' => '您的访问方式有误，请使用POST方式访问服务器！'
            ]);
        }
    }

    /**
     * 更新活动内容
     * @param Request $request
     * @return \think\response\Json
     */
    public function updateActivity(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $new_data = $request->post('data/a');
            if(!$new_data){
                return json([
                    'flag' => 'F',
                    'msg' => '获取参数有误'
                ]);
            } else {
                try{
                    $ac = new Activities();
                    $res = $ac->updateActivity($new_data);
                    if($res){
                        return json([
                            'flag' => 'S',
                            'msg' => '操作成功'
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '更新失败'
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
                'msg' => '您的访问方式有误，请使用POST方式访问服务器！'
            ]);
        }
    }

    /**
     * 删除活动
     * @param Request $request
     * @return \think\response\Json
     */
    public function deleteActivityByID(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $acids = $request->post('acids/a');
            if(!$acids){
                return json([
                    'flag' => 'F',
                    'msg' => '获取参数有误'
                ]);
            } else {
                try{
                    $ac = new Activities();
                    $res = $ac->deleteActivityBYIDS($acids);
                    if($res){
                        return json([
                            'flag' => 'S',
                            'msg' => '操作成功'
                        ]);
                    } elseif($res == 0){
                        return json([
                            'flag' => 'F',
                            'msg' => '指定活动不存在'
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '删除活动失败'
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
                'msg' => '您的访问方式有误，请使用POST方式访问服务器！'
            ]);
        }
    }

    /**
     * 新建活动
     * @param Request $request
     * @return \think\response\Json
     */
    public function insertNewActivity(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $ndata = $request->post('data/a');
            if(!$ndata){
                return json([
                    'flag' => 'F',
                    'msg' => '获取参数有误'
                ]);
            } else {
                try{
                    $ac = new Activities();
                    $res = $ac->insertActivity($ndata);
                    if($res){
                        return json([
                            'flag' => 'S',
                            'msg' => '操作成功'
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '新建活动失败'
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
                'msg' => '您的访问方式有误，请使用POST方式访问服务器！'
            ]);
        }
    }
}