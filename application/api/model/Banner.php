<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 14:49
 */

namespace app\api\model;

class Banner extends BaseModel
{
//    protected $table = 'category';//当数据库表名与实体类名不相同时的处理情况
    public function items(){//关联函数
        //模型名，外键，需要传入的当前模型主键
        return $this->hasMany('BannerItem','banner_id','id');
    }
    protected $hidden = ['delete_time','update_time'];
    /**
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getBannerByID($id)
    {
        $banner  = self::with(['items','items.img'])
            ->find($id);
        return $banner;
        //TODO: 根据Banner ID号 获取Banner信息
        /**
         * 查询方式：
         * 1、原生sql语句查询
         * 2、通过构造器操作数据库
         * 3、使用模型以及关联模型
         */
//        $result = Db::query('select * from banner_item where banner_id=?',[$id]);
//        return $result;//TP5访问控制器，不能用return直接返回数组。
//        $result = Db::table('banner_item')->where('banner_id','=',$id);//只返回query对象，并实际的数据
//        $result = Db::table('banner_item')->where('banner_id','=',$id)
//            ->select();//find()只能返回一维数组，即一条数据。select()可以返回二维数组，即多条数据
        //闭包
//        $result = Db::table('banner_item')
////            ->fetchSql()//使用这个链式方法，可以返回原生sql语句
//            ->where(function ($query) use ($id){//use ($id)是为了引入参数$id
//                $query->where('banner_id','=',$id);
//            })
//            ->select();
//        return $result;
    }
}