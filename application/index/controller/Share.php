<?php
namespace app\index\controller;
use think\Controller;

use app\index\model\Cate;

class Share extends Controller {

    public function read($share_id) {
        $share = model('Share');

        $data = $share->getShare($share_id);

        return $this->fetch('detail', ['data' => $data]);
    }

    public function create()
    {
        $category_list = Cate::order('sort desc')->column('cate_name', 'cate_id');
        return $this->fetch('addShare', ['category_list' => $category_list]);
    }

    public function save()
    {
        halt(input('post.'));
    }

}