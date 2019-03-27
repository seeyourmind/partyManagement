<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2019/3/27
 * Time: 17:44
 */

namespace app\wxapi\controller;


use app\api\model\Pictorial;
use think\Controller;
use think\Request;

class PictorialAPI extends Controller
{
    /**
     * 获取画报列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_pictorial_img(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $wechat_id = $request->post('wechatId');

            if(!checkWechatId($wechat_id)){
                return json([
                    'flag' => 'F',
                    'msg' => '非法用户访问'
                ]);
            } else {
                try{
                    $pic = new Pictorial();
                    $res = $pic->field('url')->select();
                    if ($res){
                        return json([
                            'flag' => 'S',
                            'msg' => $res
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '未获取到任何画报数据'
                        ]);
                    }
                } catch (Exception $e){
                    return json([
                        'flag'  =>  'F',
                        'code'  =>  $e->getCode(),
                        'msg'   =>  $e->getMessage(),
                        'file'  =>  $e->getFile(),
                        'line'  =>  $e->getLine()
                    ]);
                }
            }
        } else {
            return json([
                'flag' => 'F',
                'msg' => '您使用的请求方式无效，请使用POST方式请求服务器'
            ]);
        }
    }

}