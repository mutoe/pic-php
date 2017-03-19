<?php
namespace app\index\controller;

use think\Controller;

class Auth extends Controller {

    public function _initialize()
    {
        // 如果已经登陆并且不是请求注销
        if (!request()->isAjax() && is_login() && request()->path() != "auth/logout") {
            // TODO: 跳转至个人中心
            $this->redirect('index/index');
        }
    }

    public function signin()
    {
        return $this->fetch();
    }

    public function checkSignin()
    {
        return json_encode(input('post.'));
    }

    /**
     * 注册页面
     * @author 杨栋森 mutoe@foxmail.com at 2017-01-17
     */
    public function register()
    {
        return $this->fetch();
    }

    public function create()
    {

    }

    public function save()
    {

    }

}