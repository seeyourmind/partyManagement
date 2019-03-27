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
     * 上传视频
     * @param $path
     * @param $detail
     * @param $name
     * @param $thumbnail
     * @return int|string
     */
    public function insertNewVideo($path, $detail, $name, $vtme, $thumbnail)
    {
        $res = VideoManagement::allowField(true)->insert([
            'path' => $path,
            'detail' => $detail,
            'up_time' => date("Y-m-d H:i:s"),
            'file_name' => $name,
            'vtime' => $vtme,
            'thumbnail' => $thumbnail
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

    /**
     * 根据关键词查找对应视频
     * @param $keywords
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function searcherVideoByKeywords($keywords)
    {
        $res = VideoManagement::where('detail', 'like', "%{$keywords}%")->paginate(12);
        return $res;
    }

    /*******************************************************************************************************************
     * 获取视频分类
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCategoryForWX(){
        //$res = VideoManagement::query('SELECT detail FROM video_management GROUP BY detail');
        $res = VideoManagement::group('detail')->field('detail')->select();
        return $res;
    }

    /**
     * 获取一类视频
     * @param $category
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getVideoByCategoryForWX($category){
        $res = VideoManagement::where('detail', '=', $category)->select();
        return $res;
    }
}