<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/17
 * Time: 11:50
 */

namespace app\api\controller;


use app\api\model\Exam;
use app\api\model\ExamCategory;
use think\Controller;
use think\Exception;
use think\Request;

class ExamApi extends Controller
{

    /**
     * 获取全部试卷题库
     * @param Request $request
     * @return \think\response\Json
     */
    public function getExamList(Request $request){
        is_null($request) && $request;
        if ($request->isGet()){
            $ec = new ExamCategory();
            $exam = new Exam();
            try{
                $ec_datas = $ec->getExamCategory();
                $exam_list = $exam->getExam();
            } catch (Exception $e){
                return json([
                    'flag'  =>  'F',
                    'code'  =>  $e->getCode(),
                    'msg'   =>  $e->getMessage(),
                    'file'  =>  $e->getFile(),
                    'line'  =>  $e->getLine()
                ]);
            }
            $category = [];
            foreach ($ec_datas as $data){
                $category[$data['id']] = $data['explain'];
            }
            categoryID2NAME($category, $exam_list);
            return json([
                'flag'  =>  'S',
                'msg'   =>  '成功获取信息',
                'len'  =>  sizeof($exam_list),
                'data' =>  $exam_list
            ]);
        }
    }
    public function getExamWithID(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $params = $request->post();
            $id = $params['id'];
            $ec = new ExamCategory();
            $exam = new Exam();
            try{
                $ec_datas = $ec->getExamCategory();
                $exam_list = $exam->getExamWithID($id);
            } catch (Exception $e){
                return json([
                    'flag'  =>  'F',
                    'code'  =>  $e->getCode(),
                    'msg'   =>  $e->getMessage(),
                    'file'  =>  $e->getFile(),
                    'line'  =>  $e->getLine()
                ]);
            }
            $category = [];
            foreach ($ec_datas as $data){
                $category[$data['id']] = $data['explain'];
            }
            categoryID2NAME($category, $exam_list);
            return json([
                'flag'  =>  'S',
                'msg'   =>  '成功获取信息',
                'len'  =>  sizeof($exam_list),
                'data' =>  $exam_list[0]
            ]);
        }
    }
}