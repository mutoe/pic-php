<?php
namespace app\index\controller;

use app\index\controller\Common;

class Notice extends User
{

    protected $model;   // Ä£ÐÍ

    public function __construct()
    {
        parent::__construct();
        $this->model = model('Notice');
    }

    public function index()
    {
        return view('user/notice');
    }

}
