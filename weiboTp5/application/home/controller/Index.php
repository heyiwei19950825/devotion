<?php
namespace app\home\controller;
use think\Hook;
use think\Request;
load_trait('controller/Jump');

class Index extends BaseController
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    public function index()
    {

    }
}
