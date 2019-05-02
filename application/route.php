<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//Route::rule('路由表達式','路由地址','請求類型','路由類型（數組）','變量規則（數組）');

//請求類型：GET、post、delete、put、*（默認的請求方法，任意一種的請求方法）

//Route::rule('hello','sample/Test/hello','GET',['https'=>false]);
//Route::rule('hello','sample/Test/hello','GET|POST',['https'=>false]);//及支持get有支持post
//Route::get('hello','sample/Test/hello');
//Route::post();
//Route::any();//指任意一種請求方式
//傳遞參數
//Route::get('hello/:id','sample/Test/hello');
//Route::post('hello/:id','sample/Test/hello');
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');

Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');

Route::get('api/:version/product/recent','api/:version.Product/getRecent');
Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');

Route::get('api/:version/category/all','api/:version.Category/getAllCategories');
Route::post('api/:version/token/user','api/:version.Token/getToken');