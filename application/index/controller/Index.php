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
            ];
            // 首页渲染 2 个分类数据, 随后进行懒加载
            if ($key < 2) {
                $data[$key]['data'] = model('Share')->getShareList($value['cate_id'], 12);
            }
        }

        $cate_click_list = \think\Cache::get('cate_click_list');

        return $this->fetch('index', [
            'index_cate_array'  => $data,
            'cate_click_list'   => $cate_click_list,
        ]);
    }

    /**
     * 首页分类数据懒加载 只接受 POST 请求 ( 详见 route.php )
     * @author 杨栋森 mutoe@foxmail.com at 2016-12-29
     *
     * @param  integer $cate_id 分类id
     * @return json
     */
    public function loadCateData($cate_id)
    {
        $data = model('Share')->getShareList($cate_id, 12);
        return json_encode($data);
    }

}
