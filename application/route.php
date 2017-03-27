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
    'cate_id'       => '\d+',
    'share_id'      => '\d+',
]);

// 首页
Route::rule('/', 'index/index/index');

// 首页异步加载
Route::post('index/sync_load', 'index/index/loadCateData');

// 分享相关 (RESTful 路由)
Route::resource('share',    'index/share');

// 分类相关
Route::group('cate', function() {
    Route::get(':cate_id',  'index/cate/read');
});

// 用户相关
Route::group('user', function() {
    Route::get(':user_id',  'index/user/detail');
});

// auth 相关
Route::group('auth', function() {
    Route::get('register',  'index/auth/register');
    Route::post('register', 'index/auth/checkRegister');
    Route::get('auto',      'index/auth/autoSignin');
    Route::get('signout',   'index/auth/signout');
    Route::get('/',         'index/auth/signin');
    Route::post('/',        'index/auth/checkSignin');
});

Route::group('api', function() {
    Route::get('tylogin',   'index/api/tylogin');
    Route::get('tyconnect', 'index/api/tyconnect');
    Route::get('tylogout',  'index/api/tylogout');
});
