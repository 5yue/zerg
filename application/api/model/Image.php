<?php

namespace app\api\model;

class Image extends BaseModel
{
    protected $hidden = ['id','from','delete_time','update_time'];
    //其中get_Attr是一个固定模式，"_"这里添加字段名
    public function getUrlAttr($value,$data){
        return $this->prefixImgUrl($value,$data);
    }
}
