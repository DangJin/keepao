<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/1/23
 * Time: 下午3:38
 */

namespace app\index\model;


use think\Db;
use think\Exception;
use think\exception\ThrowableError;
use think\migration\db\Column;
use think\Model;

class Common extends Model
{
    protected $pk = 'id';

    protected $manyToMany = '';

    //[表中字段 => 对应表名]
    protected $parent = '';

    //[对应表中字段 => 对应表名]
    protected $oneToMany = '';

    /**
     * Function: select
     * Description: $daat['and'] = 1 and查询,
     *              $data['all'] = 1 查询全部
     * Author  : wry
     * DateTime: 18/1/23 下午6:14
     */
    public function select($data, $page = 1, $limit = 10, $all = '')
    {
        $result = $this->order('sort desc');

        //多条件查询
        $map = [];
        if (isset($data['search']) && !empty($data['search'])) {
            $condi = ['like', '%'.$data['search'].'%'];
            if (isset($data['searchForField']) && !empty($data['searchForField'])) {
                $tmp = explode(',', $data['searchForField']);
                $tmp = array_unique($tmp);
                $tmp = array_filter($tmp);
                foreach ($tmp as $item) {
                    $map[$item] = $condi;
                }
                $this->where(function ($query) use ($map) {
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
            if (isset($data['and']) && $data['and'] == 1)
                $result = $result->where(function ($query) use ($map) {
                    $query->where($map);
                });
            else
                $result = $result->whereOr(function ($query) use ($map) {
                    $query->whereOr($map);
                });
        }

        $result = $result->where('isdel', '<>', 1);

        try {
            //查询全部数据
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
            foreach ($result['data'] as &$item) {
                foreach ($this->parent as $k => $v) {
                    $item[$k] = $this->table($v)->where('id', $item[$k])->where('isdel', '<>', 1)->find();
                }
            }

            return returnJson(701, 200, $result);
        } catch (Exception $e) {
            return returnJson(601,400, $e->getMessage());
        }
    }

    public function add($data)
    {
        if (empty($data)) {
            return returnJson(602, 400, '添加参数不能为空');
        }

        $this->startTrans();
        try {
            //添加主表
            $result = $this->validate(true)->allowField(true)->save($data);
            if ($result == false)
                return returnJson(603, 400, $this->getError());
            //添加关联中间表
            if (!empty($this->manyToMany)) {
                foreach (array_keys($this->manyToMany) as $c) {
                    if (in_array($c, array_keys($data))) {
                        $tmpArr = explode(',', $data[$c]);
                        $tmpArr = array_unique($tmpArr);
                        $tmpArr = array_filter($tmpArr);
                        $this->$c()->saveAll($tmpArr);
                    }
                }
            }
            $this->commit();
            return returnJson(702, 200, $this->toArray());
        } catch (\Exception $e) {
            $this->rollback();
            return returnJson(603, 400, $e->getMessage());
        }  catch (\Error $e) {
            $this->rollback();
            return returnJson(603, 400, $e->getMessage());
        }
    }

    public function renew($data) {
        if (!isset($data['id']) && empty($data['id'])) {
            return returnJson(605, 400, '更新缺少主键参数');
        }

        $this->startTrans();
        try {
            $this->allowField(true)->validate($this->name.'.update')->save($data);
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
        } catch (Exception $e) {
            $this->rollback();
            return returnJson(606, 400, $e->getMessage());
        }

    }

    public function del($data, $softdel = true)
    {
        if (!isset($data['ids']) && empty($data['ids'])) {
            return returnJson(604, 400, '缺少删除参数');
        }
        if ($softdel) {
            $this->where('id', 'in', $data['ids'])->update(['isdel' => 1]);
        } else {
            $this->startTrans();
            try {
                foreach ($this->oneToMany as $k => $v) {
                    $this->table($v)->where($k, 'in', $data['ids'])->delete();
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
