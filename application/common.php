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
// 替换试题列表中的分类ID
function categoryID2NAME($category, $exam){
    foreach ($exam as $item){
        $item['category'] = $category[$item['category']];
    }
}