<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2019/3/16
 * Time: 15:25
 */

namespace app\wxapi\controller;


use app\api\model\Article;
use think\Controller;
use think\Exception;
use think\Request;

class ArticleView extends Controller
{
    public function news(Request $request){
        is_null($request) && $request;
        if($request->isGet()){
            $id = $request->get('id');
            if($id){
                $article = new Article();
                try{
                    $res = $article->getContentWithArticleId($id);
                    if($res){
                        $title = $res[0]['title'];
                        $content = $res[0]['content'];
                        $this->view->assign('title', $title);
                        $this->view->assign('content', $content);
                        return $this->view->fetch('article');
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '未读取到指定ID的新闻内容'
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
                    'msg' => '服务器未读取到指定的新闻ID'
                ]);
            }
        } else {
            return json([
                'flag' => 'F',
                'msg' => '您使用的请求方式无效，请使用POST方式请求服务器'
            ]);
        }
    }
    public function messages(Request $request){
        is_null($request) && $request;
        if($request->isGet()){
            $id = $request->get('id');
            if($id){
                $article = new Article();
                try{
                    $res = $article->getContentWithArticleId($id);
                    if($res){
                        $title = $res[0]['title'];
                        $content = $res[0]['content'];
                        $this->view->assign('title', $title);
                        $this->view->assign('content', $content);
                        return $this->view->fetch('article');
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '未读取到指定ID的新闻内容'
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
                    'msg' => '服务器未读取到指定的新闻ID'
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