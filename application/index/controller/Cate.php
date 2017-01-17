<?php
namespace app\index\controller;

use think\Controller;

class Cate extends Controller {

    public function read($cate_id, $page = 1) {
        $share = model('Share');
        $cate  = model('Cate');

        $data = $share->where(['cate_id' => $cate_id, 'status' => ['gt', 0]])->paginate(24);

        $cate_data = $cate->where('cate_id', $cate_id)->find();

        if(!\think\Cache::get('cate_click_list')) {
            controller('Share')->refresh_cache();
        }
        $cate_click_list = \think\Cache::get('cate_click_list');

        return $this->fetch('detail', [
            'list' => $data,
            'cate_info' => $cate_data,
            'cate_click' => $cate_click_list[$cate_id],
        ]);
    }

}