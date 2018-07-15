<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/8
 * Time: 20:30
 */

namespace app\admin\controller;

class Index extends Base
{
    public function index(){

        return $this->fetch('index');
    }
}