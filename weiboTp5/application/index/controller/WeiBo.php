<?php
/**
 * Created by PhpStorm.
 * User: 贺宜伟【ewei】
 * Date: 2018/9/4
 * Time: 16:45
 */

namespace app\index\controller;


use app\common\helper;
use think\Controller;
use think\Loader;
use think\Log;
use app\index\model\Celebrity as CelebrityModel;

class WeiBo extends Controller
{

    public function getListByUid(){
        $params         = $this->request->post(); //uid 获取目标用户ID号 熊吱吱ID: 3920497465 //type 获取接口类型
        $params['type'] = 'container';
        
        //数据验证
        $valiDate     =   Loader::validate('IndexValiDate');
        if( !$valiDate->check($params) ){
            //写入错误日志
            Log::record(
                array(
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'user_ip'    => $_SERVER["REMOTE_ADDR"],
                    'message'    => $valiDate->getError(),
                    'function'   => __CLASS__,
                ),'error'
            );
            //返回错误数据
            return [
                'error' => 10010,
                'message' => $valiDate->getError(),
            ];
        }

        //初始化数据
        $uid = $type = $url = $page = $media = '';
        $wbUrl = config('weibo');   //获取微博接口列表
        extract($params);

        //获取用户微博ID
        $userInfo = CelebrityModel::getInfo(['id'=>$uid],'value');
        $userInfo = json_decode($userInfo['value'],true);
        foreach ($userInfo as $key => $value) {
            if($value['name'] == $media){
                $uid = $value['uid'];
            }
        }

        //获取微博数据
        foreach ( $wbUrl['api_url'] as $k => $v ){
            if( $type == $k ){
                $url = $v['url'].'?type=uid&value='.$uid;
            }
        }

        //获取微博用户的信息
        $data = json_decode(helper::http_curl($url),true);

        if( $data['ok'] != 1 ){
            Log::record('微博数据获取失败 微博接口为：','error');
            return false;
        }else{
            Log::record($data['data'],'log');
        }

        $wbInfo =  array(
            'userInfo' => [
                'id'   => $data['data']['userInfo']['id'],//ID
                'name' => $data['data']['userInfo']['screen_name'],//名称
                'profile' => $data['data']['userInfo']['profile_image_url'],//头像
                'verified_reason' => $data['data']['userInfo']['verified_reason'],//标签
                'description' => $data['data']['userInfo']['description'],//描述
                'followers_count' => $data['data']['userInfo']['followers_count'],//关注数量
            ]
        );
        $containerId = 0;
        //获取微博容器ID
        foreach ($data['data']['tabsInfo']['tabs'] as $v){
            if(in_array('微博',$v)){
                $containerId = $v['containerid'];
            }
        }
        $listRow = [];
        //根据抓取页数  抓取相应页码的数据
        for ($i=1; $i <= $page; $i++) { 
            $getRow = json_decode(helper::http_curl($url.'&containerid='.$containerId.'&page='.$i),true);
            if( $getRow['ok'] != 1 ){
                Log::record('微博数据获取失败 微博接口为：','error');
                return false;
            }else{
                //获取微博用户所发微博数据
                foreach( $getRow['data']['cards'] as $k=>$v ) {
                    if( isset($v['mblog']) ){
                        $pics = '暂无图片';
                        if( isset($v['mblog']['pics']) ) {
                            $pics = '';
                            foreach ($v['mblog']['pics'] as $pk => $pv) {
                                $pics .= $pv['url'] . '@@@';
                            }
                            $pics = '<div class="layui-btn layui-btn-sm" data-val="' . trim($pics, ",") . '">点击查看图片</div>';
                        }
                        $scheme = explode('/',explode('?',$v['scheme'])[0])[4];
                        
                        $getRowAfter[$k] = array(
                            'id' => $data['data']['userInfo']['id'],
                            'username' => $data['data']['userInfo']['screen_name'],
                            'created_at' => $v['mblog']['created_at'],
                            'url' => 'https://weibo.com/'.$uid.'/'.$scheme,
                            'text' => str_replace("<br",'',$v['mblog']['text']),
                            'pics' => $pics,
                            'reposts_count' => $v['mblog']['reposts_count'],
                            'comments_count' => $v['mblog']['comments_count'],
                            'attitudes_count' => $v['mblog']['attitudes_count'],
                        );
                    }

                }
                $listRow = array_merge($listRow, $getRowAfter);

                Log::record($getRow['data'],'log');
            }
        }
        $listRow = helper::arrayUnique($listRow);
        if(!empty($listRow)){
            $row = [
                'code' => 0,
                'message' => '查询成功',
                'data' => $listRow,
            ];
        }

       return json($row);
    }
}