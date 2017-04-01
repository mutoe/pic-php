<?php
namespace app\index\controller;

use app\index\controller\Common;

class Comment extends Common
{

    /**
     * 保存评论
     * @author 杨栋森 mutoe@foxmail.com at 2017-04-01
     */
    public function save($id, $share_id)
    {
        // 过滤评论 去除多余换行符和空字符
        $detail = trim(input('post.detail'));
        $detail = nl2br($detail); // 将换行符转化为<br />
        $detail = explode('<br />', $detail);
        $detail = array_filter($detail, 'trim');
        $detail = implode('<br />', $detail);

        // 长度过滤
        if (strlen($detail) < 4) {
            return $this->error('请至少说两个字吧 !');
        }

        // 20 分钟防刷 TODO: 基于用户等级降低时间
        if (cache('comment_time_'.$share_id)) {
            return $this->error('你在 20 分钟内只能评论一次');
        }
        cache('comment_time_'.$share_id, true, 20 * 60);

        // 保存评论
        $comment = model('Comment');
        $savedata['share_id'] = $share_id;
        $savedata['user_id'] = auth_status('user_id');
        $savedata['detail'] = $detail;
        $result = $comment->save($savedata);
        if (!$result) {
            return $this->error('数据写入出错');
        }

        return $this->success();
    }

}
