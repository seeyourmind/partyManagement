<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/5
 * Time: 10:16
 */

namespace app\api\model;

use think\Model;

class Dues extends Model
{
    /**
     * 获取全部党费缴纳流水
     * @return false|static[]
     * @throws \think\exception\DbException
     */
    public function getAllDues(){
        $res = Dues::all();
        return $res;
    }

    /**
     * 获取某位党员的党费缴纳流水
     * @param $userid
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDuesWithUserID($userid){
        $res = Dues::where('userid', '=', $userid)->select();
        return $res;
    }

    /**
     * 插入一条党费缴纳记录
     * @param $data
     * @return int
     * @throws \Exception
     */
    public function insertDues($data){
        $dues = new Dues();
        $res = $dues
            ->allowField(true)
            ->saveAll($data);

        return sizeof($res);
    }
}