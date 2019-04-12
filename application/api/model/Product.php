<?php


namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = [
        'delete_time','main_img_id','pivot','from','category_id',
        'create_time','update_time'
    ];//pivot表示多对多的一个连接表，并不属于product表中的字段
}