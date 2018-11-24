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
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getLatestThressArticleWithCategory($category){
        $res = Article::where('level2', '=', $category)->order('time', 'desc')->limit(3)->select();
        return $res;
    }
}