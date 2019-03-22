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
    'getNineteenBigAnswer' => 'api/ExamApi/getNineteenBigAnswer',
    'insertArticle' => 'api/ArticleApi/insertArticle',
    'getAllArticles' => 'api/ArticleApi/getAllArticles',
    'getArticleWithID' => 'api/ArticleApi/getArticleWithID',
    'deleteArticlesByIds' => 'api/ArticleApi/deleteArticlesByIds',
    'searchArticle' => 'api/ArticleApi/searchArticle',
    'uploadImage' => 'api/UploadApi/uploadImage',
    'uploadVideo' => 'api/UploadApi/uploadVideoFromMine',
    'getActivityWithId' => 'api/ActivityApi/getActivityWithId',
    'updateActivity' => 'api/ActivityApi/updateActivity',
    'deleteActivityByID' => 'api/ActivityApi/deleteActivityByID',
    'insertNewActivity' => 'api/ActivityApi/insertNewActivity',
    'getAllVideos' => 'api/VideoApi/getAllVideos',
    'getVideoByID' => 'api/VideoApi/getVideoByID',
    'deleteVideoByID' => 'api/VideoApi/deleteVideoByID',
    'searchVideoByKW' => 'api/VideoApi/searchVideoByKW',
    'newAdmin' => 'api/adminApi/newAdmin',
    'deleteAdmin' => 'api/adminApi/deleteAdmin',
    'authorizationAdmin' => 'api/adminApi/authorizationAdmin',
    'getOneAdminInfo' => 'api/adminApi/getOneAdminInfo',
    'changeAdminPassword' => 'api/adminApi/changePassword',

//    'uploads/video' => 'api/Uploads/video',
//    'uploads/images' => 'api/Uploads/images',

    'admin/login' => 'admin/login/login',
    'admin/login/check_in' => 'admin/login/check_in',
    'admin/admin_management' => 'admin/admin_management/index',
    'admin/user_management' => 'admin/userManagement/index',
    'admin/user_management/searchUser' => 'admin/userManagement/searchUser',
    'admin/exam_management' => 'admin/examManagement/index',
    'admin/exam_add' => 'admin/examManagement/addExam',
    'admin/article_management' => 'admin/articleManagement/index',
    'admin/new_article' => 'admin/articleManagement/newArticle',
    '/admin/article_management/searchArticle' => 'admin/articleManagement/searchArticle',
    'admin/activities_management' => 'admin/activitiesManagement/index',
    'admin/activities_management/searchActivity' => 'admin/activitiesManagement/searchActivity',
    'admin/video_management' => 'admin/video_management/index',
    'admin/video_management/searchVideo' => 'admin/video_management/searchVideoBykw',

    'wxapi/article_detail' => 'admin/article_detail/index',

    'wxapi/get_wechatid_by_jscode' => 'wxapi/loginAPI/get_wechatid_by_jscode',
    'wxapi/verify_registration' => 'wxapi/loginAPI/use_wechatid_register',
    'wxapi/get_userinfo_by_wechatid' => 'wxapi/loginAPI/get_userinfo_by_wechatid',
    'wxapi/get_all_exam_category' => 'wxapi/examAPI/get_all_exam_category',
    'wxapi/get_exam_questions' => 'wxapi/examAPI/get_exam_questions',
    'wxapi/get_exam_score' => 'wxapi/examAPI/get_exam_score',
    'wxapi/get_user_read_message_list' => 'wxapi/articleAPI/get_user_read_message_list',
    'wxapi/user_watch_article' => 'wxapi/articleAPI/user_watch_article',
    'wxapi/get_user_not_read_message_count' => 'wxapi/articleAPI/get_user_not_read_message_count',
    'wxapi/get_latest_three_article' => 'wxapi/articleAPI/get_latest_three_article',
    'wxapi/get_all_news_list' => 'wxapi/articleAPI/get_all_news_list',
    'wxapi/news' => 'wxapi/ArticleView/news',
    'wxapi/messages' => 'wxapi/ArticleView/messages',
    'wxapi/get_video_category' => 'wxapi/videoAPI/get_category',
    'wxapi/get_video_list' => 'wxapi/videoAPI/get_video_list_by_category'

];
