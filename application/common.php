<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 对OpenID进行加密
function encryptOpenid($array){
    foreach ($array as $user){
        if(strlen($user['openID']) >= 1){
            $user['openID'] = md5($user['openID']);
        }
        unset($user['password']);
    }
}
// 试题分类ID转名称
function categoryID2NAME($category, $exam){
    foreach ($exam as $item){
        $item['category'] = $category[$item['category']];
    }
}
// 试题分类名称转ID
function categoryNAME2ID($exam_list, $id, $explain){
    foreach ($exam_list as $item){
        if ($item['category'] == $explain) $item['category'] = $id;
    }
}
// 提取图片路径
function matchCenterImage($article_list){
    $match_str = '#<figure class="image"><img src="/uploads/images/[0-9]*/([a-zA-Z0-9]*)\.[a-zA-Z]*"></figure>#';
    $main_image_list = [];
    foreach ($article_list as $article){
        preg_match($match_str, $article['content'], $arr);
        $whole_str = current($arr);
        $start = stripos($whole_str, 'src="')+5;
        $end = stripos($whole_str, '"></figure>');
        $image_url = substr($whole_str, $start, $end-$start);
        array_push($main_image_list, $whole_str?$image_url:'');
    }
    return $main_image_list;
}
// 提取试卷答案的ID
function getAnswerIDs($list){
    $ids = [];
    foreach ($list as $item){
        array_push($ids, $item['id']);
    }
    return $ids;
}
// 计算成绩
function getExamScore($user, $answer){
    $user_map = [];
    $answer_map = [];
    foreach ($user as $item) $user_map[$item['id']] = $item['answer'];
    foreach ($answer as $item) $answer_map[$item['id']] = $item['answer'];
    $score = sizeof(array_intersect_assoc($user_map, $answer_map));
    return $score;
}
// 检查WeChatID是否存在
function checkWechatId($wechatId){
    try{
        $userinfo = new \app\api\model\Userinfo();
        $res = $userinfo->getUserinfoByWechatid($wechatId);
        if($res){
            return true;
        } else {
            return false;
        }
    } catch (Exception $e){
        return false;
    }
}