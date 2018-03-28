<?php

namespace app\wechat\controller;

use think\Controller;
use think\Request;

class Common extends Controller
{

    protected $wxConfig;
    protected $app;

    public function __construct(\think\Request $request = null)
    {
        parent::__construct($request);
    }
}
