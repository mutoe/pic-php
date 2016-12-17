<?php
namespace app\index\controller;
use think\Controller;

class Share extends Controller {

    public function detail() {

		$share_id = I('get.share_id', 0);

		$data = D('share') -> getShare($share_id);
		$this -> assign('data', $data);

		return $this -> fetch();
    }

}