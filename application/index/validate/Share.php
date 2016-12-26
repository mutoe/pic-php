<?php
namespace app\index\validate;

use think\Validate;

class Share extends Validate
{
    protected $rule = [
        'detail'  => 'max:255',
        'cate_id' => 'require',
    ];

}