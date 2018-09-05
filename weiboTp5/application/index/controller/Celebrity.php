<?php
/**
 * Created by PhpStorm.
 * User: 贺宜伟【ewei】
 * Date: 2018/9/5
 * Time: 11:36
 */

namespace app\index\controller;


use think\Db;
use think\Exception;
use think\Model;
use app\index\model\Celebrity as CelebrityModel;
use think\Url;


class Celebrity extends BaseController
{
    public function index(){
        if( $this->request->isPost()){
            $params = $this->request->post();
            $row = CelebrityModel::getList();

            $data = [
                'code' => 0,
                'message' => '查询成功',
                'data'    => $row
            ];

            return json($data);
        }

        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function save(){
        if( $this->request->isPost() ){
            $data = ['code'=>0,'message'=>'操作成功','data'=>''];
            $params = $this->request->post();
            $username = $media = $status = $describe = '';
            extract($params);

            //媒体信息序列化
            $value = [];
            for ($i=0;$i<count($media['name']);$i++){
                $value[$i] = [
                    'name' => $media['name'][$i],
                    'uid' => $media['uid'][$i],
                ];
            }
            $par = [
                'user_name' => $username,
                'value'     => json_encode($value),
                'status'    => (int)$status,
                'describe'  => $describe,
            ];

            try{
                $row = (new CelebrityModel())->allowField('true')->insert($par);
            }catch (Exception $e){
                if( strstr($e->getMessage(),'SQLSTATE[23000]')){
                    $data['code'] = 1;
                    $data['message'] = '红人名称已经存在';
                    return json($data);
                }
            }

            if( $row ){
                $data['url'] = Url::build('index/Celebrity/index');
                return json($data);
            }else{
                $data['code'] = 1;
                $data['message'] = '网络错误';
                return json($data);
            }

            CelebrityModel::saveData($data);
        }

    }

    public function edit(){

        return $this->fetch();
    }

    public function update(){

    }

    public function del(){

    }
}