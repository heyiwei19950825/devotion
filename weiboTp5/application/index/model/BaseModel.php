<?php
/**
 * Created by PhpStorm.
 * User: 贺宜伟【ewei】
 * Date: 2018/9/5
 * Time: 11:39
 */

namespace app\index\model;


use think\Model;
class BaseModel extends Model
{
    //自定义初始化
    protected function initialize()
    {

        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }
}