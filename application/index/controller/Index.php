<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller {

    public function index() {

        $data = D('Share') -> getDataList('create_time');
        $this -> assign('data', json_encode($data));

        $topic = D('Share') -> getTopit(275);
        $this -> assign('topic', $topic);


        return $this -> fetch();
    }

    public function test() {

        $pic = M('share');
        $data = $pic -> limit(30) -> order('create_time desc') -> field('savename,savepath') -> select();
        $this -> assign('data', json_encode($data));

        return $this -> fetch();
    }

    public function getData($type = 'new', $page = 1) {
        $pic = M('share');
        $data = $pic -> limit(30*($page - 1), 30*$page) -> order('create_time desc') -> select();
        //$data = $pic -> limit(1) -> order('create_time desc') -> select();

        return json_encode($data);
    }

}