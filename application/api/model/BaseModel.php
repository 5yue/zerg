<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{

    public function prefixImgUrl($value,$data){//读取器
        if($data['from'] == 1) {
            $value = config('setting.img_prefix') . $value;
        }
        return $value;
    }
}
