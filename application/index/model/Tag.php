<?php
namespace app\index\model;

use think\Model;

class Tag extends Model {

    public function getTagname($tag_id = 0, $force = false) {
        if($force) {
            $data = $this -> where(['tag_id' => $share_id, 'status' => 1]) -> find();
        } else {
            $data = $this -> where(['tag_id' => $share_id]) -> find();
        }
    	return $data['tag_name'];
    }


}