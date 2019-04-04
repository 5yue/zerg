<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 13:37
 */

namespace app\api\validate;


use think\Validate;

class IDMustBePostiveInt extends BaseValidate
{
    protected $rule = [
      'id' => 'require|isPositiveInteger',
//        'num' => 'in:1,2,3'
    ];
    protected  function isPositiveInteger ($value, $rule='', $data='', $fileId='')
    {
      if (is_numeric($value) && is_int($value+0) && ($value+0)>0){
            return true;
      } else{
            return $fileId.'必须是正整数' ;
      }
    }
}