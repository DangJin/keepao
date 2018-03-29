<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/23
 * Time: 下午3:38
 */

namespace app\index\model;


class User extends Common
{
    protected $insert = ['state' => 1, 'isdel' => 0];

    protected $parent = ['rid' => 'role'];

    protected $oneToMany = [];

    protected $manyToMany = ['roles' => 'use_role,uid'];

    public function roles()
    {
        $tmparr = explode(',', $this->manyToMany['roles']);
        return $this->belongsToMany('Role',$tmparr[0], 'rid', $tmparr[1]);
    }
}