<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 17:23
 */

namespace app\lib\exception;


class ParameterException  extends BaseException
{
    public $code=400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}