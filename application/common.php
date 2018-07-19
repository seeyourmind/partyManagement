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
//
function categoryNAME2ID($exam_list, $id, $explain){
    foreach ($exam_list as $item){
        if ($item['category'] == $explain) $item['category'] = $id;
    }
}