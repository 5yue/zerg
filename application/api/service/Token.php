<?php


namespace app\api\service;

use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken(){
        //32个字符组成一组随机字符串
        $randChars = getRandChar(32);
        //用三组字符串，进行md5加密
        $timestamp = time();//没有使用老师的$_SERVER['REQUEST_TIME'];
        //salt 盐（配置到配置文件中）
        $salt = config('secure.token_salt');

        return md5($randChars.$timestamp.$salt);
    }

    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);
        if (!$vars){
            throw new TokenException();
        } else {
            if (!is_array($vars))
            {
                $vars = json_decode($vars,true);
            }
            if (array_key_exists($key, $vars))
            {
                return $vars[$key];
            } else {
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }

    public static function getCurrentUID()
    {   //Token
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    // 用户和cms管理员都可以访问的权限
    public static function needPrimaryScope(){
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= ScopeEnum::User){
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }
    // 只有用户才能访问的接口权限
    public static function needExclusiveScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope == ScopeEnum::User){
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    // 是否是一個合法的操作
    // 比對客戶端傳回的id，和通過令牌去緩存裡找到的id是否相同
    public static function isValidOperate($checkedUID)
    {
        if (!$checkedUID)
        {
            throw new Exception('檢測UID時必須傳入一個被檢測的UID');
        }
        $currentOperateUID = self::getCurrentUID();
        if ($currentOperateUID == $checkedUID)
        {
            return true;
        }
        return false;
    }
}