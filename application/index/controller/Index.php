<?php
namespace app\index\controller;

use app\index\model\User;
use app\index\model\UseRole;

class Index
{
    public function test()
    {
//        $user = new User();
//        $user = new UseRole();
//        return $user->select([]);
//        return $user->select(['search' => 1, 'searchForField' => 'name,gender', 'all' => 1, 'sort' => 1, 'and' => 1]);
//        return $user->add(['name' => 'wang3','gender' => 1, 'roles' => '1,2,3']);
//        return $user->del(['ids' => '2']);
//        $user->renew([]);
        $contact = ['roles' => 'use_role,uid'];
        foreach ($contact as $i) {
            dump($i);
        }
    }

    public function add()
    {
        echo '123';
    }
}
