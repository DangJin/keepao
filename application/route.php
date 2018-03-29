<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

\think\Route::group(
    'admin', [

    ]
);

\think\Route::group(
    'device', [
        'mac/upload' => ['device/Sport/uploadSport', ['method' => 'POST']]
    ]
);


\think\Route::group(
    'wechat', [
        'init' => ['wechat/Wx/serve', ['method' => 'GET|POST']],
    ]
);

\think\Route::group(
    'wxpay', [
        'init' => ['wxpay/index/index', ['method' => 'GET']],
        'payConfig' => ['wxpay/Jssdk/getJssdk', ['method' => 'POST']],
        'order' => ['wxpay/Order/orderUnify', ['method' => 'GET']],
        'notice' => ['wxpay/Order/notice', ['method' => 'POST ']],
    ]
);