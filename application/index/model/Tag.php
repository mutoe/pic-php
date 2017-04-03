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
        return $this->belongsToMany('Share', $table);
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

}
