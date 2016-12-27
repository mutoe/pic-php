<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller {

    public function index() {

        $show_cate_in_index = model('Cate')->where(['status' => 1])->order('sort desc')->select();
        if(!\think\Cache::get('cate_click_list')) {
            controller('Share')->refresh_cache();
        }
        foreach ($show_cate_in_index as $key => $value) {
            $data[$key] = [
                'cate_id' => $value['cate_id'],
                'name' => $value['cate_name'],
                'description' => $value['description'],
                'total_share' => $value['total_share'],
                'data' => model('Share')->getShareList($value['cate_id'], 9),
            ];
        }
        $cate_click_list = \think\Cache::get('cate_click_list');

        return $this->fetch('index', [
            'index_cate_array'  => $data,
            'cate_click_list'   => $cate_click_list
        ]);
    }

}
