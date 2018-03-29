<?php
namespace app\index\controller;

use app\index\model\User;
use app\index\model\UseRole;
use think\Db;

class Index
{
    public function test()
    {
        $region = Db::table('region')->where('id', 1)->select();
        return json($region);
    }

    public function add()
    {
        echo '123';
    }
}
