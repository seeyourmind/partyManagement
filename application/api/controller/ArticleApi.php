<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/1
 * Time: 15:24
 */

namespace app\api\controller;


use app\api\model\Article;
use app\api\model\HotArticle;
use think\Controller;
use think\Exception;
use think\Request;

class ArticleApi extends Controller
{
    /**
     * 发布新文章
     * 1. insert into article
     * 2. insert into hot_article
     * @param Request $request
     * @return \think\response\Json
     * @throws \Exception
     */
    public function insertArticle(Request $request) {
        is_null($request) && $request;
        if($request->isPost()){
            $data = $request->post()['data'];
            $flag = 'F';
            $msg = '服务器未获取到数据';
            $article = new Article();
            $hot_article = new HotArticle();
            try{
                $res1 = $article->insterArticle($data);
                if(intval($res1)){
                    $res2 = $hot_article->insertHotArticle(array('aid'=>intval($res1)));
                    if($res2){
                        return json([
                            'flag' => 'S',
                            'msg' => '文章发布成功'
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '新建文章失败，请重新提交',
                            'res' => '2'
                        ]);
                    }
                } else {
                    return json([
                        'flag' => 'F',
                        'msg' => '新建文章失败，请重新提交',
                        'res' => 1
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
        }else{
            return json([
                'flag' => 'F',
                'msg' => '您的访问方式有误，请使用POST方式访问服务器！'
            ]);
        }
    }

    /**
     * 获取所有文章列表
     * @param Request $request
     * @return \think\response\Json
     */
    public function getAllArticles(Request $request){
        is_null($request) && $request;
        if($request->isGet()){
            $article = new Article();
            $flag = 'F';
            $msg = '未获取到任何数据';

            try{
                $res = $article->getAllArticles();
                $str_list = matchCenterImage($res);
                if(sizeof($res)>0){
                    $flag = 'S';
                    $msg = $res;
                }
                return json([
                    'flag' => $flag,
                    'msg' => $msg,
                    'url_list'=>$str_list
                ]);
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
     * 获取文章
     * @param Request $request
     * @return \think\response\Json
     */
    public function getArticleWithID(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $a_id = $request->post('aid');
            $article = new Article();
            $flag = 'F';
            $msg = '未获取到任何数据';
            try{
                $res = $article->getArticleWithID($a_id);
                if(sizeof($res) >= 1){
                    $flag = 'S';
                    $msg = $res[0];
                }
                return json([
                    'flag' => $flag,
                    'msg' => $msg
                ]);
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
}