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
    protected $message = [
      'id' => 'id必须是正整数'
    ];
}