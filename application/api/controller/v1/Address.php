<?php


namespace app\api\controller\v1;


use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use think\Controller;

class Address extends Controller
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress']
    ];
    protected function checkPrimaryScope(){
        $scope = TokenService::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= ScopeEnum::User){
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }
//    protected $beforeActionList = [
//        'first' => ['only' => 'second,third']
//    ];
//
//    protected function first(){
//        echo 'first';
//    }
//    public function second(){
//        echo 'second';
//    }
//    public function third(){
//        echo 'third';
//    }

    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();
        // 根据|Token获取用户的uid
        // 根据uid查找用户数据，判断用户是否存在，如果不存在则抛出异常
        // 获取用户从客户端提交而来的地址信息
        // 根据用户地址信息是否存在，从而判断是添加地址还是更新地址

        $uid = TokenService::getCurrentUID();
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserException();
        }

        $dataArray = $validate->getDataByRule(input('post.'));

        $userAddress = $user->address;
        if (!$userAddress)
        {
            $user->address()->save($dataArray);
        } else {
            $user->address->save($dataArray);
        }
//        return $user;
        return json(new SuccessMessage(),201);
    }
}