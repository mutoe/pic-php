<?php
namespace app\index\model;

use think\Model;

class UserOauth extends Model
{

    /**
     * 用户 Oauth 授权信息表
     *
     * user_id      int         用户id
     * realname     varchar     真实姓名
     * ecardno      bigint      一卡通号码
     */

    public function user()
    {
        return $this->belongsTo('user')->field('nickname');
    }

}
