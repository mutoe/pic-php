<?php
namespace app\index\controller;

use think\Controller;

class Auth extends Controller {

    public function _initialize()
    {
        // 如果已经登陆并且不是请求注销
        if (!request()->isAjax() && auth_status() && request()->path() != "auth/logout") {
            // TODO: 跳转至个人中心
            //$this->redirect('index/index');
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

    /**
     * 创建用户
     * @author 杨栋森 mutoe@foxmail.com at 2017-03-23
     */
    public function checkRegister()
    {
        $post = input('post.');

        // 验证字段合法性
        $validate = validate('User');
        if (!$validate->scene('register')->check($post)) {
            return $this->error($validate->getError());
        }

        // 生成登陆密码
        $password = password($post['email'] . $post['password']);

        // 创建用户数据
        $user = model('User');
        $data = [
            'email'     => $post['email'],
            'nickname'  => $post['nickname'],
            'password'  => $password,
        ];
        $result = $user->data($data)->save();
        if (!$result) {
            return $this->error('数据写入出错');
        }
        return $this->error($result);
        // 创建 OAuth 授权数据
        $userAccount = model('user_account');
        $auth_data = [
            'ecardno'   => session('ecardno'),
            'email'     => session('email'),
        ];
        $result = $userAccount->save($auth_data, ['user_id' => $user->user_id]);
        if (!$result) {
            return $this->error('授权数据写入出错');
        }

        // 执行登陆动作
        $auth = [
            'user_id'   => $user->user_id,
            'email'     => $user->email,
            'nickname'  => $user->nickname,
            'ecardno'   => $userAccount->ecardno,
            'realname'  => $userAccount->realname,
        ];
        $result = $this->doSignin($email, true);
        if (!$result) {
            return $this->error('自动登录失败: 执行登陆出错');
        }

        // 返回来源页
        $redirect = input('param.from', '/');
        return $this->success('登陆成功，正在返回...', $redirect);
    }

}