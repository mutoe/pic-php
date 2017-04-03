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

}
