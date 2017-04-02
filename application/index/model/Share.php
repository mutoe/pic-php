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
    public static function getShare($share_id = 0, $force = false) {
        if($force) {
            $data = Share::where(['share_id' => $share_id])->find();
        } else {
            $data = Share::where(['share_id' => $share_id, 'status' => 1])->find();
        }
        return $data;
    }

    public static function getShareList($category = 1, $limit = 6, $orderby = 'create_time') {
        switch ($orderby) {
            case 'create_time':
                $order = 'create_time desc';
                break;
            case 'click':
                $order = 'click';
                break;
            default:
                $order = 'share_id desc';
                break;
        }
        $filter = [
            'cate_id' => $category,
            'status' => ['>=', 1],
        ];
        $data = Share::with('profile')->where($filter)->limit($limit)->order($order)->select();
        return $data;
    }

    /**
     * 获取话题
     */
    public function getTopit($share_id = 0) {
        return $this -> where(['share_id' => $share_id]) -> find();
    }

    /**
     * 数据转换
     * @author 杨栋森 mutoe@foxmail.com at 2017-03-30
     */
    /*
    public function migration()
    {
        if (!config('app_debug')) {
            throw new Exception("非法请求 !", 1);
        }
        if ($this->profile) {
            return true;
        }
        $data['click']          = $this->click;
        $data['star']           = $this->star;
        $data['comments_count'] = $this->total_comments;

        $result = $this->profile()->save($data);
        if (!$result) {
            return $this->error($this->getError());
        }
        return $result;
    }
    */

}
