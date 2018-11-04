<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/10/29
 * Time: 22:50
 */

namespace app\admin\controller;


use app\api\model\Article;
use think\Request;

class ArticleManagement extends Base
{
    public function index(){
        $article = new Article();
        $res = $article->getAllArticles();
        $this->view->assign('articles', $res);
        $this->view->assign('content_header', '浏览文章');
        $this->view->assign('menu_open3', 'menu-open');
        $this->view->assign('active31', 'active');
        return $this->view->fetch("article_browser");
    }

    public function newArticle(Request $request){
        if(!is_null($request) && $request->isGet()){
            $id = $request->get('aid');
            if(sizeof($id)>=1){
                $article = new Article();
                $res = $article->getArticleWithID($id);
                $this->view->assign('article', $res[0]);
            } else {
                $this->view->assign('article', null);
            }
        }
        $this->view->assign('content_header', '新建文章');
        $this->view->assign('menu_open3', 'menu-open');
        $this->view->assign('active32', 'active');
        return $this->view->fetch("article_editor");
    }

}