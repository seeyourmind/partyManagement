<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/24
 * Time: 20:03
 */

namespace app\api\controller;


use app\api\model\VideoManagement;
use think\Controller;
use think\Exception;
use think\Request;

class VideoApi extends Controller
{
    /**
     * 获取视频列表
     * @param Request $request
     * @return \think\response\Json
     */
    public function getAllVideos(Request $request)
    {
        is_null($request) && $request;
        if($request->isPost()){
            try{
                $vm = new VideoManagement();
                $res = $vm->getAllVideo();
                if($res){
                    return json([
                        'flag' => 'S',
                        'msg' => $res
                    ]);
                } else {
                    return json([
                        'flag' => 'F',
                        'msg' => '未获取到任何视频数据'
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
                'msg' => '您的访问方式有误，请使用POST方式访问服务器！'
            ]);
        }
    }

    /**
     * 获取某ID视频源
     * @param Request $request
     * @return \think\response\Json
     */
    public function getVideoByID(Request $request)
    {
        is_null($request) && $request;
        if($request->isPost()){
            $vid = $request->post('id');
            if(!$vid){
                return json([
                    'flag' => 'F',
                    'msg' => '请求参数有误'
                ]);
            } else {
                try{
                    $vm = new VideoManagement();
                    $res = $vm->getVideoByID($vid);
                    if($res){
                        return json([
                            'flag' => 'S',
                            'msg' => $res[0]
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '未获取到任何视频数据'
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
     * 删除某ID视频
     * @param Request $request
     * @return \think\response\Json
     */
    public function deleteVideoByID(Request $request)
    {
        is_null($request) && $request;
        if($request->isPost()){
            $vid = $request->post('id');
            if(!$vid){
                return json([
                    'flag' => 'F',
                    'msg' => '请求参数有误'
                ]);
            } else {
                try{
                    $vm = new VideoManagement();
                    $res = $vm->deleteVideoByID($vid);
                    if($res){
                        return json([
                            'flag' => 'S',
                            'msg' => '操作成功'
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '删除视频失败'
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

    public function searchVideoByKW(Request $request)
    {
        is_null($request) && $request;
        if($request->isPost()){
            $kw = $request->post('keywords');
            if(!$kw){
                return json([
                    'flag' => 'F',
                    'msg' => '请求参数有误'
                ]);
            } else {
                try{
                    $vm = new VideoManagement();
                    $res = $vm->searcherVideoByKeywords($kw);
                    if($res){
                        return json([
                            'flag' => 'S',
                            'msg' => $res
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '未获取到任何视频数据'
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