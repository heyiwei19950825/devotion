<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
        'id'    => '\d+',
    ],
    //子域名 路由模块
    '__domain__' => [
        'admin'     => 'admin',
        'tp'        => 'home',
        'api'       => 'api'
    ],


    'user/index'      => 'home/user/index',
    'user/create'     => 'home/user/create',
    'user/add'        => 'home/user/add',
    'user/add_list'   => 'home/user/addList',
    'user/update/:id' => 'home/user/update',
    'user/delete/:id' => 'home/user/delete',
    'user/:id'        => 'home/user/read',
];

