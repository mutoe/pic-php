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
// $Id$

return [

    'share/:share_id'     => ['index/share/detail', ['method' => 'get'], ['share_id' => '\d+']],

    'user' => [
        'avatar/:user_id/:size'    => ['index/user/avatar', ['method' => 'get'], ['user_id' => '\d+']],
        'avatar/:user_id'          => ['index/user/avatar', ['method' => 'get'], ['user_id' => '\d+']],
    ],

];
