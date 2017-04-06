<?php
namespace app\index\model;

use think\Model;

class Notice extends Model {

    /**
     * 通知表
     *
     * message_id       int         通知id
     * type             tinyint     通知类型
     *                              1:公告
     *                              21:作品被收藏
     *                              22:作品被评论
     *                              23:作品得分
     *                              31:作品被贴上标签
     *                              51:用户被关注
     * user_id          int         接收用户id
     * update_time      bigint      创建时间
     * extra            text        附加数据 (json)
     * status           tinyint     通知状态
     *                              1: 未读 0: 已读
     */

    protected $insert   = ['status' => 1];
    protected $auto     = ['update_time', 'extra'];

    /**
     * 修改器
     */
    protected function setUpdateTimeAttr()
    {
        return time();
    }
    protected function setExtraAttr($value)
    {
        return json_encode($value);
    }
    protected function setTypeAttr($value)
    {
        $status = [
            'score'     => 23,
        ];
        return $status[$value];
    }


    /**
     * 获取器
     */
    protected function getExtraAttr($value)
    {
        return (array)json_decode($value);
    }
    protected function getTypeAttr($value)
    {
        $status = [
            23  => 'score',
        ];
        return $status[$value];
    }

    /**
     * 获取通知列表
     * @author 杨栋森 mutoe@foxmail.com at 2017-04-05
     *
     * @param  integer $to          接收方
     * @param  integer $type        通知类型
     * @param  integer $only_unread 只读取未读通知
     * @param  mixed   $result      输出结果
     *                              0: select();
     *                              >0: paginate($result);
     *                              'count': count();
     * @return array                通知列表
     */
    public function getNotices($user_id, $type, $only_unread = 1, $result = 0)
    {
        $map['user_id'] = $user_id;
        $map['status'] = $only_unread ? 1 : ['>=', 0];
        if ($type) $map['type'] = $type;
        $return = $this->where($map)->order('update_time desc');
        switch ($result) {
            case 'count':
                $return = $return->count();
                break;
            case 0:
                $return = $return->select();
                break;
            default:
                $return = $return->paginate($result);
                break;
        }
        return $return;
    }

    /**
     * 生成一个通知
     * @author 杨栋森 mutoe@foxmail.com at 2017-04-07
     *
     * @param  integer  $type       通知类型
     * @param  integer  $user_id    接收方user_id
     * @param  array    $extra      附加数据
     */
    public function setNotice($type, $user_id, $extra = [], $notice_id = 0)
    {
        $savedata['type'] = $type;
        $savedata['user_id'] = $user_id;
        $savedata['extra'] = $extra;
        if ($notice_id) $savedata['notice_id'] = $notice_id;
        $result = $this->isUpdate($notice_id)->save($savedata);
        if (!$result) {
            return $this->getError();
        }
        return $result;
    }

}
