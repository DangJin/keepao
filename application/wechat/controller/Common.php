<?php

namespace app\wechat\controller;

use EasyWeChat\Factory;
use think\Config;
use think\Controller;
use think\Request;

class Common extends Controller
{

    protected $wxConfig;
    protected $app;

    public function __construct ( \think\Request $request = null )
    {
        parent::__construct($request);
        Config::load(APP_PATH . 'wechat/config.php');
        if (Config::has('wxconfig')) {
            $this->wxConfig = Config::get('wxConfig');
        }
        $this->app = Factory::officialAccount($this->wxConfig);
    }

}
