<?php
namespace app\index\model;

use think\Model;

class ShareProfile extends Model {

    /**
     * 分享档案表
     *
     * share_id         int
     * detail           varchar     分享详情
     * photo_date       varchar     拍摄日期
     * month            char        拍摄年月
     * create_time      bigint      创建时间
     * update_time      bigint      修改时间
     * savepath         varchar     保存路径
     * savename         varchar     保存名称
     * width            smallint    图像宽度
     * height           smallint    图像高度
     * exif             text        图像 EXIF 数据
     * user_agent       varchar     上传时用户 UA
     */

    protected $auto = ['month','update_time'];
    protected $insert = ['create_time'];

    public function share()
    {
        return $this->belongsTo('share');
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
        return date_create_from_format('Y.n.j', $data['photo_date'])->format('Ym');
    }

}
