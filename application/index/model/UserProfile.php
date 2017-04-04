<?php
namespace app\index\model;

use think\Model;

class UserProfile extends Model
{

    /**
     * 用户信息表
     *
     * user_id      int         用户id
     * detail       varchar     个性签名
     * is_male      tinyint     性别
     *                          1: 男 0: 女
     * share_count  mediumint   总分享数目
     * album_count  mediumint   总图集数目
     * fans_count   mediumint   粉丝数
     * score        int         总评分
     * score_count  mediumint   评分次数
     */

    protected $insert   = [
        'share_count'   => 0,
        'album_count'   => 0,
        'fans_count'    => 0,
        'score'         => 0,
        'score_count'   => 0,
    ];

    public function user()
    {
        return $this->belongsTo('user')->field('nickname');
    }

    public function getIsMaleAttr($value)
    {
        $status = [1 => '男', 0 => '女'];
        return $status[$value];
    }

}
