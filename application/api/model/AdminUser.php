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
    public function loginValidate($data){
        $res = AdminUser::field('id')->where('username', '=', $data['username'])->where('password','=',$data['password'])->select();
        return $res;
    }
}