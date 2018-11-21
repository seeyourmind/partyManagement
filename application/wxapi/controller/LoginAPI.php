<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/20
 * Time: 13:54
 */

namespace app\wxapi\controller;


use app\api\model\Userinfo;
use app\extra\AES;
use think\Controller;
use think\Exception;
use think\Request;

class LoginAPI extends Controller
{
    /**
     * js_code换取WeChatID
     * @param Request $request
     * @return \think\response\Json
     */
    public function get_wechatid_by_jscode(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $jscode = $request->post('js_code');
            $userinfo = new Userinfo();

            if(strlen($jscode) > 5){
                // 设置请求头
                $headerArray = array("Content-type:application/json;","Accept:application/json");
                // 请求地址
                $url = WXCHARTID_URL.'appid='.APPID.'&secret='.APPSECRET.'&js_code='.$jscode.'&grant_type='.GRANT_TYPE;
                // 初始化
                $ch = curl_init();
                // 设置选项
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
                // 执行
                $output = curl_exec($ch);
                // 关闭
                curl_close($ch);
                // json输出
                $output_a = json_decode($output, true);
                if(array_key_exists('openid', $output_a)){
                    $aeser = new AES(AES_KEY);
                    $wechatid = $aeser->encode($output_a['openid']);
                    try{
                        $res = $userinfo->getUserinfoByWechatid($wechatid);
                        if($res){
                            return json([
                                'flag' => 'S',
                                'msg' => array('wechatId'=>$wechatid, 'isUserExisted'=>true, 'userInfo'=>$res)
                            ]);
                        } else {
                            return json([
                                'flag' => 'S',
                                'msg' => array('wechatId'=>$wechatid, 'isUserExisted'=>false)
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
                } else {
                    return json([
                        'flag' => 'F',
                        'msg' => json_decode($output)
                    ]);
                }
            } else {
                return json([
                    'flag' => 'F',
                    'msg' => '未获取到js_code'
                ]);
            }
        } else {
            return json([
                'flag' => 'F',
                'msg' => '您使用的请求方式无效，请使用POST方式请求服务器'
            ]);
        }
    }

    /**
     * 验证注册信息，根据提交信息绑定WeChatID
     * @param Request $request
     * @return \think\response\Json
     */
    public function use_wechatid_register(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $wechat_id = $request->post('wechatId');
            $job_number = $request->post('jobNumber');
            $name = $request->post('name');
            $sex = $request->post('sex');
            $pattern_job_number = "#^[0-9]*$#";
            $pattern_sex = "#^[男|女]{1}#";
            $userinfo = new Userinfo();

            if(strlen($wechat_id) && preg_match($pattern_job_number, $job_number) && strlen($name) && preg_match($pattern_sex, $sex)){
                try{
                    if($userinfo->verifyRegistrationWechatid($wechat_id, $job_number, $name, $sex)){
                        return json([
                            'flag' => 'S',
                            'msg' => array('isBind'=>true)
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '验证失败，未匹配到该用户'
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
            } else {
                return json([
                    'flag' => 'F',
                    'msg' => '填写信息有误'
                ]);
            }
        } else {
            return json([
                'flag' => 'F',
                'msg' => '您使用的请求方式无效，请使用POST方式请求服务器'
            ]);
        }
    }

    /**
     * 获取用户，根据WeChatID
     * @param Request $request
     * @return \think\response\Json
     */
    public function get_userinfo_by_wechatid(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $wechat_id = $request->post('wechatId');
            $userinfo = new Userinfo();
            if(strlen($wechat_id)){
                try{
                    $res = $userinfo->getUserinfoByWechatid($wechat_id);
                    if($res){
                        return json([
                            'flag' => 'S',
                            'msg' => array('userInfo'=>$res[0])
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '未获取任何用户信息'
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
            } else {
                return json([
                    'flag' => 'F',
                    'msg' => '填写信息有误'
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