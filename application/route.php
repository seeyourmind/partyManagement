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
    'insertUserinfo' => 'api/UserApi/insertOneUserinfo',
    'deleteUserWithID' => 'api/UserApi/deleteUserWithID',
    'updateUserInfo' => 'api/UserApi/updateUserInfo',
    'getAllUser' => 'api/UserApi/getAllUser',
    'getUserWithID' => 'api/UserApi/getUserWithID',
    'getUserWithName' => 'api/UserApi/getUserWithName',
    'uploadsFiles' => 'api/UserApi/uploadUserDataFiles',
    'getAllExam' => 'api/ExamApi/getExamList',
    'getExamWithID' => 'api/ExamApi/getExamWithID',
    'updateExam' => 'api/ExamApi/updateExamQuestion',
    'deleteQuestion' => 'api/ExamApi/deleteExamQuestionWithID',
    'insertQuestion' => 'api/ExamApi/insertExamQuestion',
    'insertQuestions' => 'api/ExamApi/insertExamQuestions',
    'admin/login' => 'admin/login/login',
    'admin/login/check_in' => 'admin/login/check_in',
    'admin' => 'admin/index/index',
    'admin/user_management' => 'admin/userManagement/index',
    'admin/user_management/searchUser' => 'admin/userManagement/searchUser',
    'admin/exam_management' => 'admin/examManagement/index',
    'admin/exam_add' => 'admin/examManagement/addExam',
];
