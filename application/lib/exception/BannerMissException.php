<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 16:10
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code=404;
    public $msg = '请求404不存在';
    public $errorCode = 40000;
}