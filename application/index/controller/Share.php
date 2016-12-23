<?php
namespace app\index\controller;
use think\Controller;

class Share extends Controller {

    public function detail() {
        $share = model('Share');

        $share_id = input('share_id', 0);
        $data = $share->getShare($share_id);

        return $this -> fetch('detail', ['data' => $data]);
    }

}