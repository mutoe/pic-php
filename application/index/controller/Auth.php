<?php
namespace app\index\controller;

use app\index\controller\Common;

class Auth extends Common {

    // 登陆后允许访问的方法
    private $allow_path = [
        'auth/signout',
    ];

    public function _initialize()
    {
        // 如果已经登陆并且访问不允许的方法
        if (auth_status() && !in_array(request()->path(), $this->allow_path)) {
            // TODO: 跳转至个人中心
            $this->redirect('/');
        }
    }

    public function signin()
    {
        return $this->fetch();
    }

    /**
     * 检查登陆
     * @author 杨栋森 mutoe@foxmail.com at 2017-03-23
     */
    public function checkSignin()
    {
        // 获取表单数据
        $data = input('post.');
        $remember = isset($data['remember_me']) && $data['remember_me'];

        // 如果通过 Oauth 登陆, 跳转到 authSignin 方法
        if (session('oauth')) {
            return $this->oauthSignin($remember);
        }

        // 验证字段合法性
        $validate = validate('User');
        if (!$validate->scene('signin')->check($data)) {
            return $this->error($validate->getError());
        }

        $user = model('User');

        // 获取用户密码
        $password = $user->where(['email' => $data['email']])->value('password');
        if (!$password) {
            return $this->error("邮箱不存在");
        }

        // 核对密码
        if ($password != password($data['email'] . $data['password'])) {
            return $this->error("密码错了");
        }

        // 执行登陆
        $this->doLogin($data['email'], $remember, 'email');

        return $this->success('登陆成功，即将跳转回首页...', url('index/index'));

    }

    private function oauthSignin($remember_me)
    {
        $userAccount = model('user_account');
        $user_id = $userAccount->where(session('oauth'))->value('user_id');
        if (!$user_id) {
            action('Api/tylogout');
            return $this->error('授权登陆失败: 授权信息有误, 请重新授权');
        }

        $result = $this->doSignin($user_id, $remember_me);
        if (!$result) {
            return $this->error('授权登录失败: 没有执行登陆过程');
        }

        $redirect = input('get.from', '/');
        $this->success('授权登陆成功, 正在跳转...', $redirect, '', 1);
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
            return $this->error('自动登录失败: 授权信息已过期或不存在');
        }

        // 获取登陆口令
        $user = model('User');
        $email = cookie('email');
        $token = $user->where(['email' => $email])->value('remember_token');
        if ($token != cookie('remember_token')) {
            return $this->error('自动登录失败: 授权信息错误');
        }

        // 验证通过 开始登陆
        $result = $this->doSignin($email, true, 'email');
        if (!$result) {
            return $this->error('自动登录失败: 执行登陆出错');
        }

        return $this->success(auth_status('nickname'));
    }

    /**
     * 执行登陆
     * @author 杨栋森 mutoe@foxmail.com at 2016-07-19
     *
     * 含通行证类型检测, 默认 'user_id', 还可使用 'email', 共 2 种类型
     *
     * @param  integer $account         待写入用户通行证
     * @param  boolean $remember        是否记住用户
     * @param  boolean $sign_type       通行证类型
     */
    private function doSignin($account, $remember = false, $sign_type = 'user_id')
    {
        // 过滤异常的登录通行证类型
        $allow_type = ['user_id', 'email'];
        $map = [];
        $map[$sign_type] = $account;
        if (!in_array($sign_type, $allow_type)) {
            return false;
        }

        // 获取用户认证数据
        $user = model('user');
        $result1 = $user->where($map)->find();
        $userAccount = model('user_account');
        $result2 = $userAccount->where($map)->find();

        // 登陆状态写入
        $auth = [
            'user_id'   => $result1->user_id,
            'email'     => $result1->email,
            'nickname'  => $result1->nickname,
            'ecardno'   => $result2->ecardno,
            'realname'  => $result2->realname,
        ];
        session('auth', $auth);

        // 登录次数自增, 自动写入上次登陆 time 和 ip
        $user->where($map)->setInc('login_times');

        // 记住登陆状态
        if ($remember) {
            // 生成口令
            $new_string = get_random_string(32);
            $user->save(['remember_token' => $new_string], $map);
            // 保存邮箱一年 用于登陆时自动键入邮箱
            cookie('email', $auth['email'], ['expire' => 3600 * 24 * 30 * 12]);
            // 自动登陆口令保存一个月
            cookie('remember_token', $new_string, ['expire' => 3600 * 24 * 30]);
        }

        return auth_status('user_id');
    }

    /**
     * 注销登录
     */
    public function signout()
    {
        // 清空授权数据
        session('auth', null);

        // 清空 OAuth 数据
        session('oauth', null);
        cookie('ecardno', null);

        // 清除自动登陆口令
        cookie('remember_token', null);

        return $this->success('注销成功, 正在返回...', '/');
    }

}