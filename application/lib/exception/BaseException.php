<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 16:06
 */

namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
    //Http状态码 400, 200
    public $code = 400;
    //错误具体信息
    public $msg = 'Parameter ERROR';
    //自定义的错误代码
    public $errorCode = 10000;

    //构造函数
    public function __construct($params = [])
    {
        if (!is_array($params)){
            return ;//这里认为如果不是数组,不改变变量的默认值
//            throw new Exception('参数必须是整数');
        }
        if (array_key_exists('code',$params)){//判断数组中是否有code
            $this->code = $params['code'];
        }
        if (array_key_exists('msg',$params)){//判断数组中是否有code
            $this->msg = $params['msg'];
        }
        if (array_key_exists('errorCode',$params)){//判断数组中是否有code
            $this->errorCode = $params['errorCode'];
        }
    }
}