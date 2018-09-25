<?php
/**
 * Created by PhpStorm.
 * User: 贺宜伟【ewei】
 * Date: 2018/9/4
 * Time: 10:57
 */

namespace app\admin\validate;


use think\Validate;

class BaseValiDate extends Validate
{

    /**
     * 判断是否在数组中
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function inArray( $value,$rule,$data ){
        return $rule == $value ? true: '请求操作不存在';
    }
}