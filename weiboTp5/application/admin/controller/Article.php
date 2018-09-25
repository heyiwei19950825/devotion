<?php
namespace app\admin\controller;

use QL\QueryList;
use think\Config;
use think\Controller;
use think\Loader;
use think\Log;

class Article extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}
