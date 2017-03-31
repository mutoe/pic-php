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

}
