<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/1
 * Time: 15:19
 */

namespace app\api\model;


use think\Db;
use think\Model;

class Article extends Model
{
    /**
     * 添加文章
     * @param $data
     * @return int
     * @throws \Exception
     */
    public function insterArticle($data){
        $res = Db::name('article')->insertGetId($data);

        return $res;
    }

    /**
     * 获取所有文章列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getAllArticles(){
        $res = Article::order('time', 'desc')->paginate(10);
        return $res;
    }

    /**
     * 通过ID获取文章详情
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  getArticleWithID($id){
        $res = Article::where('id', '=', $id)->select();
        return $res;
    }

    /**
     * 获取最新三则文章
     * @param $category
     * @param $uid
     * @return mixed
     */
    public function getLatestThressArticleWithCategory($category, $uid){
        if ($category == '通知公告'){
            $res = Db::query("SELECT id, level1 AS department, level2 AS category, title, time, 
                                    IF(id IN (SELECT aid FROM user_message WHERE uid = $uid), TRUE, FALSE) AS have_read 
                                    FROM article WHERE level2 = '$category' ORDER BY TIME DESC LIMIT 3");
        } else {
            $res = Db::query("SELECT article.id, level1 AS department, level2 AS category, title, time, hot
                                    FROM article, hot_article
                                    WHERE level2 = '时事新闻' AND article.id = hot_article.aid
                                    ORDER BY TIME DESC LIMIT 3");
        }
        return $res;
    }

    /**
     * 获取新闻列表
     * 支持分页
     * @param null $current_page
     * @param null $page_num
     * @return mixed
     */
    public function getAllNewsList($current_page=null, $page_num=null){
        if(is_null($current_page) || is_null($page_num)){
            $res = Db::query("SELECT article.id, level1 AS department, level2 AS category, title, time, hot
                                    FROM article, hot_article
                                    WHERE level2 = '时事新闻' AND article.id = hot_article.aid
                                    ORDER BY TIME DESC");
        } else {
            $start = ($current_page - 1) * $page_num;
            $res = Db::query("SELECT article.id, level1 AS department, level2 AS category, title, time, hot
                                    FROM article, hot_article
                                    WHERE level2 = '时事新闻' AND article.id = hot_article.aid
                                    ORDER BY TIME DESC LIMIT $start, $page_num");
        }
        return $res;
    }

    /**
     * 获取指定ID的文章内容
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getContentWithArticleId($id){
        $res = Article::field('title, content')->where('id', '=', $id)->select();
        return $res[0];
    }
}