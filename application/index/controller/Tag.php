<?php
namespace app\index\controller;

use app\index\controller\Common;

class Tag extends Common {

    /**
     * 标签详情页
     * @author 杨栋森 mutoe@foxmail.com at 2017-04-04
     */
    public function read($id) {
        //
        $tag = model('Tag');
        // 分享 排序
        $order = $this->constructSortAttr(input('get.order'));

        // 查找标签
        $tag_data = $tag->find($id);
        if (!$tag_data) {
            return $this->error('不存在的标签');
        }
        $this->assign('tag_data', $tag_data);

        // 生成统计数据
        $share_count = $tag->find($id)->shares()->where('status>0')->count();
        $this->assign('share_count', $share_count);

        // 生成数据列表
        $share_list = $tag->find($id)->shares()->where('status>0')->with('profile,user')
            ->order($order)->paginate(12, false, ['query' => input('get.')]);
        $this->assign('share_list', $share_list);

        return $this->fetch();
    }

    /**
     * 添加一个标签
     * 需要接受 $post['share_id']
     * @author 杨栋森 mutoe@foxmail.com at 2017-04-04
     */
    public function save()
    {
        // 合法性验证
        $share_id = input('post.share_id');
        $share = model('Share')->getShare($share_id);
        if (!$share) {
            return $this->error('非法请求!');
        }

        // 验证数据
        $tag_name = trim(input('post.tag_name'));
        $result = $this->validate(
            ['name' => $tag_name],
            ['name|标签名称' => 'require|max:20']
        );
        if (true !== $result) {
            return $this->error($result);
        }

        // 10 秒防刷
        if (cache('tag_add')) {
            return $this->error('操作太频繁了');
        }
        cache('tag_add', true, 10);

        // TODO: 权限检查 可以设置锁定 自由添加 审核添加
        $user_id = auth_status('user_id');

        $tag = model('Tag')->handleTags($tag_name, $share_id, $user_id);
        if ($tag == 0)
            return $this->error('出了点错误, 稍后再试试吧');

        // 获取回调id
        $tag_id = model('Tag')->where('name', $tag_name)->value('tag_id');

        if ($tag == -1) {
            return $this->success(2, '', $tag_id);
        }

        return $this->success(1, '', $tag_id);
    }

    /**
     * 删除关系
     * @author 杨栋森 mutoe@foxmail.com at 2017-04-04
     */
    public function delete($id)
    {
        $share_id = input('delete.share_id');
        $tag = model('Tag')->find($id);
        $share = model('Share')->find($share_id);
        if (!$tag || !$share) {
            return $this->error('非法请求!');
        }

        // 权限检查
        // TODO: 增加管理员删除的权限
        $user_id = auth_status('user_id');
        if ($user_id != $share->user->user_id) {
            return $this->error('非法请求: 你没有权限这么做');
        }

        $tag->shares()->detach($share_id);
        return $this->success();
    }

}
