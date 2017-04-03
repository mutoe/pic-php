<?php
namespace app\index\model;

use think\Model;

class Share extends Model {

    /**
     * 用户表
     *
     * share_id         int
     * user_id          int         用户id
     * cate_id          tinyint     分类id
     * star             mediumint   获赞数
     * click            mediumint   浏览量
     * score            int         评分
     * score_count      mediumint   评分次数
     * status           tinyint     状态
     */

    protected $insert = [
        'status'        => 1,
        'user_id',
        'star'          => 0,
        'click'         => 0,
        'score'         => 0,
        'score_count'   => 0,
    ];
    protected $update = [];

    /**
     * 关联用户模型
     */
    public function user()
    {
        return $this->hasOne('User', 'user_id', 'user_id')->field('nickname');
    }

    /**
     * 关联分类模型
     */
    public function cate()
    {
        return $this->hasOne('Cate', 'cate_id', 'cate_id');
    }

    /**
     * 关联分享档案模型
     */
    public function profile()
    {
        return $this->hasOne('ShareProfile')->setEagerlyType(0);
    }

    /**
     * 关联一对多评论模型
     */
    public function comments()
    {
        return $this->hasMany('Comment');
    }

    /**
     * 分享表 多对多关联
     */
    public function tags()
    {
        // TODO: https://github.com/top-think/think/issues/641
        $table = config('database.prefix') . 'share_tag_relation';
        return $this->belongsToMany('Tag', $table);
    }

    /**
     * 修改器
     */
    protected function setUserIdAttr()
    {
        return auth_status('user_id');
    }

    /**
     * 根据 share_id 获取详情
     * @author 杨栋森 mutoe@foxmail.com at 2016-12-24
     *
     * @param  integer $share_id
     * @param  boolean $force    是否获取所有分享(默认获取审核通过的)
     */
    public function getShare($share_id = 0, $force = false) {
        if($force) {
            $data = $this->find($share_id);
        } else {
            $data = $this->where('status>=1')->find($share_id);
        }
        return $data;
    }

    public function getShareList($cate_id = 1, $limit = 6, $order = '') {
        $order = action('Common/constructSortAttr', ['order' => $order]);
        $filter = [
            'cate_id' => $cate_id,
            'status' => ['>=', 1],
        ];
        $data = $this->with('profile')->where($filter)->limit($limit)->order($order)->select();
        return $data;
    }

}
