<?php
namespace app\index\model;

use think\Model;

class Comment extends Model {

    /**
     * 评论表
     *
     * comment_id       int
     * share_id         int
     * user_id          int         用户id
     * detail           varchar     评论内容
     * create_time      bigint      评论时间
     * star             mediumint   获赞数
     * status           tinyint     状态
     */

    protected $insert = ['create_time', 'star' => 0, 'status' => 1];

    /**
     * 关联用户模型
     */
    public function user()
    {
        return $this->hasOne('User', 'user_id', 'user_id')->field('nickname');
    }

    /**
     * 绑定分享模型
     */
    public function share()
    {
        return $this->belongsTo('Share');
    }
}
