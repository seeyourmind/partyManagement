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
     * 新建活动
     * @param $data
     * @return false|int
     */
    public function insertActivity($data){
        $res = Activities::allowField(true)->save($data);
        return $res;
    }

    /**
     * 获取全部活动
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getAllActivities(){
        $res = Activities::order('start_time', 'desc')->paginate('12');
        return $res;
    }

    /**
     * 获取某ID活动
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getActivityWithID($id){
        $res = Activities::where('id', '=', $id)->select();
        return $res;
    }

    /**
     * 更新活动内容
     * @param $data
     * @return $this
     */
    public function updateActivity($data){
        $res = Activities::where('id', '=', $data['id'])->update($data);
        return $res;
    }

    /**
     * 删除活动
     * @param $ids
     * @return int
     */
    public function deleteActivityBYIDS($ids){
        $id_str = $ids[0];
        foreach ($ids  as $id){
            if($id_str == $id){continue;}else{
                $id_str = $id_str.','.$id;
            }
        }
        $res = Activities::where("id in ($id_str)")->delete();
        return $res;
    }

    /**
     * 主题关键字搜索
     * @param $key
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function searchActivityByTheme($key){
        $res = Activities::where('name', 'like', "%{$key}%")->paginate(12);
        return $res;
    }
}