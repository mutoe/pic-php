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

        // 创建 OAuth 授权数据
        $userAccount = model('user_account');
        $auth_data = [
            'user_id'   => $user->user_id,
            'ecardno'   => session('oauth.ecardno'),
            'realname'  => session('oauth.realname'),
        ];
        $result = $userAccount->data($auth_data)->save();
        if (!$result) {
            return $this->error('授权数据写入出错');
        }

        // 执行登陆动作
        $result = $this->doSignin($user->user_id);
        if (!$result) {
            return $this->error('自动登录失败: 执行登陆出错');
        }

        // 返回来源页
        $redirect = input('param.from', '/');
        return $this->success('登陆成功，正在返回...', $redirect);
    }

    /**
     * 根据 remember_token 自动登录
     * @author 杨栋森 mutoe@foxmail.com at 2016-07-19
     *
     * @return ajax_return
     */
    public function autoSignin()
    {
        // 口令过期检测
        if (!cookie('email') || !cookie('remember_token')) {
            return $this->error('自动登录失败: 授权信息过期');
        }

        // 获取登陆口令
        $user = model('User');
        $email = cookie('email');
        $token = $user->where(['email' => $email])->value('remember_token');
        if ($token != cookie('remember_token')) {
            return $this->error('自动登录失败: 授权信息错误');
        }

        // 验证通过 开始登陆
        $result = $this->doSignin($email, true);
        if (!$result) {
            return $this->error('自动登录失败: 执行登陆出错');
        }

        return $this->success(auth_status('nickname'));
    }

    /**
     * 执行登陆
     * @author 杨栋森 mutoe@foxmail.com at 2016-07-19
     *
     * @param  integer $user_id         待写入用户id
     * @param  boolean $remember_me     是否记住用户
     */
    private function doSignin($user_id, $remember_me = false)
    {
        // 获取用户认证数据
        $user = model('user');
        $result1 = $user->where(['user_id' => $user_id])->find();
        $userAccount = model('user_account');
        $result2 = $userAccount->where(['user_id' => $user_id])->find();

        // 登陆状态写入
        $auth = [
            'user_id'   => $user_id,
            'email'     => $result1->email,
            'nickname'  => $result1->nickname,
            'ecardno'   => $result2->ecardno,
            'realname'  => $result2->realname,
        ];
        session('auth', $auth);

        // 登录次数自增, 自动写入上次登陆 time 和 ip
        $user->where(['user_id' => $user_id])->setInc('login_times');

        // 记住登陆状态
        if ($remember_me) {
            // 生成口令
            $new_string = get_random_string(32);
            $user->save(['remember_token' => $new_string],
                ['user_id' => $auth['user_id']]);
            // 保存邮箱一年 用于登陆时自动键入邮箱
            cookie('email', $auth['email'], ['expire' => 3600 * 24 * 30 * 12]);
            // 自动登陆口令保存一个月
            cookie('remember_token', $new_string, ['expire' => 3600 * 24 * 30]);
        }

        return auth_status('user_id');
    }

}