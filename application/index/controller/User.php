<?php
namespace app\index\controller;

use app\index\controller\Common;

class User extends Common {

    /**
     * 个人空间
     */
    public function index()
    {
        // 用户 获取数据
        $user_id = auth_status('user_id');
        $user_data = model('User')->find($user_id);
        $this->assign('user_data', $user_data);

        // 分享 排序
        $order = input('order');
        switch ($order) {
            case 'new':
                $order = 'create_time desc';
                break;
            case 'click':
                $order = 'click desc';
                break;
            case 'star':
                $order = 'be_like desc, click desc';
                break;
            default:
                $order = 'create_time desc';
                break;
        }
        // 分享 过滤
        $map['user_id'] = $user_id;
        $map['status'] = ['in', '1, 2, -1'];
        // 分享 获取数据
        $share_data = model('Share')->where($map)->order($order)->paginate(24);
        $this->assign('share_data', $share_data);

        // 渲染页面
        return $this->fetch();
    }

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