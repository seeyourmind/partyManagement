<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/10/29
 * Time: 22:50
 */

namespace app\admin\controller;


use app\api\model\AdminUser;
use app\api\model\Article;
use app\api\model\ArticleCategory;
use think\Request;

class ArticleManagement extends Base
{
    public function index(){
        $authority = session('admin_authority');
        if(!$authority){
            $admin = new AdminUser();
            $authority = $admin->getUserAuthority(session('admin_username'))[0]['authority'];
        }

        if($authority=='0' || stristr($authority,'3')!=false){
            $article = new Article();
            $res = $article->getAllArticles();
            $this->view->assign('articles', $res);
            $this->view->assign('content_header', '浏览文章');
            $this->view->assign('menu_open3', 'menu-open');
            $this->view->assign('active31', 'active');
            return $this->view->fetch("article_browser");
        } else {
            $this->view->assign('content_header', '浏览文章');
            $this->view->assign('menu_open3', 'menu-open');
            $this->view->assign('active31', 'active');
            return $this->view->fetch('/login/authority');
        }

    }

    public function newArticle(Request $request){
        $authority = session('admin_authority');
        if(!$authority){
            $admin = new AdminUser();
            $authority = $admin->getUserAuthority(session('admin_username'))[0]['authority'];
        }

        if($authority=='0' || stristr($authority,'3')!=false){
            if(!is_null($request) && $request->isGet()){
                $id = $request->get('aid');
                if(sizeof($id)>=1){
                    $article = new Article();
                    $res = $article->getArticleWithID($id);
                    $this->view->assign('category', json_encode([]));
                    $this->view->assign('article', $res[0]);
                    $this->view->assign('select_hidden', 'hidden');
                    $this->view->assign('input_hidden', '');
                } else {
                    $article_category = new ArticleCategory();
                    $category_of_article = $article_category->getArticleCategory();
                    $this->view->assign('category', json_encode($category_of_article));
                    $this->view->assign('article', null);
                    $this->view->assign('select_hidden', '');
                    $this->view->assign('input_hidden', 'hidden');
                }
            }
            $this->view->assign('content_header', '新建文章');
            $this->view->assign('menu_open3', 'menu-open');
            $this->view->assign('active32', 'active');
            return $this->view->fetch("article_editor");
        } else {
            $this->view->assign('content_header', '新建文章');
            $this->view->assign('menu_open3', 'menu-open');
            $this->view->assign('active32', 'active');
            return $this->view->fetch('/login/authority');
        }
    }

    public function searchArticle(Request $request){
        if(!is_null($request) && $request->isGet()){
            $key = $request->get('key');
            $article = new Article();
            $res = $article->searcheArticle($key);

            $this->view->assign('articles', $res);
            $this->view->assign('content_header', '浏览文章');
            $this->view->assign('menu_open3', 'menu-open');
            $this->view->assign('active31', 'active');
        } else {
            $this->view->assign('articles', []);
            $this->view->assign('content_header', '浏览文章');
            $this->view->assign('menu_open3', 'menu-open');
            $this->view->assign('active31', 'active');
        }
        return $this->view->fetch("article_browser");
    }
}