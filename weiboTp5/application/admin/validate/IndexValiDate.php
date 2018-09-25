<?php
/**
 * Created by PhpStorm.
 * User: 贺宜伟【ewei】
 * Date: 2018/9/4
 * Time: 10:54
 */

namespace app\admin\validate;


class IndexValiDate extends BaseValiDate
{
    protected $rule = [
        'uid'   => 'require|number',
        'type'  => 'require',
        'page'  => 'require|number'
    ];

    protected $message = [
        'uid.require'   =>    '必须填入博主ID',
        'uid.number'    =>    '博主ID只能是数字',
        'type.require'  =>    '必须选择微博爬取操作',
        'page.require'   =>    '必须填入页码',
        'page.number'    =>    '页码只能是数字',
    ];
}