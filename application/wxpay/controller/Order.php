<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 18/3/29
 * Time: 上午2:04
 */

namespace app\wxpay\controller;


use think\Request;

class Order extends Common
{
    public function __construct ( Request $request = null )
    {
        parent::__construct($request);
    }

    /**
     * 统一下单
     * @param Request $request
     * @return \think\response\Json
     */
    public function orderUnify ( Request $request )
    {
        $notice_url = $request->host() . '/wxpay/notice';
        // 生成订单号
        $out_trade_no = $request->param('out_trade_no');
        $openid = $request->param("openid");
        $total_fee = $request->param("total_fee");
        $body = $request->param("body");
        $data = $this->payment->order->unify(
            [
                'body' => $body,
                'out_trade_no' => $out_trade_no,
                'total_fee' => $total_fee,
                'notify_url' => $notice_url,
                // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => 'JSAPI',
                'openid' => $openid,
            ]
        );
        return returnJson(200, 200, $data);
    }

    /**
     * 微信通知
     */
    public function notice ()
    {
        $response = $this->payment->handlePaidNotify(
            function ( $message, $fail ) {
                $order = $message['out_trade_no'];
                //                array (
                //                    'appid' => 'wx5c1a89ec7428682c',
                //                    'bank_type' => 'CFT',
                //                    'cash_fee' => '1',
                //                    'fee_type' => 'CNY',
                //                    'is_subscribe' => 'Y',
                //                    'mch_id' => '1489798132',
                //                    'nonce_str' => '5a9e026f0efba',
                //                    'openid' => 'or7y7w6mgJIlh4sZpgBYcmnIR5qM',
                //                    'out_trade_no' => '2018030619665',
                //                    'result_code' => 'SUCCESS',
                //                    'return_code' => 'SUCCESS',
                //                    'sign' => '7D296156F5E385F824DBFCB829DB0C7A',
                //                    'time_end' => '20180306105236',
                //                    'total_fee' => '1',
                //                    'trade_type' => 'JSAPI',
                //                    'transaction_id' => '4200000068201803063613566595',
                //                )
                // 订单查询
                $order_res = $this->payment->order->queryByTransactionId(
                    $message['transaction_id']
                );
                if ($order_res['return_code'] === "SUCCESS") {
                    if ($order_res['trade_state'] === "SUCCESS") {
                        // 订单支付成功，写操作日志
                        // order_res 处理
                        //                array (
                        //                    'return_code' => 'SUCCESS',
                        //                    'return_msg' => 'OK',
                        //                    'appid' => 'wx5c1a89ec7428682c',
                        //                    'mch_id' => '1489798132',
                        //                    'nonce_str' => 'P7sMR2KN473kwUDJ',
                        //                    'sign' => '06756C3C36D7D9B2188F0E52E1F4B224',
                        //                    'result_code' => 'SUCCESS',
                        //                    'openid' => 'or7y7w6mgJIlh4sZpgBYcmnIR5qM',
                        //                    'is_subscribe' => 'Y',
                        //                    'trade_type' => 'JSAPI',
                        //                    'bank_type' => 'CFT',
                        //                    'total_fee' => '1',
                        //                    'fee_type' => 'CNY',
                        //                    'transaction_id' => '4200000080201803063667119886',
                        //                    'out_trade_no' => '2018030649910',
                        //                    'attach' => NULL,
                        //                    'time_end' => '20180306124719',
                        //                    'trade_state' => 'SUCCESS',
                        //                    'cash_fee' => '1',
                        //                )
                        return true;// 响应订单处理成功
                    } else {
                        $fail();
                    }
                }
            }
        );
        $response->send();

    }
}