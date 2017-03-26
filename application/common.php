<?php

use app\index\model\User;

/**
 * md5 密码加密算法
 * 需配合应用设置中 'app_salt' 和 'database.prefix' 加盐
 * @author 杨栋森 mutoe@foxmail.com at 2016-07-14
 *
 * @param  {String} $value 待加密字符串
 * @return {String}        md5摘要后的字符串
 */
function password($value)
{
    $value .= config('database.prefix') . config('app_salt');
    return md5($value);
}

/** 随机生成字符串
 * @author 杨栋森 mutoe@foxmail.com at 2016-07-19
 *
 * @param  integer $length 字符串长度
 * @return string
 */
function get_random_string($length = 32)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
    }

    return $str;
}

/**
 * 认证状态
 * 检测用户是否通过了登陆认证
 * @author 杨栋森 mutoe@foxmail.com at 2017-03-23
 */
function auth_status($info_type = 'user_id')
{
    $auth = session('auth');
    if (!isset($auth['user_id']) || !$auth['user_id']) {
        return false;
    }

    return $auth[$info_type];
}

/**
 * 模拟 http 请求
 */
function http($url, $post = '', $cookie = '', $returnCookie = 0)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_REFERER, $url);
    if ($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    if ($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);
    if ($returnCookie) {
        list($header, $body) = explode("\r\n\r\n", $data, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        $info['cookie'] = substr($matches[1][0], 1);
        $info['content'] = $body;
        return $info;
    } else {
        return $data;
    }
}
