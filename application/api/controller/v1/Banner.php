<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 10:54
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePostiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;

class Banner
{
    /**
     * 获取指定id的banner信息
     * @param $id   banner的id号
     * @url /banner/:id
     * @http GET
     *
     */
    public function getBanner($id) {
        (new IDMustBePostiveInt())->goCheck();
//        模型静态调用
        /**
         *  推荐使用静态调用，模型查询
         *  1、静态调用更加简洁，不需要实例化模型对象
         *  2、模型的本质意义上来说
         */
//        $banner  = BannerModel::with(['items','items.img'])->find($id);//这是使用的module，继承与think\module。或得的是一个对象
//        实例对象调用，先实例化模型
        $banner = BannerModel::getBannerByID($id);//这是自定义方法
//        $banner->hidden(['update_time','delete_time']);//隐藏指定字段,现在模型里已经不能自动显示有这些函数了
//        $banner->visible(['id']);//显示指定字段
//        $data = $banner->toArray();
//        unset($data['delete_time']);
        if(!$banner){
//            throw new Exception('内部错误');
            throw new BannerMissException();
        }
//        $c = config('setting.img_prefix');\\可以获取我们在setting中定义的基路径
        return $banner;
        /**
         * 以下为未开展业务代码时所使用的
         */
//        $banner = new BannerModel();
//        $banner = $banner->get($id);
        //由于表的对应关系，查询到的是banner表
//        $banner = BannerModel::getBannerByID($id);//这是自定义方法

        /*$data=[
          'name' => 'vandor12324234',
          'email' => 'vendorqq.com'
        ];*/
//        $data=[
//            'id' => $id
//        ];
////        $validate = new Validate([
////            'name' => 'require|max:10',//内置验证规则
////            'email' => 'email'
////        ]);//做验证器时需要屏蔽它
////        $validate = new TestValidate();
//        $validate = new IDMustBePostiveInt();
////        $result = $validate->check($data);//用这个方法无法获取所有的错误信息。
//        $result = $validate->batch()
//            ->check($data);//这个可以获取所有的错误信息(批量验证方式)
////        echo $validate->getError();//批量验证时，不能使用echo输出，需要
////        var_dump($validate->getError());//批量验证时，返回的是一个数组。var_dump能显示一个数组的内容
//        if ($result){
//
//        }else{
//
//        }
        //独立验证//tp5的内置规则
        //验证器。独立验证作为它的一个基础。验证器的封装性比独立验证好。
        /**
         * 验证器与独立验证的区别
         * 验证器对独立验证Validate做了一个更好的封装
         */
    }
}