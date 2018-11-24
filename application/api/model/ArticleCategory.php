<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/23
 * Time: 16:17
 */

namespace app\api\model;


use think\Model;

class ArticleCategory extends Model
{
    public function getArticleCategory(){
        $res = ArticleCategory::field('level2')->select();
        return $res;
    }
}