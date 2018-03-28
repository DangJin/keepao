<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/3/28
 * Time: 下午1:06
 */

namespace app\admin\model;


use think\Model;

class Common  extends Model
{
    protected $pk = 'id';

    //自动填充
    protected $insert = ['state' => 1, 'isdel' => 0];

    //多对多
    protected $manyToMany = [];

    //[对应表名 => 表中字段]
    protected $parent = [];

    //[对应表名 => 对应表中字段]
    protected $oneToMany = [];

    protected $addallow = true;

    protected $upallow = true;

    /**
     * Function: select
     * Description: $daat['and'] = 1 and查询,
     *              $data['all'] = 1 查询全部
     * Author  : wry
     * DateTime: 18/1/23 下午6:14
     */
    public function select($data, $page = 1, $limit = 10, $all = '')
    {
        if (!empty($data['order'])) {
            $result = $this->order($data['order']);
        } else {
            $result = $this->order('sort desc');
        }

        //多条件查询
        $map = [];
        if (isset($data['search']) && !empty($data['search'])) {
            if (preg_match('/^[0-9]+$/', $data['search'])) {
                $condi = (int)$data['search'];
            } else {
                $condi = ['like', '%'.$data['search'].'%'];
            }
            if (isset($data['searchForField']) && !empty($data['searchForField'])) {
                $tmp = explode(',', $data['searchForField']);
                $tmp = array_unique($tmp);
                $tmp = array_filter($tmp);
                foreach ($tmp as $item) {
                    $map[$item] = $condi;
                }
                $result->where(function ($query) use ($map) {
                    $query->whereOr($map);
                });
            }
        }

        //筛选条件数组
        $map = [];
        if (!empty($data)) {
            foreach (array_keys($data) as $key) {
                if (in_array($key, $this->getTableFields())) {
                    $pos = stripos($data[$key], ',');
                    if ($pos && $pos != strlen($data[$key])) {
                        $map[$key] = explode(',', $data[$key]);
                    } else {
                        $map[$key] = $data[$key];
                    }
                }
            }
        }


        //查询条件形式
        if (!empty($map)) {
            if (isset($data['and']) && $data['and'] == 1) {
                $result = $result->where(function ($query) use ($map) {
                    $query->where($map);
                });
            } else {
                $result = $result->whereOr(function ($query) use ($map) {
                    $query->whereOr($map);
                });
            }
        }

        $result = $result->where('isdel', '<>', '1');

        try {
            //查询全部数据（不分页）
            if (isset($data['all']) && $data['all'] == 1) {
                if (empty($all)) {
                    $count = $result->count();
                    return $this->select($data, $page, $limit, $count + 1);
                }
                $result = $result->paginate($all - 1, false, ['page' => 1]);
            } else {
                $result = $result->paginate($limit, false, ['page' => $page]);
            }
            $result = $result->toArray();

            //查询父表数据
            if (isset($data['hasParent']) && $data['hasParent'] == 1) {
                foreach ($result['data'] as &$item) {
                    foreach ($this->parent as $k => $v) {
                        $tmp = explode('|', $v);
                        if (sizeof($tmp) == 1) {
                            $tmp[1] = '';
                        }
                        $item[$tmp[0]] = $this->table($k)->where('id', $item[$tmp[0]])->field($tmp[1])->find();
                    }
                }
            }

            //查询子表数据
            if (isset($data['children']) && !empty($data['children'])) {
                $keys = array_keys($this->oneToMany);
                if (!empty($keys)) {
                    foreach ($result['data'] as &$item) {
                        $child = $this->getChild($data['children'], $item['id'], $keys);
                        $item = array_merge($item, $child);
                    }
                }
            }

            return returnJson(701, 200, $result);
        } catch (Exception $e) {
            return returnJson(601,400, $e->getMessage());
        }
    }

    public function getChild($tables, $id, $keys)
    {
        $children = explode(',', $tables);
        $children = array_filter($children);
        $children = array_unique($children);

        $result = [];
        foreach ($children as $child) {
            if (in_array($child, $keys)) {
                $result[$child] = $this->table($child)->where($this->oneToMany[$child], $id)->select();
            }
        }
        return $result;
    }

    public function add($data)
    {
        if (empty($data)) {
            return returnJson(602, 400, '添加参数不能为空');
        }

        $data['create_user'] = session('id');
        $data['modify_user'] = session('id');

        $this->startTrans();
        try {
            //添加主表
            $result = $this->validate(true)->allowField(true)->save($data);
            if ($result == false)
                return returnJson(603, 400, $this->getError());
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            return returnJson(603, 400, $e->getMessage());
        }  catch (\Error $e) {
            $this->rollback();
            return returnJson(603, 400, $e->getMessage());
        }
        return returnJson(702, 200, $this->toArray());
    }

    public function renew($data) {
        if (empty($data['id'])) {
            return returnJson(605, 400, '更新缺少主键参数');
        }

        $data['modify_user'] = session('id');
        $this->startTrans();
        try {
            $result = $this->allowField($this->upallow)->validate($this->name.'.update')->isUpdate(true)->save($data);
            if ($result === false) {
                return returnJson(606, 400, $this->getError());
            }
            foreach (array_keys($data) as $item) {
                if (in_array($item, array_keys($this->manyToMany))) {
                    $tmparr = explode(',', $this->manyToMany[$item]);
                    $this->table($tmparr[0])->where($tmparr[1], $data['id'])->delete();
                    $tmparr = explode(',', $data[$item]);
                    $tmparr = array_unique($tmparr);
                    $tmparr = array_filter($tmparr);
                    $this->$item()->savaAll($tmparr);
                }
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return returnJson(606, 400, $e->getMessage());
        }
        return returnJson(704, 200, '更新成功');
    }

    public function del($data, $softdel = true)
    {
        if (empty($data['ids'])) {
            return returnJson(604, 400, '缺少删除参数');
        }
        if ($softdel) {

            $this->where('id', 'in', $data['ids'])->update([
                'isdel' => 1,
                'modify_user' => session('id'),
                'modify_time' => date('Y-m-d H:i:s', strtotime('now'))
            ]);
        } else {
            $this->startTrans();
            try {
                foreach ($this->oneToMany as $k => $v) {
                    $this->table($k)->where($v, 'in', $data['ids'])->delete();
                }
                foreach ($this->manyToMany as $item) {
                    $tmparr = explode(',', $item);
                    $this->table($tmparr[0])->where($tmparr[1], 'in', $data['ids'])->delete();
                }
                $this->where('id', 'in', $data['ids'])->delete();
                $this->commit();
            } catch (Exception $e) {
                $this->rollback();
                return returnJson(601, 400, $e->getMessage());
            }
        }
        return returnJson(703, 200, '删除成功');
    }
}