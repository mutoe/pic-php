<?php
namespace app\index\controller;

use app\index\controller\Common;

class User extends Common {

    /**
     * 调取bbs用户中心头像
     * $size 取值big:200x250 middle:120x120 small:48x48
     */
    public function avatar($user_id = 39139, $size = 'middle') {
/*
    $url = 'http://bbs.cqjtu.edu.cn/uc_server/avatar.php?uid='.$user_id.'&size='.$size;
    $result = http($url);

    echo $result;exit;*/
        header('Content-type: image/jpeg');
        echo file_get_contents('./static/i/default_avatar.jpg');
   }





}