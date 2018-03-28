<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/23
 * Time: 下午6:59
 */

namespace app\index\validate;


use think\Validate;

class User extends Validate
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