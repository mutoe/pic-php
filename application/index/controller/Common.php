<?php
namespace app\index\controller;

use think\Controller;

class Common extends Controller
{

    public function refreshCaptcha()
    {
        return captcha_src();
    }

}
