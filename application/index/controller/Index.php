<?php
namespace app\index\controller;
use think\Controller;

use app\index\model\Share;
use app\index\model\Cate;

class Index extends Controller {

    public function index() {

        $show_cate_in_index = Cate::where(['status' => 1])->order('sort desc')
            ->column('cate_name', 'cate_id');

        foreach ($show_cate_in_index as $cate_id => $cate_name) {
            $data[] = [
                'cate_id' => $cate_id,
                'name' => $cate_name,
                'data' => Share::getShareList($cate_id, 9),
            ];
        }

        return $this->fetch('index', ['index_cate_array' => $data]);
    }

}