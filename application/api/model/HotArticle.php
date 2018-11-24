<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/22
 * Time: 22:07
 */

namespace app\api\model;


use think\Db;
use think\Model;

class HotArticle extends Model
{
    /**
     * 写入热点新闻
     * @param $data
     * @return false|int
     */
    public function insertHotArticle($data){
        $res = HotArticle::allowField(true)->save($data);
        return $res;
    }
    /**
     * 更新文章浏览次数
     * @param $aid
     * @return int
     */
    public function updateWatchTime($aid){
        $old_time = Db::query("select hot from hot_article where aid=$aid");
        if($old_time){
            $new_time = $old_time[0]['hot'] + 1;
            $res = Db::execute("update hot_article set hot=$new_time where aid=$aid");

            return $res;
        } else {
            return 0;
        }
    }
}