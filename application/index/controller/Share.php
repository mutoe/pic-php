<?php
namespace app\index\controller;

use app\index\controller\Common;
use think\Cache;

class Share extends Common {

    public function read($id) {
        $share = model('Share');

        $data = $share->getShare($id);
        $share->where('share_id', $id)->setInc('click', 1, 60);

        return $this->fetch('detail', ['data' => $data]);
    }

    public function create()
    {
        $category_list = model('Cate')->order('sort desc')->column('cate_name', 'cate_id');
        return $this->fetch('addShare', ['category_list' => $category_list]);
    }

    /**
     * 添加分享的处理方法
     * 请注意 文件移动之前是存储在 php 临时目录下的, 请确保该目录和 'public/uploads/' 的权限为 777.
     * 生成缩略图的最大尺寸是 480x960px. 预览图最大尺寸是 1440x900px. 原图不做处理
     * @author 杨栋森 mutoe@foxmail.com at 2016-12-26
     */
    public function save()
    {
        // 文件合法性验证
        $file = request()->file('image');
        $mine = ['image/jpeg', 'image/gif', 'image/png', 'image/jpg'];
        $info = $file->validate(['size' => 8*1024*1024, 'type' => $mine]);
        if (!$info) {
            return $this->error($info->getError());
        }

        // 初始化文件数据
        $savepath = 'uploads/'. date('Ym', time()). '/';
        $public_path = ROOT_PATH. 'public/'. $savepath;
        $image = \think\Image::open($file);
        $height = $image->height();
        $width = $image->width();

        // 初始化模型
        $share = \think\Loader::model('Share');
        $savedata = input('post.');
        $savedata['width'] = $width;
        $savedata['height'] = $height;
        $savedata['savepath'] = $savepath;
        $result = $share->data($savedata, true);

        // 数据合法性验证
        $validate = \think\Loader::validate('Share');
        if(!$validate->check($result)) {
            $this->error($validate->getError());
        }

        // 原图增加前缀 'o_'
        $info = $file->rule('uniqid')->move(ROOT_PATH. 'public/'. $savepath);
        $savename = $info->getFilename();
        copy($public_path. $savename, $public_path. 'o_'. $savename);

        // 生成缩略图
        $image->thumb(480, 9999);
        // 长图裁取顶部 960px
        if ($height > 3 * $width) {
            $image->crop(480, 960);
        }
        $image->save($public_path. 't_'. $savename);

        // 生成预览图
        $image = \think\Image::open($file);
        $image->thumb(1440, 900)->save($public_path. $savename);

        // 更新保存名称
        $share->savename = $savename;
        $result = $share->save();
        if (!$result) {
            return $this->error($share->getError());
        }

        // 数据创建成功后删除临时文件
        @unlink($info->getInfo('tmp_name'));
        return $this->redirect(url('/share/'. $share->share_id));
    }

    /**
     * 刷新缓存
     * 目前可刷新 "分类点击量"
     * @author 杨栋森 mutoe@foxmail.com at 2016-12-28
     */
    public function refresh_cache()
    {
        $cate = model('Cate');
        $share = model('Share');
        $data = $cate->select();
        $result = array_fill(1, $cate->max('cate_id'), 0);
        foreach ($data as $key => $value) {
            $sharelist = $share->where(['cate_id'=>$value['cate_id']])->column('click');
            $count = 0;
            foreach ($sharelist as $value1) {
                $count += $value1;
            }
            $result[$value['cate_id']] = $count;
        }
        Cache::set('cate_click_list', $result);
    }

}