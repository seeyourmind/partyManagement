<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/5
 * Time: 10:17
 */

namespace app\api\model;

use think\Model;

class ExamCategory extends Model
{
    /**
     * 获取全部试卷分类信息
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getExamCategory(){
        $res = ExamCategory::select();

        return $res;
    }

    /**
     * 获取某一类别的试卷分类信息
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getExamCategoryWithID($id){
        $res = ExamCategory::where('id','=',$id)->select();

        return $res;
    }

    /**
     * 添加试卷分类信息
     * @param $data
     * @return array|false
     * @throws \Exception
     */
    public function insertExamCategory($data){
        $res = ExamCategory::allowField(true)->saveAll($data);

        return sizeof($res);
    }

    public function updateExamCategory($data){
        $res = ExamCategory::where('id','=',$data['id'])->update($data);

        return sizeof($res);
    }

    /**
     * 删除某ID对应的试卷分类信息
     * @param $id
     * @return int
     */
    public function deleteExamCategoryWithID($id){
        $res = ExamCategory::where('id','=',$id)->delete();

        return sizeof($res);
    }

    /**
     * 删除全部试卷分类信息
     * @return int
     */
    public function deleteAllExamCategory(){
        $res = ExamCategory::where('id','>',0)->delete();

        return sizeof($res);
    }
}