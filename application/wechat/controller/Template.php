<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 18/3/29
 * Time: 上午12:56
 */

namespace app\wechat\controller;


use think\Request;

class Template extends Common
{
    protected $template_message;

    public function __construct ( Request $request = null )
    {
        parent::__construct($request);
        $this->template_message = $this->app->template_message;
    }
}