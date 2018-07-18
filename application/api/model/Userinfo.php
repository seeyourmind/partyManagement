<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/5
 * Time: 10:17
 */

namespace app\api\model;

use think\Model;

class Userinfo extends Model
{
    /**
     * 添加新用户
     * @param $data
     * @return int
     * @throws \Exception
     */
    public function insertUser($data){
        $user = new Userinfo();
        $res = $user->allowField(true)->insertAll($data);

        return $res;
    }

    /**
     * 删除某ID的用户数据
     * @param $userid
     * @return int
     */
    public function deleteUserWithID($userid){
        $res = Userinfo::where('id','=',$userid)->delete();

        return $res;
    }

    /**
     * 更新用户数据
     * @param $data
     * @return int
     */
    public function updateUserInfo($data){
        $res = Userinfo::where('id','=',$data['id'])->update($data);

        return $res;
    }

    /**
     * 获取全部用户信息
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getUserinfo(){
        $res = Userinfo::order('id desc')->paginate(12);

        return $res;
    }

    /**
     * 获取某ID的用户信息
     * @param $userid
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getUserinfoWithID($userid){
        $res = Userinfo::where('id','=',$userid)->paginate(12);
        return $res;
    }

    /**
     * 获取某姓名的用户信息
     * @param $name
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getUserinfoWithName($name){
        $res = Userinfo::where('name','=',$name)->paginate(12);

        return $res;
    }
}