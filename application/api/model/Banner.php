<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 14:49
 */

namespace app\api\model;


use think\Db;
use think\Exception;

class Banner
{
    public static function getBannerByID($id)
    {
        //TODO: 根据Banner ID号 获取Banner信息
////        try{
////            1/0;
////        }
////        catch (Exception $e){
////            throw $e;
////        }
//        return 'this is a banner info';
//        return null
//;
        /**
         * 查询方式：
         * 1、原生sql语句查询
         * 2、通过构造器操作数据库
         * 3、使用模型以及关联模型
         */
        $result = Db::query('select * from banner_item where banner_id=?',[$id]);
        return $result;//TP5访问控制器，不能用return直接返回数组。
    }
}