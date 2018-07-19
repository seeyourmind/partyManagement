<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/17
 * Time: 10:47
 */

namespace app\admin\controller;

use app\api\model\Exam;
use app\api\model\ExamCategory;

class ExamManagement extends Base
{
    /**
     * 点击浏览试卷
     * @return string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(){
        $ec = new ExamCategory();
        $exam = new Exam();
        $ec_datas = $ec->getExamCategory();
        $category = [];
        foreach ($ec_datas as $data){
            $category[$data['id']] = $data['explain'];
        }
        $exam_list = $exam->getExam();
        categoryID2NAME($category, $exam_list);
        $this->view->assign('category', json_encode($category));
        $this->view->assign('exams', $exam_list);
        $this->view->assign('content_header', '试卷信息管理');
        $this->view->assign('menu_open2', 'menu-open');
        $this->view->assign('active21', 'active');
        return $this->view->fetch('exam_management');
    }

    public function addExam(){
        $ec = new ExamCategory();
        $ec_datas = $ec->getExamCategory();
        $category = [];
        foreach ($ec_datas as $data){
            $category[$data['id']] = $data['explain'];
        }
        $question_list = [['question'=>null,'item1'=>null,'item2'=>null,'item3'=>null,'item4'=>null,'answer'=>null]];
        $this->view->assign('question_list', $question_list);
        $this->view->assign('category', json_encode($category));
        $this->view->assign('content_header', '试卷信息管理');
        $this->view->assign('menu_open2', 'menu-open');
        $this->view->assign('active22', 'active');
        return $this->view->fetch('exam_management_add');
    }
}