<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 18/3/29
 * Time: 上午9:48
 */

namespace app\device\controller;


use think\Controller;
use think\Request;

class Common extends Controller
{
    public function __construct ( Request $request = null )
    {
        parent::__construct($request);
    }
}