<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 14:27
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        //获取http传入的参数
        //对这些参数做校验
        $request = Request::instance();
        $params = $request->param();

        $result = $this->check($params);
        if(!$result){
            $e = new ParameterException([//初始化成员变量，赋值
                //也可以可选参数赋值，即，部分参数的赋值
                'msg' => $this->error,
//                'code'=> 400,
//                'errorCode'=>10002
            ]);//访问外部成员变量赋值时是没有[]的内容的。
            //[]内的方法赋值时，更加符合面向对象的特性
//            $e->msg = $this->error;//访问外部成员变量
            throw $e;
//            $error = $this->error;
//            throw new Exception($error);
        }
        else{
            return true;
        }
    }
}