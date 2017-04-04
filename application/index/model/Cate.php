<?php
namespace app\index\model;

use think\Model;

class Cate extends Model {

    public function shares()
    {
        return $this->hasMany('share');
    }

}
