<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/28
 * Time: 17:41
 */

namespace app\admin\controller;


use app\api\model\Article;
use think\Controller;

class ArticleDetail extends Controller
{
    public function index(){
        $article = new Article();
        $content = $article->getContentWithArticleId(1);
        if($content){
            $this->view->assign('title', $content['title']);
            $this->view->assign('content', $content['content']);
        } else {
            $this->view->assign('title', 'ERROR');
            $this->view->assign('content', 'Sorry! there is a error happened');
        }

        return $this->view->fetch('article_detail');
    }
}