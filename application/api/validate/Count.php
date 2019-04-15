<?php


namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule=[//在验证规则的字符串中不能使用空格，有手残也不行
        'count' => 'isPositiveInteger|between:1,15'
    ];
}