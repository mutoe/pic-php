<?php
namespace app\index\controller;
use think\Controller;

use app\index\model\Cate;

class Share extends Controller {

    public function detail() {
        $share = model('Share');

        $share_id = input('share_id', 0);
        $data = $share->getShare($share_id);

        return $this -> fetch('detail', ['data' => $data]);
    }

    public function addShare()
    {
        $category_list = Cate::order('sort desc')->column('cate_name', 'cate_id');
        return $this->fetch('addShare', ['category_list' => $category_list]);
    }

}