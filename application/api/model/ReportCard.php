<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/11/21
 * Time: 14:12
 */

namespace app\api\model;


use think\Model;

class ReportCard extends Model
{
    /**
     * 插入新成绩单
     * @param $data
     * @return int|string
     */
    public function insertReportCard($data){
        $res = ReportCard::allowField(true)->insert($data);

        return $res;
    }
}