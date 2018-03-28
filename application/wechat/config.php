<?php
//配置文件
return [
    'wxconfig' => [
        'app_id'        => 'wx57d618b8d33729e0',
        'secret'        => '12ae9280e824131b08666a3b305e0ef9',
        'aes_key'       => '9oPJDvazmplbq2qraYBSjms2dBUU4F6Z48JBmngKwCc', // 可选
        'token'         => 'keepao',
        'response_type' => 'array',
        'log'           => [
            'level'      => 'debug',
            'permission' => 0777,
            'file'       => '/home/wwwroot/api.keepao.com/runtime/log/easywechat.log',
        ],
        'oauth'         => [
            'scopes'   => ['snsapi_userinfo'],
            'callback' => 'weixin/callback',
        ],
    ],
];