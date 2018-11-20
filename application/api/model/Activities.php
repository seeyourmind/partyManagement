<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/16
 * Time: 15:46
 */

namespace app\api\model;


use think\Model;

class Activities extends Model
{
    /**
     * 获取全部活动
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getAllActivities(){
        $res = Activities::order('start_time', 'desc')->paginate('12');
        return $res;
    }
}