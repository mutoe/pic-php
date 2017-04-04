<?php
namespace app\index\validate;

use think\Validate;

class Share extends Validate
{
    protected $rule = [
        'detail'  => 'max:255',
        'cate_id' => 'require',
        'tags' => 'checkTags'
    ];

    /**
     * 验证标签
     */
    protected function checkTags($value)
    {
        // 没有填写则通过验证
        if ($value == '') {
            return true;
        }

        $tags_array = explode(',', $value);
        foreach ($tags_array as $tag) {
            $tag = trim($tag);
            if (mb_strlen($tag) > 20) {
                $this->message['tags.checkTags'] = '标签 '. $tag .' 超出 20 个字符';
                return false;
            }
        }

        return true;
    }

}
