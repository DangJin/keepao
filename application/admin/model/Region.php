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
        foreach ($region as &$province)
        {
            $province['city'] = $this->where('parent_code', $province['code'])->select()->toArray();

            foreach ($province['city'] as &$city)
            {
                $city['county'] = $this->where('parent_code', $city['code'])->select()->toArray();
            }
        }
        return returnJson(801, 200, $region);
    }

    public function getByParent($code)
    {
        $region = $this->where('parent_code', $code)->select()->toArray();
        return returnJson(801, 200, $region);
    }
}