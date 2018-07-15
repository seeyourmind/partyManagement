<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    'insertUserinfo' => 'api/api/insertUserinfo',
    'deleteUserWithID' => 'api/api/deleteUserWithID',
    'updateUserInfo' => 'api/api/updateUserInfo',
    'getAllUser' => 'api/api/getAllUser',
    'getUserWithID' => 'api/api/getUserWithID',
    'getUserWithName' => 'api/api/getUserWithName',
    'admin/login' => 'admin/login/login',
    'admin/login/check_in' => 'admin/login/check_in',
    'admin' => 'admin/index/index',
    'admin/user_management' => 'admin/userManagement/index',
    'admin/user_management/searchUser' => 'admin/userManagement/searchUser'

];
