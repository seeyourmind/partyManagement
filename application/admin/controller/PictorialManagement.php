<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2019/3/27
 * Time: 15:43
 */

namespace app\admin\controller;


use app\api\model\Pictorial;
use think\Exception;

class PictorialManagement extends Base
{
    public function index(){
        $pimgs = new Pictorial();
        try{
            $pictorial = $pimgs->getAllPictorial();
        } catch (Exception $e){
            $pictorial = [];
        }
        $this->view->assign('content_header', '宣传画报管理');
        $this->view->assign('id_group', $pictorial);
        $this->view->assign('hide', sizeof($pictorial)>0?'hidden':'');
        $this->view->assign('active6', 'active');
        return $this->view->fetch('pictorial_management');
    }

}