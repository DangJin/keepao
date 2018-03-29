<?php

namespace app\wxpay\controller;

use EasyWeChat\Factory;
use think\Config;
use think\Controller;
use think\Request;

class Common extends Controller
{
    protected $payment;
    protected $wxconfig;

    public function __construct ( Request $request = null )
    {
        parent::__construct($request);
        Config::load(APP_PATH . 'wxpay/config.php');
        if (Config::has('wxconfig')) {
            $this->config = Config::get('wxconfig');
        }
        $this->payment = Factory::payment($this->wxconfig);
    }
}
