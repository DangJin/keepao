<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 18/3/29
 * Time: 上午12:54
 */

namespace app\wechat\controller;


use think\Request;

class JsSdk extends Common
{
    public function __construct ( Request $request = null )
    {
        parent::__construct($request);
    }

    /**
     * 获取jssdk
     * @param Request $request
     * @return \think\response\Json
     */
    public function getWxConfig ( Request $request )
    {
        $jsApiList = $request->param('jsApiList');
        $url = $request->param('url');
        $debug = $request->param('debug');
        if (empty($debug)) {
            $debug = false;
        }
        if (empty($jsApiList) || empty($url)) {
            return returnJson(400, 400, 'jsApiList 不得为空 / NULL');
        }
        $this->app->jssdk->setUrl($url);
        $config = $this->app->jssdk->buildConfig(
            explode(",", $jsApiList),
            $debug
        );
        return returnJson(200, 200, json_decode($config));
    }
}