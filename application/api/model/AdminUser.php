<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/12/20
 * Time: 17:32
 */

namespace app\api\model;


use think\Model;

class AdminUser extends Model
{
    /**
     * 新建管理员信息
     * @param $data
     * @return int|string
     */
    public function insertAdminUser($data){
        $res = AdminUser::allowField(true)->insertAll($data);
        return $res;
    }

    /**
     * 管理员登录验证
     * @param $data
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function loginValidate($data){
        $res = AdminUser::field('id')->where('username', '=', $data['username'])->where('password','=',$data['password'])->select();
        return $res;
    }

    /**
     * 获取管理员的执行权限
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserAuthority($id){
        $res = AdminUser::field('authority')->where('id','=', $id)->select();
        return $res;
    }

    /**
     * 管理员授权
     * @param $id
     * @param $authority
     * @return $this
     */
    public function authorization($id, $authority){
        $res = AdminUser::where('id', '=', $id)->update(['authority'=>$authority]);
        return $res;
    }
}