<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 16:06
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    //Http状态码 400, 200
    public $code = 400;
    //错误具体信息
    public $msg = 'Parameter ERROR';
    //自定义的错误代码
    public $errorCode = 10000;
}