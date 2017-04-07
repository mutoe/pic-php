<?php
namespace app\index\controller;

use think\Controller;

class Common extends Controller
{

    protected $user_id;

    public function __construct()
    {
        parent::__construct();
        $this->user_id = auth_status('user_id');
    }

    /**
     * 根据字符串构造出 order() 方法的参数
     * @author 杨栋森 mutoe@foxmail.com at 2017-04-03
     */
    public function constructSortAttr($order = '')
    {
        switch ($order) {
            case 'new':
                $order = 'create_time desc';
                break;
            case 'click':
                $order = 'click desc';
                break;
            case 'score':
                $order = 'score desc';
                break;
            case 'star':
                $order = 'star desc, click desc';
                break;
            default:
                $order = 'create_time desc';
                break;
        }
        return $order;
    }

}
