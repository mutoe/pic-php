<?php
namespace app\index\controller;
use think\Controller;

class Api extends Controller {

    public function tylogin() {

        $url = 'https://weixin.cqjtu.edu.cn/authorize?';
        $data['client_id'] = C('tyconnect.client_id');
        $data['redirect_uri'] = C('tyconnect.redirect_uri');
        $data['response_type'] = 'code';
        $data['scope'] = 'basic';
        $url .= http_build_query($data);
        redirect($url);
    }


    public function tyconnect() {
        if(I('get.error') == 'access_denied') {
            return $this -> error('你成功的拒绝了我', U('index/index'));
        } else {
            $code = I('get.code');
            $url = 'https://weixin.cqjtu.edu.cn/access_token';
            $data['client_id'] = C('tyconnect.client_id');
            $data['client_secret'] = C('tyconnect.client_secret');
            $data['redirect_uri'] = C('tyconnect.redirect_uri');
            $data['grant_type'] = 'authorization_code';
            $data['code'] = $code;
            $result = http($url,$data);
            $result = json_decode($result);
            if($token = $result -> access_token) {
                $user = http('https://weixin.cqjtu.edu.cn/user?access_token='.$token);
                $user = json_decode($user);
                $user = (array)$user;
                session('realname', $user['realname']);
                session('ecardno', $user['ecardno']);

                //A('Index') -> checkRegister();
                return $this -> success('身份验证成功,正在返回...', U('index/index'));
            } elseif($result -> error) {
                return $this -> error($result -> error_description, U('index/index'));
            }
        }
    }

}