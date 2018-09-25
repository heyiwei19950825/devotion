<?php
namespace app\home\controller;

use think\Controller;
use think\Request;

class BaseController extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }
}