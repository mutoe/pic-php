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
        $user_data = model('User')->field('user_id, nickname')->find($user_id);
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
                $order = 'star desc, click desc';
                break;
            default:
                $order = 'create_time desc';
                break;
        }
        // 分享 过滤
        $map['user_id'] = $user_id;
        $map['status'] = ['in', '1, 2, -1'];
        // 分享 获取数据
        $share_data = model('Share')->with('profile')->where($map)->order($order)->paginate(24);
        $share_count = model('Share')->where($map)->count();
        $this->assign('share_data', $share_data);
        $this->assign('share_count', $share_count);

        // 我自己的空间
        $this->assign('myself', true);

        // 渲染页面
        return $this->fetch();
    }

    /**
     * 查看他人空间
     * TODO: 内容还需调整
     * @author 杨栋森 mutoe@foxmail.com at 2017-03-30
     */
    public function read($id)
    {
        // 用户 获取数据
        $user_data = model('User')->where('status', 1)->field('user_id, nickname')->find($id);
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
                $order = 'star desc, click desc';
                break;
            default:
                $order = 'create_time desc';
                break;
        }
        // 分享 过滤
        $map['user_id'] = $id;
        $map['status'] = ['in', '1, 2, -1'];
        // 分享 获取数据
        $share_data = model('Share')->with('profile')->where($map)->order($order)->paginate(24);
        $share_count = model('Share')->where($map)->count();
        $this->assign('share_data', $share_data);
        $this->assign('share_count', $share_count);

        // 别人的空间
        $this->assign('myself', false);

        // 渲染页面
        return $this->fetch('index');
    }

}