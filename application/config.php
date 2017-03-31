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

return [

    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,


    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => true,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,


    // 视图输出字符串内容替换
    'view_replace_str'  => [
        '__ROOT__'  => '',
        '__UPLOAD__'=> '/uploads',
        '__STATIC__'=> '/static',
    ],

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],

    // thinkphp 验证码
    'captcha'  => [
        // 验证码字符集合
        //'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        'codeSet'  => '0123456789',
        // 验证码字体大小(px)
        'fontSize' => 18,
        // 是否画混淆曲线
        'useCurve' => false,
         // 验证码图片高度
        'imageH'   => 36,
        // 验证码图片宽度
        'imageW'   => 140,
        // 验证码位数
        'length'   => 4,
        // 验证成功后是否重置
        'reset'    => true
    ],

    // 站点自定义配置
    'pic' => [
        // pjax 加载进度条显示
        'pjax_progress'     => false,
        // pjax 动画 (up, down, none)
        'pjax_animate'      => 'none',
    ],

];
