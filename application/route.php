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

Route::pattern([
    'user_id'       => '\d+',
    'share_id'      => '\d+',
]);

// 定义 RESTful 路由
Route::resource('share', 'index/share', ['var', ['share' => 'share_id']]);

Route::group('user', [
    ':user_id'                  => ['index/user/detail',    ['method' => 'GET']],
    'avatar/:user_id/[:size]'   => ['index/user/detail',    ['method' => 'GET']],
]);
