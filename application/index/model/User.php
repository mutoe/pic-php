<?php
namespace app\index\model;

use think\Model;

class User extends Model
{

    /**
     * 用户表
     *
     * user_id      int         用户id
     * email        varchar     email地址
     * nickname     varchar     昵称
     * password     varchar     密码(hash)
     * remember_token           自动登录口令
     * group_id     tinyint     用户组
     * create_time  bigint      创建时间
     * lastlogin_time  bigint   上次登录时间
     * lastlogin_ip varchar     上次登录ip
     * login_times  mediumint   登录次数
     * modify_nickname_time     上次修改用户名时间
     * status       tinyint     用户状态
     */

    protected $auto     = [];
    protected $insert   = ['status' => 1, 'group_id' => 6, 'create_time'];
    protected $update   = ['lastlogin_time', 'lastlogin_ip'];

    /**
     * 关联用户资料
     */
    public function profile()
    {
        return $this->hasOne('userProfile');
    }

    public function oauth()
    {
        return $this->hasOne('oauth');
    }

    /**
     * 修改器
     */

    public function setCreateTimeAttr()
    {
        return time();
    }

    public function setLastloginTimeAttr()
    {
        return time();
    }

    public function setLastloginIpAttr()
    {
        return request()->ip();
    }

    /**
     * 获取器
     */

    public function getStatusAttr($value)
    {
        $status = [
            1   => '身份未验证',
            2   => '已验证',
            0   => '用户被删除',
            -1  => '禁止登陆',
        ];
        return $status[$value];
    }

}
