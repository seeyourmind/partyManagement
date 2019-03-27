<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2019/3/16
 * Time: 16:19
 */

namespace app\wxapi\controller;


use app\api\model\VideoManagement;
use think\Controller;
use think\Exception;
use think\Request;

class VideoAPI extends Controller
{
    /**
     * 获取视频分类
     * @param Request $request
     * @return Request|\think\response\Json
     */
    public function get_category(Request $request) {
        is_null($request) && $request;
        if($request->isGet()){
            $video = new VideoManagement();
            try{
                $res = $video->getCategoryForWX();
                if($res){
                    return json([
                        'flag' => 'S',
                        'msg' => $res
                    ]);
                } else {
                    return json([
                        'flag' => 'F',
                        'msg' => '暂无视频'
                    ]);
                }
            } catch (Exception $e){
                return json([
                    'flag'  =>  'F',
                    'code'  =>  $e->getCode(),
                    'msg'   =>  '获取文章内容时出错，请稍候重试',
                    'file'  =>  $e->getFile(),
                    'line'  =>  $e->getLine()
                ]);
            }
        } else {
            return json([
                'flag' => 'F',
                'msg' => '您使用的请求方式无效，请使用GET方式请求服务器'
            ]);
        }
        return $this->request;
    }

    /**
     * 按分类获取视频列表
     * @param Request $request
     * @return \think\response\Json
     */
    public function get_video_list_by_category(Request $request) {
        is_null($request) && $request;
        if($request->isPost()){
            $category = $request->post('category');
            if($category){
                $video = new VideoManagement();
                try{
                    $res = $video->getVideoByCategoryForWX($category);
                    if($res){
                        return json([
                            'flag' => 'S',
                            'msg' => $res
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'category' => $res,
                            'msg' => '该分类下暂无视频'
                        ]);
                    }
                } catch (Exception $e){
                    return json([
                        'flag'  =>  'F',
                        'code'  =>  $e->getCode(),
                        'msg'   =>  '获取文章内容时出错，请稍候重试',
                        'file'  =>  $e->getFile(),
                        'line'  =>  $e->getLine()
                    ]);
                }
            } else {
                return json([
                    'flag' => 'F',
                    'msg' => '服务器未读取到指定的参数'
                ]);
            }
        } else {
            return json([
                'flag' => 'F',
                'msg' => '您使用的请求方式无效，请使用POST方式请求服务器'
            ]);
        }
    }
}