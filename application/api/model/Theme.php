<?php


namespace app\api\model;


class Theme extends BaseModel
{
    public function topicImg()
    {
        //一对一关系是不对等的。
//        $this->hasOne()从表使用hasOne()
        return $this->belongsTo('Image','topic_img_id','id');//主表使用belongsTo
    }
    public function headImg()
    {
        return $this->belongsTo('Image','head_img_id','id');
    }
}