<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/3/28
 * Time: 下午1:06
 */

namespace app\admin\validate;


class Region
{
    protected $rule = [
        'name'  =>  'require|unique:user,isdel=0&name=:name',
        'gender' => 'require',
    ];

    protected $message = [
        'name.require'  =>  '用户名必须',
        'name.unique'  => '姓名已经存在',
        'gender.require' => '性别不能为空',
    ];

    protected $scene = [
        'update' => ['name'],
    ];
}