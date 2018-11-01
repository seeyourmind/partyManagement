<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/1
 * Time: 15:19
 */

namespace app\api\model;


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
        $article = new Article();
        $res = $article->allowField(true)->saveAll($data);

        return sizeof($res);
    }

    /**
     * 获取所有文章列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getAllArticles(){
        $res = Article::paginate(10);
        return $res;
    }
}