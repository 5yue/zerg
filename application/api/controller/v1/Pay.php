<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePostiveInt;
use app\api\service\Pay as PayService;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only'=>'getPreOrder']
    ];

    // 查詢預訂單，此訂單是api向微信獲取的微信端訂單
    public function getPreOrder($id='')
    {
        (new IDMustBePostiveInt())->goCheck();
        $pay = new PayService($id);
        $pay->pay();
    }
}