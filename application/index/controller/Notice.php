<?php
namespace app\index\controller;

use app\index\controller\Common;

class Notice extends Common
{

    public function index()
    {
        return view('user/notice');
    }

}
