<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/23
 * Time: 9:10
 */

namespace app\api\model;


use think\Db;
use think\Model;

class UserMessage extends Model
{
    /**
     * 建立用户与已读文章的关联
     * @param $uid
     * @param $aid
     * @return int
     */
    public function insertUserArticleRelation($uid, $aid){
        $res0 = Db::query("select id from article where id=$aid and level2='通知公告'");
        if($res0){
            $res1 = Db::query("select id from user_message where uid=$uid and aid=$aid");
            if($res1){
                return 1;
            } else {
                $res2 = UserMessage::insert(['uid' => $uid, 'aid' => $aid]);
                if($res2){
                    return 2;
                } else {
                    return 3;
                }
            }
        } else {
            return 0;
        }
    }

    /**
     * 获取用户已读消息和未读消息列表
     * 支持分页
     * @param $uid
     * @param null $page_num
     * @param null $current_page
     * @return mixed
     */
    public function getReadOrNotMessages($uid, $page_num=null, $current_page=null){
        if(is_null($current_page)||is_null($page_num)){
            $res = Db::query("SELECT id, level1 AS department, title, time, IF(id IN (SELECT aid FROM user_message WHERE uid = $uid), TRUE, FALSE) AS have_read
                                    FROM article
                                    WHERE level2='通知公告'
                                    ORDER BY time DESC");

        } else {
            $start = ($current_page - 1) * $page_num;
            $res = Db::query("SELECT id, level1 AS department, title, time, IF(id IN (SELECT aid FROM user_message WHERE uid = $uid), TRUE, FALSE) AS have_read
                                    FROM article
                                    WHERE level2='通知公告'
                                    ORDER BY time DESC LIMIT $start,$page_num");
        }

        return $res;
    }

    /**
     * 获取未读消息条数
     * @param $uid
     * @return mixed
     */
    public function getNotReadMessagesCount($uid){
        $res = Db::query("SELECT COUNT(*) AS count_ FROM article WHERE level2='通知公告' AND id NOT IN (SELECT aid FROM user_message WHERE uid = $uid)");
        return $res;
    }
}