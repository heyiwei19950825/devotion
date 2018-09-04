<?php
namespace app\index\controller;

use QL\QueryList;
use think\Config;
use think\Controller;
use think\Loader;
use think\Log;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }



}
