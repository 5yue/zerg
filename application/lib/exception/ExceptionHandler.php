<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/31
 * Time: 16:03
 */

namespace app\lib\exception;


use Exception;
use think\Config;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    public $code;
    public $msg;
    public $errorCode;
    //还需要返回客户端当前请求的URL路径

    public function render(\Exception $e)
    {
//        return json('~~~~~~~~~~~');
        if ($e instanceof BaseException)
        {
            //如果是自定义异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }
        else{
//            $switch = true;
            /**
             * 读取config.php文件下的信息，使用两种方式
             *  Config::get('app_debug')
             * config('app_debug')
             * 配置文件一般只能用于读取，不能用于存储信息
             * 要想记录某一个变量的值，可以存在数据库、Redis缓存、tp5自带缓存、全局变量
             */
//            Config::get('app_debug');
            if(config('app_debug')){//使用开关控制，
                //return default error page
                return parent::render($e);
            }
            else {
                $this->code = 500;
                $this->msg = '服务器内部错误，不想告诉你';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()//获取当前请求路径
        ];
        return json($result, $this->code);
    }
    private function recordErrorLog(Exception $e){
        Log::init([//打开tp5的日志记录功能
           'type'=> 'File',
            'path'=>LOG_PATH,
            'level'=>['error']//只记录异常在error以上的信息
        ]);
        Log::record($e->getMessage(),'error');
    }
}