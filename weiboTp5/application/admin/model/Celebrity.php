<?php
/**
 * Created by PhpStorm.
 * User: 贺宜伟【ewei】
 * Date: 2018/9/5
 * Time: 11:39
 */

namespace app\admin\model;


use think\Db;

class Celebrity extends BaseModel
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    //自定义初始化
    protected function initialize()
    {
        parent::initialize();
        //TODO:自定义的初始化
    }

    static function getList( $params = [] ){
        $field = $page = $limit = $keyword = $type = '';
        extract($params);

        $row = self::where([])->field($field)->select();
        

        return $row;
    }

    static function saveData( $data = [] ){
        $row = self::create($data);
        return $row;
    }

    static function getInfo($params=[],$field=''){
        
        $row = self::where($params)->field($field)->find();

        return $row;
    }
}