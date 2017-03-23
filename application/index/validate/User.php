<?php

namespace app\index\validate;

use think\Validate;
use think\Db;

/**
 * auth验证器
 */
class User extends Validate
{

    protected $rule = [
        'captcha|验证码'
            => 'require|captcha',
        'email'
            => 'require|email',
        'nickname|昵称'
            => 'require|checkName|unique:user|specialchar|nospace',
        'password|密码'
            => 'require|min:6|max:16',
        'repassword'
            => 'confirm:password|require',
        'oldpassword|旧密码'
            => 'require|min:6|max:16',
    ];

    protected $message = [
        'email.email'           => ':attribute格式错啦',
        'repassword.confirm'    => 'wtf? 两次密码输入不一致啊啊啊',
        'nickname.checkName'    => ':attribute长度只能为4-12个字符或2-12个汉字',
        'nickname.specialchar'  => ':attribute中不能含有特殊字符',
        'nickname.nospace'      => ':attribute中不能含有空格',
    ];

    protected $scene = [
        'register'
            => ['email' => 'require|email|unique:user', 'nickname', 'password',
                'captcha'],
        'signin'
            => ['email', 'password'],
        'modify_password'
            => ['password', 'repassword', 'captcha', 'oldpassword'],
        'modify_nickname'
            => ['nickname'],
    ];

    // 不能有空格
    protected function nospace($value)
    {
        $arr = explode(" ", $value);
        if (count($arr) >= 2) {
            return false;
        }
        return true;

    }

    // 不允许特殊字符
    protected function specialchar($value)
    {
        if (preg_match("/[\'.,:;*?~`!@#$%^&+=)(<{}]|\]|\[|\/|\\\|\"|\|/", $value)) {
            return false;
        }
        return true;

    }

    // 验证用户名 允许4-12字符或2-12汉字
    protected function checkName($value, $rule)
    {
        if (strlen($value) < 4 || mb_strlen($value) > 12) {
            return false;
        }
        return true;
    }

}
