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
     * @throws \think\exception\DbException
     */
    public function getExamCategory(){
        $res = ExamCategory::paginate(12);

        return $res;
    }

    /**
     * 获取某一类别的试卷分类信息
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getExamCategoryWithID($id){
        $res = ExamCategory::where('id','=',$id)->paginate(12);

        return $res;
    }

    /**
     * 依据文字说明获取ID
     * @param $explain
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getExamCategoryWithExplain($explain){
        $res = ExamCategory::where('explain','=',$explain)->field('id')->paginate(12);

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

    //wxapi-------------------------------------------------------------------------------------------------------------
    /**
     * 微信端获取所有试题分类列表
     * @return false|static[]
     * @throws \think\exception\DbException
     */
    public function getWXExamCategory(){
        $res = ExamCategory::all();
        return $res;
    }

    /**
     * 获取试题分类编号，依据分类说明
     * @param $category
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function hasWXExamCategory($category){
        $res = ExamCategory::field('id')->where('id', '=', $category)->select();
        return $res;
    }
}