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
            $data = array(
                'title' => $request->post('title'),
                'level1' => $request->post('level1'),
                'level2' => $request->post('level2'),
                'content' => $request->post('content'),
                'time' => $request->post('time')
                );

            $cover_file = $request->file('cover');
            $msg = '';
            if(!empty($cover_file)){
                //todo 上传封面，并保存
                $info = $cover_file->move(ROOT_PATH.'uploads'.DS.'images');
                if($info){
                    $cover_path = $info->getSaveName();
                    $cover_path = str_replace('\\', '/', $cover_path);
                    $msg = 'FS';
                    $data['cover'] = $cover_path;
                }
            }
            $article = new Article();
            $hot_article = new HotArticle();
            try{
                $res1 = $article->insterArticle($data);
                if(intval($res1)){
                    $res2 = $hot_article->insertHotArticle(array('aid'=>intval($res1)));
                    if($res2){
                        return json([
                            'flag' => 'S',
                            'msg' => '文章发布成功'.$msg
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

    /**
     * 关键词搜索文章
     * @param Request $request
     * @return \think\response\Json
     */
    public function searchArticle(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $keywords = $request->post('keyword');
            if(!$keywords){
                return json([
                    'flag' => 'F',
                    'msg' => '未获取到合法参数'
                ]);
            } else {
                try{
                    $article = new Article();
                    $res = $article->searcheArticle($keywords);
                    if ($res){
                        return json([
                            'flag' => 'S',
                            'msg' => $res
                        ]);
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '未找到符合条件的数据'
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
     * 删除指定IDS的文章
     * @param Request $request
     * @return \think\response\Json
     */
    public function deleteArticlesByIds(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $aids = $request->post('aids/a');
            if($aids){
                try{
                    $article = new Article();
                    $res = $article->deleteArticleByIds($aids);
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
                } catch (Exception $e) {
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
                    'msg' => '未获取到合法参数'
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