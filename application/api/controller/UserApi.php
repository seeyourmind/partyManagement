<?php
/**
 * Created by PhpStorm.
 * User: Fyzer
 * Date: 2018/7/5
 * Time: 10:14
 */

namespace app\api\controller;

use app\api\model\Userinfo;
use think\Controller;
use think\Exception;
use think\Request;

class UserApi extends Controller
{
    /**
     * 添加新用户数据
     * @param Request $request
     * @return string|\think\response\Json
     * @throws \Exception
     */
    public function insertOneUserinfo(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $datas = $request->post();
            $data = $datas['data'];
            $msg = '填写数据格式正确';
            $result = false;

            if (strlen($data['id'])<=1){
                $msg = $data['id'].'=>'.'账号不能为空';
            } elseif (strlen($data['name'])<=1){
                $msg = $data['name'].'=>'.'姓名不能为空';
            } else {
                $msg = $data;
                $user = new Userinfo();
                try{
                    $result = $user->insertUser(array($data));
                }catch (Exception $e){
                    return json([
                        'way' => 'post',
                        'flag'=>'F',
                        'code' => $e->getCode(),
                        'msg'  => $e->getMessage(),
                        'file'    => $e->getFile(),
                        'line'   => $e->getLine()
                    ]);
                }
            }

            if ($result){
                $msg = '数据添加成功';
            } else {
                $msg = '数据添加失败'.$result;
            }

            return json([
                'way' => 'post',
                'flag'=>'S',
                'data'=>$result,
                'msg' => $msg
            ]);
        }

        if($request->isAjax()){
            $data = $request->param();
            return json([
                'way' => 'Ajax',
                'get_data' => $data
            ]);
        }
    }

    /**
     * 依据用户ID删除用户信息
     * @param Request $request
     * @return string|\think\response\Json
     */
    public function deleteUserWithID(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $post_params = $request->post();
            $ids = $post_params['id'];
            $user = new Userinfo();
            $delete_sum = 0;
            foreach ($ids as $id){
                try{
                    $result = $user->deleteUserWithID($id);
                }catch (Exception $e){
                    return json([
                        'flag'=>'F',
                        'code' => $e->getCode(),
                        'msg'  => $e->getMessage(),
                        'file'    => $e->getFile(),
                        'line'   => $e->getLine()
                    ]);
                }
                $delete_sum += $result;
            }
            return json([
                'flag'=>'S',
                'msg'=>'用户删除成功',
                'data'=>$delete_sum,
            ]);
        } else {
            return 'please use post';
        }
    }
    /**
     * 获取用户信息列表
     * @param Request $request
     * @return \think\response\Json
     */
    public function getAllUser(Request $request){

        is_null($request) && $request;
        if ($request->isGet()) {
            $user = new Userinfo();
            try{
                $user_list = $user->getUserinfo();
            }catch (Exception $e){
                return json([
                    'flag'=>'F',
                    'code' => $e->getCode(),
                    'msg'  => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'   => $e->getLine()
                ]);
            }

            encryptOpenid($user_list);

            $result_len = sizeof($user_list);
            if ($result_len <= 0){
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>'为获取到任何用户信息'
                ]);
            } else {
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>$user_list
                ]);
            }
        }
    }
    /**
     * 根据ID获取用户的个人信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function getUserWithID(Request $request){
        is_null($request) && $request;
        if ($request->isGet()) {
            $user_id = $request->get('id');
            $user = new Userinfo();

            try{
                $user_info = $user->getUserinfoWithID($user_id);
            }catch (Exception $e){
                return json([
                    'flag'=>'F',
                    'code' => $e->getCode(),
                    'msg'  => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'   => $e->getLine()
                ]);
            }
            encryptOpenid($user_info);
            $result_len = sizeof($user_info);

            if ($result_len <= 0){
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>'为获取到任何用户信息'
                ]);
            } else {
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>$user_info[$result_len-1]
                ]);
            }
        } else if ($request->isAjax()){
            $user_id = $_POST['id'];
            $user = new Userinfo();

            try{
                $user_info = $user->getUserinfoWithID($user_id);
            }catch (Exception $e){
                return json([
                    'flag' => 'F',
                    'code' => $e->getCode(),
                    'msg'  => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'   => $e->getLine()
                ]);
            }

            return json([
                'way' => 'ajax',
                'flag'=>'S',
                'length'=>sizeof($user_info),
                'data'=>$user_info
            ]);
        } else {
            return json([
                'flag'=>'F',
                'data'=>'您使用了错误的方式访问服务器，请使用POST或AJAX'
            ]);
        }
    }
    /**
     * 根据姓名获取用户个人信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function getUserWithName(Request $request){
        is_null($request) && $request;
        if ($request->isGet()){
            $user_name = $request->get('name');
            $user = new Userinfo();
            try{
                $user_info = $user->getUserinfoWithName($user_name);
            }catch (Exception $e){
                return json([
                    'flag' => 'F',
                    'code' => $e->getCode(),
                    'msg'  => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'   => $e->getLine()
                ]);
            }
            encryptOpenid($user_info);
            $result_len = sizeof($user_info);

            if ($result_len <= 0){
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>'为获取到任何用户信息'
                ]);
            } else {
                return json([
                    'flag'=>'S',
                    'length'=>$result_len,
                    'data'=>$user_info
                ]);
            }
        }

        if ($request->isAjax()) {
            $user_name = $_POST['name'];
            $user = new Userinfo();

            try {
                $user_info = $user->getUserinfoWithName($user_name);
            } catch (Exception $e) {
                return json([
                    'flag' => 'F',
                    'code' => $e->getCode(),
                    'msg' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }

            return json([
                'way' => 'ajax',
                'flag' => 'S',
                'length' => sizeof($user_info),
                'data' => $user_info
            ]);
        }
    }

    /**
     * 更新用户个人信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function updateUserInfo(Request $request){
        is_null($request) && $request;
        if ($request->isPost()){
            $datas = $request->post();
            $data = $datas['data'];
            $user  = new Userinfo();
            try{
                $result = 0;
                foreach ($data as $d){
                    $result += $user->updateUserInfo($d);
                }
            }catch (Exception $e){
                return json([
                    'flag'=>'F',
                    'code' => $e->getCode(),
                    'msg'  => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'   => $e->getLine()
                ]);
            }

            return json([
                'flag'=>'S',
                'data'=>$result,
                'msg'=>'更新数据操作成功'
            ]);
        }
    }

    /**
     * 上传用户数据文件[单一文件处理]
     * @return \think\response\Json
     * @throws \Exception
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function uploadUserDataFiles(){
        include_once EXTEND_PATH.'PHPExcel/PHPExcel.php';

        $files = \request()->file('files');
        if (empty($files)){
            //$this->error('请选择上传文件');
            return json([
                'flag' => 'F',
                'msg' => '请选择上传的文件'
            ]);
        }
        $info = $files->move(ROOT_PATH.'uploads'.DS.'excel');
        if ($info){
            //$this->success('文件上传成功');
            //获取文件名
            $exclePath = $info->getSaveName();
            //上传文件的地址
            $filename = ROOT_PATH . 'public' . DS . 'uploads' . DS . $exclePath;

            //判断截取文件
            $extension = strtolower( pathinfo($filename, PATHINFO_EXTENSION) );

            //区分上传文件格式
            if($extension == 'xlsx') {
                $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
                $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
            }else if($extension == 'xls'){
                $objReader =\PHPExcel_IOFactory::createReader('Excel5');
                $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
            }

            $excel_array = $objPHPExcel->getsheet(0)->toArray();   //转换为数组格式
            array_shift($excel_array);  //删除第一个数组(标题);
            $user = [];
            foreach($excel_array as $k=>$v) {
                $user[$k]['id'] = $v[0];
                $user[$k]['name'] = $v[1];
                $user[$k]['birthday'] = $v[2];
                $user[$k]['nation'] = $v[3];
                $user[$k]['sex'] = $v[4];
                $user[$k]['league_time'] = $v[5];
                $user[$k]['party_apply_time'] = $v[6];
                $user[$k]['stage'] = $v[7];
                $user[$k]['stage_time'] = $v[8];
                $user[$k]['general_branch'] = $v[9];
                $user[$k]['branch'] = $v[10];
                $user[$k]['sponsor'] = $v[11];
                $user[$k]['assessor'] = $v[12];
                $user[$k]['honor'] = $v[13];
            }

            $userinfo = new Userinfo();
            $result = $userinfo->insertUser($user);
            return json([
                'flag' => 'S',
                'msg' => '用户信息添加成功',
                'result' => $result
            ]);
        } else {
            //$this->error($files->getError());
            return json([
                'flag' => 'F',
                'msg' => $files->getError()
            ]);
        }


        /*
        foreach($excel_array as $k=>$v) {
            if(empty(Db::name('excel_shop')->where(['goods_id'=>$v[0]])->value('name'))){
                $city[$k]['goods_id'] = $v[0];
                //$city[$k]['xxx'] = $v[1];
                //$city[$k]['xxx'] = $v[2];
            }
        }

        Db::name('excel_shop')->insertAll($city); //批量插入数据
        $output['status'] = true;
        $output['info'] = '导入数据成功~';
        $this->ajaxReturn($output);
        */
    }
}