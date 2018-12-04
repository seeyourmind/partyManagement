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
    /**
     * 新建视频信息
     * @param $path
     * @param $detail
     * @return int|string
     */
    public function insertNewVideo($path, $detail, $name)
    {
        $res = VideoManagement::allowField(true)->insert([
            'path' => $path,
            'detail' => $detail,
            'up_time' => date("Y-m-d H:i:s"),
            'file_name' => $name
        ]);
        return $res;
    }

    /**
     * 获取视频列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getIDgroup()
    {
        $res = VideoManagement::query('SELECT detail, GROUP_CONCAT(id) AS id_count FROM video_management GROUP BY detail;');
        return $res;
    }
    public function getAllVideo()
    {
        $res = VideoManagement::order('up_time','desc')->paginate(18);
        return $res;
    }

    /**
     * 获取ID视频路径
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getVideoByID($id)
    {
        $res = VideoManagement::field('path')->where('id', '=', $id)->select();
        return $res;
    }

    /**
     * 删除视频信息
     * @param $id
     * @return int
     */
    public function deleteVideoByID($id)
    {
        $res = VideoManagement::where('id', '=', $id)->delete();
        return $res;
    }

    public function searcherVideoByKeywords($keywords)
    {
        $res = VideoManagement::where('detail', 'like', "%{$keywords}%")->paginate(12);
        return $res;
    }
}