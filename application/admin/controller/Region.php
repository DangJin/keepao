<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/3/28
 * Time: 下午1:05
 */

namespace app\admin\controller;


use think\Controller;
use think\Request;

class Region extends Common
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->model = new \app\admin\model\Region();
    }

    public function select(Request $request)
    {
        $page = empty($this->data['page']) ? 1 : $this->data['page'];
        $limit = empty($this->data['limit']) ? 10 : $this->data['limit'];

        if (!empty($this->data['id'])) {
            return $this->model->getById($this->data['id']);
        } else if (!empty($this->data['parent_code'])) {
            return $this->model->getByParent($this->data['parent_code']);
        } else {
            return $this->model->select();
        }
    }

}