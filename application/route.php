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
// 收藏分享
Route::get('share/:share_id/star', 'index/share/star');
// 给分享评分
Route::post('share/:share_id/score', 'index/share/score');


// 标签相关 (RESTful 路由)
Route::resource('tag', 'index/tag', [
    'except' => ['create', 'edit'],
]);


// 评论相关
Route::resource('share.comment', 'index/comment', [
    'only' => ['save', 'delete'],
]);


// 分类相关
Route::group('cate', function() {
    Route::get(':cate_id',  'index/cate/read');
});

// 用户相关 (RESTful 路由)
Route::resource('user', 'index/user', [
    'only' => ['index', 'read', 'edit', 'update'],
]);

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
