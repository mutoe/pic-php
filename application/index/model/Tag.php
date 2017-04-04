<?php
namespace app\index\model;

use think\Model;

class Tag extends Model {

    /**
     * 标签表
     *
     * tag_id       标签id
     * name         标签名称 最大 20 字符(包括汉字)
     * user_id      创建者id
     * create_time  创建时间
     * status       标签状态
     *              1:正常(default) 2:推荐 0:被禁用的
     */

    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    protected $insert = ['status' => 1, 'user_id'];

    /**
     * 分享表 多对多关联
     */
    public function shares()
    {
        // TODO: https://github.com/top-think/think/issues/641
        $table = config('database.prefix') . 'share_tag_relation';
        return $this->belongsToMany('share', $table);
    }

    public function setUserIdAttr()
    {
        return auth_status('user_id');
    }

    public function getTagname($tag_id = 0, $force = false) {
        if($force) {
            $data = $this -> where(['tag_id' => $share_id, 'status' => 1]) -> find();
        } else {
            $data = $this -> where(['tag_id' => $share_id]) -> find();
        }
    	return $data['tag_name'];
    }

    /**
     * 根据 tags 数组和 share_id 关联数据
     * @author 杨栋森 mutoe@foxmail.com at 2017-04-03
     *
     * @param  array|string     $tags       tags 数组 或 tag_name
     * @param  integer          $share_id
     * @param  integer          $user_id
     * @param  bool             $exist      如果已经存在关系, 是否中断
     */
    public function handleTags($tags, $share_id, $user_id, $exist = false)
    {
        $share = model('Share');

        // 如果接受字符串则转化为数组
        if (!is_array($tags)) $tags = [$tags];

        // 解析数据
        $return = 1;
        foreach ($tags as $tag_name) {
            $tag_name = trim($tag_name);
            // 检查是否已经存在标签
            $find = $this->where('name', $tag_name)->find() ?: ['name' => $tag_name];
            if (isset($find->createTime)) {
                $share_list = $find->shares()->select();
                foreach ($share_list as $s) {
                    if ($s->share_id == $share_id) return -1;
                }
            }
            // 写入关联数据
            $result = $share->find($share_id)->tags()->attach($find, [
                'update_time'   => time(),
                'user_id'       => $user_id
            ]);
            if (!$result) return 0;
        }

        return $return;
    }

}
