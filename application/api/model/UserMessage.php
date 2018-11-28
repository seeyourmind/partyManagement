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
     * @param $wechatid
     * @return mixed
     */
    public function getReadOrNotMessages($uid){
        $res = Db::query("SELECT id, level1 AS department, title, time, have_read FROM (
        SELECT *, false AS have_read FROM article WHERE level2='通知公告' AND id NOT IN ( SELECT aid FROM user_message WHERE uid = $uid )
        UNION SELECT *, true AS have_read FROM article WHERE level2='通知公告' AND id IN ( SELECT aid FROM user_message WHERE uid = $uid )
        ) AS tablea ORDER BY time DESC");

        return $res;
    }

    public function getNotReadMessagesCount($uid){
        $res = Db::query("SELECT COUNT(*) AS count_ FROM article WHERE level2='通知公告' AND id NOT IN (SELECT aid FROM user_message WHERE uid = $uid)");
        return $res;
    }
}