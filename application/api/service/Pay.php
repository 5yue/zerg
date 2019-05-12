<?php


namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;

// extend/WxPay/WxPay.Api.php
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderID;
    private $orderNO;

    function __construct($orderID)
    {
        if (!$orderID)
        {
            throw new Exception('訂單號不允許為NULL');
        }
        $this->orderID = $orderID;
    }

    public function pay()
    {
        // 訂單號可能根本不存在
        // 訂單確實存在，但是，訂單號和當前用戶是不匹配的
        // 訂單有可能已經被支付過
        // 進行庫存量檢測
        /**
         * 驗證順序：
         * 可能性越大的、對服務器消耗越小的放在最前面
         */
        $this->checkOrderValid();
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass'])
        {
            return $status;
        }
        return $this->makeWxPreOrder($status['orderPrice']);

    }

    private function makeWxPreOrder($totalPrice)
    {
        // openid
        $opendid = Token::getCurrentTokenVar('openid');
        if (!$opendid)
        {
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice*100);
        $wxOrderData->SetBody('粮食商贩');
        $wxOrderData->SetOpenid($opendid);
        $wxOrderData->SetNotify_url('http://qq.com');//接收微信的回调通知
        $wxOrderData->SetProduct_id($this->orderID);

        return $this->getPaySignature($wxOrderData);
    }

    // 向微信获取订单号
    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder('',$wxOrderData);
        // 失败时不会返回result_code
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] !='SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
//            throw new Exception('获取预支付订单失败');
        }
        return null;
    }

    private function checkOrderValid()
    {
        $order = OrderModel::where('id','=',$this->orderID)
            ->find();
        if (!$order)
        {
            throw new OrderException();
        }
        if (!Token::isValidOperate($order->user_id))
        {
            throw new TokenException([
                'msg' => '訂單與用戶不匹配',
                'errorCode' => 10003
            ]);
        }
        if ($order->status != OrderStatusEnum::UNPAID)
        {
            throw new OrderException([
               'msg' => '訂單已支付過啦',
                'errorCode' => 80003,
                'code' => 400

            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }
}