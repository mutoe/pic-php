<?php
namespace app\index\controller;
use think\Controller;
use think\Config;

use app\index\controller\Auth;

class Api extends Controller {

    /**
     * 重定向至天佑互联
     */
    public function tylogin() {

        $url = 'https://weixin.cqjtu.edu.cn/authorize?';
        $data['client_id'] = Config::get('tyconnect.client_id');
        $data['redirect_uri'] = Config::get('tyconnect.redirect_uri');
        $data['response_type'] = 'code';
        $data['scope'] = 'basic';
        $url .= http_build_query($data);
        return redirect($url);
    }

    /**
     * 天佑互联回调
     */
    public function tyconnect() {

        if(input('get.error') == 'access_denied') {
            return $this->error('你成功的拒绝了我 QwQ');
        }
        $code = input('get.code');

        // 拼装回调请求
        $url = 'https://weixin.cqjtu.edu.cn/access_token';
        $data['client_id'] = Config::get('tyconnect.client_id');
        $data['client_secret'] = Config::get('tyconnect.client_secret');
        $data['redirect_uri'] = Config::get('tyconnect.redirect_uri');
        $data['grant_type'] = 'authorization_code';
        $data['code'] = $code;

        // 请求 access_token
        $result = http($url,$data);
        $result = json_decode($result);
        if(!$result->access_token) {
            return $this->error($result->error_description);
        }
        $token = $result->access_token;
        $user = http('https://weixin.cqjtu.edu.cn/user?access_token='.$token);
        $user = json_decode($user);

        // 登录成功, 写入信息
        $oauth = [
            'realname'  => $user->realname,
            'ecardno'   => $user->ecardno,
        ];
        session('oauth', $oauth);

        // 生成一个安全的 ecardno 供 cookie 使用
        $cookie_ecardno = substr($user->ecardno, 0, 4);
        $cookie_ecardno .= "****";
        $cookie_ecardno .= substr($user->ecardno, 8);
        cookie('ecardno', $cookie_ecardno);

        // 关闭窗口
        echo "<script>window.close()</script>";
        return $this->success();
    }

    /**
     * 注销认证状态
     */
    public function tylogout()
    {
        session('oauth', null);
        cookie('ecardno', null);
        return $this->success('撤销认证成功!', null, '', 1);
    }

}