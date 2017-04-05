<?php
namespace app\index\model;

use think\Model;

class Notice extends Model {

    /**
     * 通知表
     *
     * message_id       int         通知id
     * type             tinyint     通知类型
     * to               int         接收对象 (user_id 或 role_id)
     * from             int         发起对象 (share_id 或 user_id)
     * update_time      bigint      创建时间
     * extra            text        附加数据 (json)
     * status           tinyint     通知状态
     *                              1: 未读 0: 已读
     */


    protected $insert   = ['status' => 1];
    protected $auto     = ['update_time'];

    /**
     * 修改器
     * @author 杨栋森 mutoe@foxmail.com at 2017-04-05
     *
     */
    protected function setUpdateTimeAttr()
    {
        return time();
    }

}
