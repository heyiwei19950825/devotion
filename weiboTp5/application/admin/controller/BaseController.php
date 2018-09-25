<?php
/**
 * Created by PhpStorm.
 * User: 贺宜伟【ewei】
 * Date: 2018/9/5
 * Time: 11:36
 */

namespace app\admin\controller;


use think\Controller;

class BaseController extends Controller
{
    public function _initialize(){

        $this->request->bind('user',['name'=>'ewei','age'=>1]);

    }
}