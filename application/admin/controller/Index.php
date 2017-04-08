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

    }

    public function index()
    {
        return view();
    }

}
