<?php
namespace app\admin\controller;

use \think\Controller;

class Index extends Controller
{

    protected $user_id;

    public function __construct()
    {
        parent::__construct();
        $this->user_id = auth_status('user_id');

        if (!$this->user_id) {
            $this->redirect('/auth');
        }

        $user_data = model('index/User')->find($this->user_id);
        $this->assign('user_data', $user_data);

    }

    public function index()
    {
        return view();
    }

}
