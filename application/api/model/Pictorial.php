<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2019/3/27
 * Time: 16:31
 */

namespace app\api\model;


use think\Model;

class Pictorial extends Model
{
    /**
     * 上传新画报
     * @param $url
     * @return int|string
     */
    public function uploadNewOne($url){
        $res = Pictorial::allowField(true)->insert([
            'url' => $url,
            'uptime' => date("Y-m-d H:i:s")
        ]);
        return $res;
    }

    /**
     * 删除画报
     * @param $id
     * @return int
     */
    public function deleteOnePictorial($id){
        $res = Pictorial::where('id','=',$id)->delete();
        return $res;
    }

    /**
     * 获取画报列表
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllPictorial(){
        $res = Pictorial::select();
        return $res;
    }

}