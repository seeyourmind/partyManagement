<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/29
 * Time: 10:35
 */

namespace app\api\model;


use think\Model;

class VideoManagement extends Model
{
    public function insertNewVideo($path, $detail)
    {
        $res = VideoManagement::allowField(true)->insert([
            'path' => $path,
            'detail' => $detail,
            'up_time' => date("Y-m-d H:i:s")
        ]);
        return $res;
    }
}