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
 * 根据user_id获取nickname
 */
function get_nickname($user_id = 0) {
    if(!$user_id) return null;
    return User::where("user_id=$user_id") -> value('nickname');
}


/**
 * 根据tag_id获取tag_name
 */
function get_tagname($tag_id) {
    return model('tag') -> getTagname($tag_id);
}

/**
 * 渲染tag数组
 * @param {fixed} tag:tag数组或json格式字符串
 * @param {string} renderType:渲染方式，'tag'输出含有i图标的li列表
 * @param {string} addClass:当渲染方式为tag时在li内添加的class
 * @return {string} 输出html字符串
 */
function render_tag($tag, $renderType = 'tag', $addClass = '') {
    // 将json格式转化为php数组
    if(is_string($tag)) {
        $tag = json_decode($tag);
    }
    $result = "";
    if(empty($tag)) return $result;

    switch ($renderType) {
    // 直接输出tag标签
    case 'tag':
        foreach ($tag as $value) {
            if($value == 1) continue;
            $tagname = get_tagname($value);
            $result .= "<li class='$addclass'><a href='". U('index/tag/detail', 'tag_id='.$value) ."'><i class='am-icon am-icon-tag'></i> $tagname</a></li>";
        }
        break;

    default:
        foreach ($tag as $value) {
            $tagname = get_tagname($value);
            $result .= "<u>$tagname</u>&nbsp;";
        }
        break;
    }
    return $result;
}

/**
 * 认证状态
 * 检测用户是否通过了登陆认证
 * @author 杨栋森 mutoe@foxmail.com at 2017-03-23
 */
function auth_status($info_type)
{
    $auth = session('auth');
    if (!$auth['user_id']) {
        return false;
    }

    return $return[$info_type];
}

function http($url, $post = '', $cookie = '', $returnCookie = 0) {
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