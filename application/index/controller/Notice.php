<?php
namespace app\index\controller;

use app\index\controller\Common;

class Notice extends User
{

    protected $model;   // 模型

    public function __construct()
    {
        parent::__construct();
        $this->model = model('Notice');
        $this->assign('unread_notice', null);
    }

    public function index()
    {
        $notice['count']   = $this->model->getNotices($this->user_id, 0, 0, 'count');
        $notice['list']    = $this->model->getNotices($this->user_id, 0, 0, 15);
        $this->assign('notice', $notice);

        // 将所有通知标为已读
        $this->model->setNoticesRead($this->user_id);

        $user_data = model('User')->find($this->user_id);
        $this->assign('user_data', $user_data);

        return view('user/notice');
    }

}
