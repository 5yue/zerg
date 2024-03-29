<?php


namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;

class Order
{
    // 订单的商品列表，也就是客户端传递过来的products参数
    protected $oproducts;

    // 真是的商品信息（包括库存量）
    protected $products;

    protected $uid;

    /**
     *
     * 检测库存量：有则创建订单，无则向客户端返回一个order_id=-1
     *
     * @param $uid
     * @param $oproducts
     * @return array
     *
     */
    public function place($uid, $oproducts)
    {
        // oproducts和products作对比
        // products从数据库中查询出来
        $this->oproducts = $oproducts;
        $this->products = $this->getProductsByOrder($oproducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if (!$status['pass'])
        {
            $status['order_id'] = -1;
            return $status;
        }

        //开始创建订单
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;

    }

    private function createOrder($snap)
    {
        /**
         * 開啟事務，
         * 防止出現只保存一個的情況
         * 在本方法中出現的概率很小，不代表其他的api不會出現，有可能只保存了第一個save()，而saveAll()未保存的情況。
         */
        Db::startTrans();//開啟事務
        try {
            $orderNo = $this->makeOrderNo();
            $order = new \app\api\model\Order();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);

            $order->save();

            $orderID = $order->id;
            $create_time = $order->create_time;
            foreach ($this->oproducts as &$p) {
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oproducts);
            Db::commit();//提交事務
            /**
             * 提交事務：
             * 兩個都會執行，要不就兩個都不執行（如第二個無法執行，第一個也會被撤銷）
             */

            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        }
        catch (Exception $ex) {

            Db::rollback();//回滾事務，出現錯誤時，回滾以撤銷之前的執行
            throw $ex;
        }
    }

    // 生成订单号
    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    // 生成订单快照
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => ''
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];

        if (count($this->products) > 1)
        {
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    private function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id','=',$this->uid)
            ->find();
        if (!$userAddress){
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败',
                'errorCode' => 60001,
            ]);
        }
        return $userAddress->toArray();
    }

    /**
     *
     * 用於檢測庫存量
     * @param $orderID
     * 對外的方法
     */
    public function checkOrderStock($orderID)
    {
        $oProducts = OrderProduct::where('order_id','=','orderID')
            ->select();
        $this->oproducts = $oProducts;

        $this->products = $this->getProductsByOrder($oProducts);
        $status = $this->getOrderStatus();
        return $status;
    }

    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];

        foreach ($this->oproducts as $oproduct)
        {
            $pStatus = $this->getProductStatus(
              $oproduct['product_id'],$oproduct['count'],$this->products
            );
            if(!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;
    }

    private function getProductStatus($oPID, $oCount, $products)
    {
        $pIndex = -1;

        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' =>'',
            'totalPrice' => 0
        ];

        for($i=0; $i<count($products); $i++){
            if($oPID == $products[$i]['id']){
                $pIndex = $i;
            }
        }

        if($pIndex == -1){
            //客户端传递的productid有可能根本不存在
            throw new OrderException([
                'msg' => 'id为'.$oPID.'的商品不存在，创建订单失败'
            ]);
        } else {
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $product['price'] * $oCount;

            if($product['stock'] - $oCount >= 0) {
                $pStatus['haveStock'] = true;
            }
        }
        return $pStatus;
    }

    // 根据订单信息，查找真实的商品信息
    private function getProductsByOrder($oProducts)
    {
//        foreach ($oProducts as $oProduct){
//            //循环的查询数据库
//        }
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }
        $products = Product::all($oPIDs)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();
        return $products;
    }
}