<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
define('LOG_PATH', __DIR__ . '/../log/');//自定义配置log文件的位置
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
/**
 * 开启全局sql日志记录功能，
 * 用于对比学习orm模型，
 * 生产环境下，不要打开，除非需要排查错误
 */
\think\Log::init(
    [
        'type' => 'File',
        'path' => LOG_PATH,
        'level' => ['sql']
    ]
);
