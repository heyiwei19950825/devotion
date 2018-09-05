<?php
/**
 * Created by PhpStorm.
 * User: 贺宜伟【ewei】
 * Date: 2018/9/5
 * Time: 10:13
 */

namespace app\index\controller;


use think\Controller;

class Tool extends Controller
{
    public function calculate(){
        return $this->fetch('public/tool_calculate');
    }
}