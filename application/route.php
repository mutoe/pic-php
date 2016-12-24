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

Route::group('share', [
    ':share_id'                 => ['index/share/detail',   ['method' => 'GET']],
    'add'                       => ['index/share/addShare', ['method' => 'GET']],
]);

Route::group('user', [
    ':user_id'                  => ['index/user/detail',    ['method' => 'get']],
    'avatar/:user_id/[:size]'   => ['index/user/detail',    ['method' => 'get']],
]);
