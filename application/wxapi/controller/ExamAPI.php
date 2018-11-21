<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/20
 * Time: 21:28
 */

namespace app\wxapi\controller;


use app\api\model\Exam;
use app\api\model\ExamCategory;
use app\api\model\ReportCard;
use think\Controller;
use think\Exception;
use think\Request;

class ExamAPI extends Controller
{
    /**
     * 获取考试分类
     * @param Request $request
     * @return \think\response\Json
     */
    public function get_all_exam_category(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $examcategory = new ExamCategory();
            try{
                $res = $examcategory->getWXExamCategory();
                if($res){
                    return json([
                        'flag' => 'S',
                        'msg' => $res
                    ]);
                } else {
                    return json([
                        'flag' => 'F',
                        'msg' => '目前没有任何考试哦'
                    ]);
                }
            } catch (Exception $e) {
                return json([
                    'flag'  =>  'F',
                    'code'  =>  $e->getCode(),
                    'msg'   =>  $e->getMessage(),
                    'file'  =>  $e->getFile(),
                    'line'  =>  $e->getLine()
                ]);
            }
        } else {
            return json([
               'flag' => 'F',
               'msg' =>  '您使用的请求方式无效，请使用POST方式请求服务器'
            ]);
        }
    }

    /**
     * 获取试题
     * @param Request $request
     * @return \think\response\Json
     */
    public function get_exam_questions(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
           $category = $request->post('category');
           $question_count = $request->post('count');
           $wechat_id = $request->post('wechatId');
           // 用户身份校验
           if(!checkWechatId($wechat_id)){
               return json([
                   'flag' => 'F',
                   'msg' => '非法用户访问'
               ]);
           }

           if($category && $question_count && $wechat_id){
               try{
                   $exam_category = new ExamCategory();
                   $category_id = $exam_category->hasWXExamCategory($category);
                   if($category_id){
                       $exam = new Exam();
                       $res = $exam->getWXQuestions($question_count, $category_id[0]['id']);
                       if(sizeof($res)>=1){
                           return json([
                               'flag' => 'S',
                               'msg' => array('data'=>$res, 'len'=>sizeof($res))
                           ]);
                       } else {
                           return json([
                               'flag' => 'F',
                               'msg' => '未获取到该类别的试题'
                           ]);
                       }
                   } else {
                       return json([
                           'flag' => 'F',
                           'msg' => "目前没有 $category 的试卷"
                       ]);
                   }
               } catch (Exception $e){
                   return json([
                       'flag'  =>  'F',
                       'code'  =>  $e->getCode(),
                       'msg'   =>  $e->getMessage(),
                       'file'  =>  $e->getFile(),
                       'line'  =>  $e->getLine()
                   ]);
               }
           } else {
               return json([
                   'flag' => 'F',
                   'msg' => '请指定试卷类型和试题数量'
               ]);
           }
        } else {
            return json([
                'flag' => 'F',
                'msg' => '您使用的请求方式无效，请使用POST方式请求服务器'
            ]);
        }
    }

    /**
     * 计算成绩
     * @param Request $request
     * @return \think\response\Json
     */
    public function get_exam_score(Request $request){
        is_null($request) && $request;
        if($request->isPost()){
            $params = $request->post();
            $user_answers = $params['answers'];
            $wechat_id = $request->post('wechatId');
            $category = $request->post('category');
            // 用户身份校验
            if(!checkWechatId($wechat_id)){
                return json([
                    'flag' => 'F',
                    'msg' => '非法用户访问'
                ]);
            }
            if ($user_answers && $wechat_id && $category){
                try{
                    $answer_ids = getAnswerIDs($user_answers);
                    $exam = new Exam();
                    $res = $exam->getWXAnswers(implode(',', $answer_ids));
                    if($res){
                        $score = getExamScore($user_answers, $res);
                        $total = sizeof($user_answers);
                        $score_ = round(($score/$total)*100);
                        $report_card = new ReportCard();
                        $data = array('wechatid'=>$wechat_id,'exam'=>$category,'score'=>$score_, 'time'=>date("Y-m-d H:i:s"));
                        $res = $report_card->insertReportCard($data);
                        if($res){
                            return json([
                                'flag' => 'S',
                                'msg' => array('score'=>$score, 'total'=>$total, 'inDB'=>true)
                            ]);
                        } else {
                            return json([
                                'flag' => 'S',
                                'msg' => array('score'=>$score, 'total'=>$total, 'inDB'=>false)
                            ]);
                        }
                    } else {
                        return json([
                            'flag' => 'F',
                            'msg' => '未找到对应试题编号的答案'
                        ]);
                    }
                } catch (Exception $e){
                    return json([
                        'flag'  =>  'F',
                        'code'  =>  $e->getCode(),
                        'msg'   =>  $e->getMessage(),
                        'file'  =>  $e->getFile(),
                        'line'  =>  $e->getLine()
                    ]);
                }
            } else {
                return json([
                    'flag' => 'F',
                    'msg' => '未获取到您提交的试题答案，请重新提交'
                ]);
            }
        } else {
            return json([
                'flag' => 'F',
                'msg' => '您使用的请求方式无效，请使用POST方式请求服务器'
            ]);
        }
    }
}