<?php
namespace app\index\model;

use think\Model;

class Share extends Model {

    protected $auto = ['month','update_time'];
    protected $insert = [
        'status'        => 1,
        'be_like'       => 0,
        'click'         => 0,
        'total_comments'=> 0,
        'create_time',
        'user_id',
    ];
    protected $update = [];

    /**
     * 关联用户模型
     */
    public function profile()
    {
        return $this->hasOne('User', 'user_id', 'user_id')->field('nickname');
    }

    /**
     * 修改器
     */

    protected function setUserIdAttr()
    {
        return auth_status('user_id');
    }

    protected function setCreateTimeAttr()
    {
        return time();
    }

    protected function setUpdateTimeAttr()
    {
        return time();
    }

    protected function setMonthAttr($value, $data)
    {
        $month = explode('-', $data['photo_date']);
        return $month[0].$month[1];
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
        $data = Share::where($filter)->limit($limit)->order($order)->select();
        return $data;
    }

    /**
     * 获取话题
     */
    public function getTopit($share_id = 0) {
        return $this -> where(['share_id' => $share_id]) -> find();
    }

}
