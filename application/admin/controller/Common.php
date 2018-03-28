<?php
/**
 * Created by PhpStorm.
 * User: wry
 * Date: 18/3/28
 * Time: 下午1:30
 */

namespace app\admin\controller;


use think\Controller;
use think\Request;

class Common extends Controller
{
    protected $model = '';

    protected $data;

    public function __construct(Request $request = null)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, access-token, refresh-token, Content-Type, Accept, csrf, authKey, sessionId");
        header('Content-Type:text/html; charset=utf-8');

        if (!is_null($request)) {
            $this->data = $request->param();
        }
    }
//
//
//    public function select(Request $request)
//    {
//        $page = $request->has('page', 'param', true) ? $request->param('page') : 1;
//        $limit = $request->has('limit', 'param', true) ? $request->param('limit') : 10;
//        return $this->model->select($request->param(), $page, $limit);
//    }
//
//
//    public function add(Request $request)
//    {
//        if ($request->isPost()) {
//            if (!$request->has('csrf', 'header', true) || $request->header('csrf') != session('csrf')) {
//                return returnJson(600, 400, '表单token验证失败');
//            }
//            session('csrf', md5($_SERVER['REQUEST_TIME_FLOAT']));
//            return $this->model->add($request->param());
//        }
//    }
//
//    public function update(Request $request)
//    {
//        return $this->model->renew($request->param());
//    }
//
//    public function delete(Request $request)
//    {
//        if ($request->has('isdel', 'param', true)) {
//            if ($request->param('isdel') == 1) {
//                return $this->model->del($request->param(), false);
//            }
//        }
//        return $this->model->del($request->param());
//    }
//
//    public function getcsrf()
//    {
//        if (!session('?csrf')) {
//            $csrf = md5($_SERVER['REQUEST_TIME_FLOAT']);
//            session('csrf', $csrf);
//        }
//        return json([
//            'value' => true,
//            'data' => [
//                'message' => '返回csrf',
//                'csrf' => session('csrf')
//            ]
//        ]);
//    }
}