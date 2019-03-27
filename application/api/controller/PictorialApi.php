<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2019/3/27
 * Time: 16:37
 */

namespace app\api\controller;


use app\api\model\Pictorial;
use think\Controller;
use think\Exception;
use think\Request;

class PictorialApi extends Controller
{
    /**
     * 上传画报
     * @param Request $request
     * @return \think\response\Json
     */
    public function uploadNewPictorial(Request $request){
        if($request->isPost()){
            $image = $request->file('upload');

            if(!empty($image)){
                $info = $image->move((ROOT_PATH.'public'.DS.'uploads'.DS.'pictorial'));
                if($info){
                    $filePath = $info->getSaveName();
                    $filePath = str_replace('\\', '/', $filePath);
                    $msg = $filePath;
                    try{
                        $pimg = new Pictorial();
                        $res = $pimg->uploadNewOne('/uploads/pictorial/'.$msg);
                        if($res){
                            return json([
                                'flag' => 'S',
                                'msg' => '宣传画报上传成功'
                            ]);
                        } else {
                            return json([
                                'flag' => 'F',
                                'msg' => '画报上传失败'
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
                        'msg' => '画报上传失败'
                    ]);
                }
            } else {
                return json([
                    'msg' => '未获取到上传的图像数据',
                    'flag' => 'F'
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
     * 删除旧画报
     * @param Request $request
     * @return \think\response\Json
     */
    public function deleteOldPictorial(Request $request){
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
                    $pimg = new Pictorial();
                    $res = $pimg->deleteOnePictorial($vid);
                    if($res){
                        return json([
                            'flag' => 'S',
                            'msg' => '操作成功'
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '删除画报失败'
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