<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 14:27
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    //没有使用TP的正则验证，集中在一处方便以后修改
    //不推荐使用正则，因为复用性太差
    //手机号的验证规则
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id',$arrays)|array_key_exists('uid',$arrays))
        {
            //不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }
    protected function isNotEmpty($value, $rule='', $data='', $field=''){
        if (empty($value))
        {
            return false;
        } else {
            return true;
        }
    }
    protected  function isPositiveInteger ($value, $rule='', $data='', $fieId='')
    {
        if (is_numeric($value) && is_int($value+0) && ($value+0)>0){
            return true;
        } else{
            return false;
//            return $fieId.'必须是正整数' ;
        }
    }

    /**
     * 检测所有客户端发来的参数是否符合检验类规则
     * 基类定义了很多自定义验证方法，也可以直接调用
     * @return bool
     * @throws ParameterException
     */
    public function goCheck(){
        //获取http传入的参数
        //对这些参数做校验
        $request = Request::instance();
        $params = $request->param();

        $result = $this->batch()->check($params);//批量处理
        /**
         * 用以下方法时，只能返回一个错误内容
         * $result = $this->check($params);
         *
         */
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