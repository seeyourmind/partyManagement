<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/5
 * Time: 10:16
 */

namespace app\api\model;

use think\Model;

class Exam extends Model
{
    /**
     * 获取全部试卷题库
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getExam(){
        $res = Exam::paginate(12);

        return $res;
    }

    /**
     * 依据试题ID获取试题详情
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getExamWithID($id){
        $res = Exam::where('id', '=', $id)->select();

        return $res;
    }
    /**
     * 获取某一类别的试卷题库
     * @param $category
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getExamWithCategoryID($category){
        $res = Exam::where('category','=',$category)->paginate(12);

        return $res;
    }

    /**
     * 依据试题关键词查找对应试题
     * @param $keyword
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\exception\DbException
     */
    public function getExamWithKeyword($keyword){
        $res = Exam::where('question','like','%'.$keyword.'%')->paginate(12);

        return $res;
    }

    /**
     * 添加试题
     * @param $data
     * @return int
     * @throws \Exception
     */
    public function insertExam($data){
        $exam = new Exam();
        $res = $exam
            ->allowField(true)
            ->saveAll($data);

        return sizeof($res);
    }

    /**
     * 更新试题
     * @param $data
     * @return mixed
     */
    public function updateExam($data){
        $exam = new Exam();
        $res = $exam
            ->where('id','=',$data['id'])
            ->update($data);

        return sizeof($res);
    }

    /**
     * 删除一类试题
     * @param $category
     * @return int
     */
    public function deleteExamWithCategory($category){
        $res = Exam::where('category','=',$category)->delete();

        return sizeof($res);
    }

    /**
     * 删除某道试题
     * @param $id
     * @return int
     */
    public function deleteOneExam($id){
        $res = Exam::where('id','=',$id)->delete();

        return sizeof($res);
    }

    /**
     * 删除所有试题
     * @return int
     */
    public function deleteAllExam(){
        $res = Exam::where('id','>',0)->delete();

        return sizeof($res);
    }
}