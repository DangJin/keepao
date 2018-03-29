<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 18/3/29
 * Time: 上午12:59
 */

namespace app\wechat\controller;


use think\Request;

class Menu extends Common
{
    public function __construct ( Request $request = null )
    {
        parent::__construct($request);
    }

    /**
     * 获取当前菜单
     * @param Request $request
     * @return \think\response\Json
     */
    public function currentMenu ( Request $request )
    {
        $menu = $this->app->menu->current();
        if (array_key_exists("errcode", $menu)) {
            if ($menu['errcode'] === 46003) {
                return returnJson(801, 200, ['button' => []]);
            }
        }
        return returnJson(801, 200, $menu);
    }

    /**
     * 添加新菜单
     * @param Request $request
     * @return \think\response\Json
     */
    public function addMenu ( Request $request )
    {
        $menu = $request->param("menus");
        $res = $this->app->menu->create(json_decode($menu, true));
        return returnJson(801, 200, $res);
    }
}