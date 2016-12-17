<?php
namespace app\index\model;

use think\Model;

class Share extends Model {

    public function getShare($share_id = 0, $force = false) {
        if($force) {
            $data = $this -> where(['share_id' => $share_id]) -> find();
        } else {
            $data = $this -> where(['share_id' => $share_id, 'status' => 1]) -> find();
        }
    	return $data;
    }

    /**
     * 获取图片信息数据
     */
    public function getDataList($orderby = 'create_time', $limit = 50) {
        switch ($orderby) {
        	case 'create_time':
        		$order = 'create_time desc';
        		break;
            case 'click':
                $order = 'click';
                break;
        	default:
                $order = 'create_time desc';
        		break;
        }
    	$data = $this -> order($order) -> limit($limit) -> where('status>=1') -> select();
        return $data;
    }

    /**
     * 获取话题
     */
    public function getTopit($share_id = 0) {
        return $this -> where(['share_id' => $share_id]) -> find();
    }

}