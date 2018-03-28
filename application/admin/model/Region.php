<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/3/28
 * Time: 下午1:05
 */

namespace app\admin\model;


use think\Model;

class Region extends Model
{
    protected $pk = 'id';

    public function select()
    {
        $region = $this->where('parent_code', '100000')->select();
        dump($region);
    }
}