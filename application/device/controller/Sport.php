<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 18/3/29
 * Time: 上午9:48
 */

namespace app\device\controller;


use think\Log;
use think\Request;

class Sport extends Common
{
    public function __construct ( Request $request = null )
    {
        parent::__construct($request);
    }

    public function uploadSport ( Request $request )
    {
        Log::info("数据上传成功");
        Log::info($request);
        return returnJson(601, 200, 'ok');
    }
}