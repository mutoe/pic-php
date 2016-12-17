<?php
namespace app\index\controller;
use think\Controller;

class Share extends Controller {

    public function detail() {

		$share_id = input('share_id', 0);

		$data = model('share') -> getShare($share_id);
		$this -> assign('data', $data);

		return $this -> fetch();
    }

}