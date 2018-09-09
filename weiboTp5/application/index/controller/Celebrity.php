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

            $row = CelebrityModel::getList($params);
            if( $params['type'] == 'celebrityList' ){
                //处理媒体数据
                foreach ($row as $key => &$value) {
                   if( !empty($value['value'])){
                        $value['value'] = json_decode($value['value'],true);
                        $media = '';
                        foreach ($value['value'] as $kv => $vv) {
                            $media .= $vv['name'].'【'.$vv['uid'].'】';
                        }
                        $value['value'] = $media;
                   }
                }
            }
            


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

        }

    }

    public function edit(){

        return $this->fetch();
    }

    public function update(){
        if($this->request->isPost()){
            $params = $this->request->post();
            $params['status'] = $params['status'] == 'true' ? 0:1;
            $row = (new CelebrityModel())->allowField('true')->where(['id'=>$params['id']])->update($params);

            if( $row ){
                $data['url'] = Url::build('index/Celebrity/index');
                return json($data);
            }else{
                $data['code'] = 1;
                $data['message'] = '网络错误';
                return json($data);
            }
        }
    }

    public function del(){

    }
}