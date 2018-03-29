<?php

namespace app\wechat\controller;

use app\wechat\handler\EventHandler;
use app\wechat\handler\TextHandler;
use EasyWeChat\Kernel\Messages\Message;

class Index extends Common
{
    public function __construct ( Request $request = null )
    {
        parent::__construct($request);
    }

    /**
     * 得到服务器实例，分发消息
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index ()
    {
        $server = $this->app->server;
        $server->push(TextHandler::class, Message::TEXT);
        $server->push(EventHandler::class, Message::EVENT);
        $response = $server->serve();
        $response->send();
        return $response;
    }
}
