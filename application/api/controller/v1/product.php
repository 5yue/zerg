<?php


namespace app\api\controller\v1;

use app\api\model\Product as ProductModel;

use app\api\validate\Count;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ProductException;

class Product
{
    public function getRecent($count=15){//当客户端传入数据时，按照客户端的要求查询数据。如果没有则是默认的15条数据
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if($products->isEmpty()){//由于返回的数据是数据集了，判断空应该改为使用isEmpty()函数
            throw new ProductException();
        }
//        $collection = collection($products);//数据集
//        $products = $collection->hidden(['summary']);//使用数据集的hidden方法，临时隐藏summary数据
        //由于在datbase.php中配置了数据集的返回模式为collection，故：
        $products = $products->hidden(['summary']);
        return $products;
    }
    
    public function getAllInCategory($id){
        (new IDMustBePostiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }
    public function getOne($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if(!$product){
            throw new ProductException();
        }
        return $product;
    }
}