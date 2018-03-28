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

    protected $resultSetType = 'collection';

    public function select()
    {
        $region = $this->where('parent_code', '100000')->select()->toArray();
        foreach ($region as &$item)
        {
            $item['childs'] = $this->where('parent_code', $item['code'])->select()->toArray();
        }

        return 
    }
}