<?php
namespace app\index\controller;

use app\index\controller\Common;

class Comment extends Common
{

    /**
     * 保存评论
     * @author 杨栋森 mutoe@foxmail.com at 2017-04-01
     */
    public function save($share_id)
    {
        $data = input('post.detail');
        return $this->success($data);
    }

}
