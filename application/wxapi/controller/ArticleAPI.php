<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/22
 * Time: 21:28
 */

namespace app\wxapi\controller;


use app\api\model\Article;
use app\api\model\HotArticle;
use app\api\model\UserMessage;
use think\Exception;
use think\Request;

class ArticleAPI
{
    /**
     * 获取用户消息列表
     * @param Request $request
     * @return \think\response\Json
     */
    public function get_user_read_message_list(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $wechat_id = $request->post('wechatId');
            // 用户身份校验
            $uid = checkWechatId($wechat_id);
            if(!$uid){
                return json([
                    'flag' => 'F',
                    'msg' => '非法用户访问'
                ]);
            } else {
                try{
                    $user_message = new UserMessage();
                    $res = $user_message->getReadOrNotMessages($uid);
                    if ($res){
                        return json([
                            'flag' => 'S',
                            'msg' => $res
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '未获取该用户的消息列表'
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

    /**
     * 用户浏览文章
     * 1、为本篇文章浏览量+1
     * 2、如果文章是通知消息，则了关联到用户已读消息列表
     * @param Request $request
     * @return \think\response\Json
     */
    public function user_watch_article(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $wechat_id = $request->post('wechatId');
            $aid = $request->post('aid');

            // 用户身份校验
            $uid = checkWechatId($wechat_id);
            if(!$uid){
                return json([
                    'flag' => 'F',
                    'msg' => '非法用户访问'
                ]);
            } else {
                if(!$aid){
                    return json([
                        'flag' => 'F',
                        'msg' => '未获取到您阅读的文章编号'
                    ]);
                } else {
                    try{
                        $hot_article = new HotArticle();
                        $user_message = New UserMessage();
                        $res1 = $hot_article->updateWatchTime($aid);//增加文章浏览量
                        $res2 = $user_message->insertUserArticleRelation($uid, $aid);//建立用户-通知关系
                        switch ($res2){
                            case 0:
                                $flag = 'S';
                                $res2_msg = '不是消息，无需写入';
                                break;
                            case 1:
                                $flag = 'S';
                                $res2_msg = '已存在，无需写入';
                                break;
                            case 2:
                                $flag = 'S';
                                $res2_msg = '写入成功';
                                break;
                            case 3:
                                $flag = 'F';
                                $res2_msg = '写入失败';
                                break;
                            default:
                                $flag = 'F';
                                $res2_msg = '写入失败';
                                break;
                        }
                        return json([
                            'flag' => $flag,
                            'msg' => array(
                                'update' => $res1?true:false,
                                'insert' => $res2_msg
                            )
                        ]);
                    } catch (Exception $e){
                        return json([
                            'flag'  =>  'F',
                            'code'  =>  $e->getCode(),
                            'msg'   =>  $e->getMessage(),
                            'file'  =>  $e->getFile(),
                            'line'  =>  $e->getLine(),
                        ]);
                    }
                }
            }
        } else {
            return json([
                'flag' => 'F',
                'msg' => '您使用的请求方式无效，请使用POST方式请求服务器'
            ]);
        }
    }

    /**
     * 获取用户未读消息数目
     * @param Request $request
     * @return \think\response\Json
     */
    public function get_user_not_read_message_count(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $wechat_id = $request->post('wechatId');

            // 用户身份校验
            $uid = checkWechatId($wechat_id);
            if(!$uid){
                return json([
                    'flag' => 'F',
                    'msg' => '非法用户访问'
                ]);
            } else {
                try{
                    $user_message = new UserMessage();
                    $res = $user_message->getNotReadMessagesCount($uid);
                    if($res){
                        return json([
                            'flag' => 'S',
                            'msg' => $res[0]['count_']
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '未访问到任何数据'
                        ]);
                    }
                } catch (Exception $e){
                    return json([
                        'flag'  =>  'F',
                        'code'  =>  $e->getCode(),
                        'msg'   =>  $e->getMessage(),
                        'file'  =>  $e->getFile(),
                        'line'  =>  $e->getLine(),
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

    /**
     * 获取最新三则文章
     * @param Request $request
     * @return \think\response\Json
     */
    public function get_latest_three_article(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $wechat_id = $request->post('wechatId');
            $category = $request->post('category');

            //用户身份验证
            $uid = checkWechatId($wechat_id);
            if(!$uid || !$category){
                return json([
                    'flag' => 'F',
                    'msg' => '非法用户访问'
                ]);
            } else {
                try{
                    $article = new Article();
                    $category = $category == 1? '通知公告': '时事新闻';
                    $res = $article->getLatestThressArticleWithCategory($category, $uid);
                    return json([
                        'flag' => 'S',
                        'msg' => $res
                    ]);
                } catch (Exception $e){
                    return json([
                        'flag'  =>  'F',
                        'code'  =>  $e->getCode(),
                        'msg'   =>  $e->getMessage(),
                        'file'  =>  $e->getFile(),
                        'line'  =>  $e->getLine(),
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