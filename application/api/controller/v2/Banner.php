<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 10:54
 */

namespace app\api\controller\v2;

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
        return 'this is v2 version';
    }
}