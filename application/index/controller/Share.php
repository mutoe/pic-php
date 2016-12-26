<?php
namespace app\index\controller;
use think\Controller;

use app\index\model\Cate;

class Share extends Controller {

    public function read($share_id) {
        $share = model('Share');

        $data = $share->getShare($share_id);

        return $this->fetch('detail', ['data' => $data]);
    }

    public function create()
    {
        $category_list = Cate::order('sort desc')->column('cate_name', 'cate_id');
        return $this->fetch('addShare', ['category_list' => $category_list]);
    }

    /**
     * ��ӷ���Ĵ�����
     * ��ע�� �ļ��ƶ�֮ǰ�Ǵ洢�� php ��ʱĿ¼�µ�, ��ȷ����Ŀ¼�� 'public/uploads/' ��Ȩ��Ϊ 777.
     * ��������ͼ�����ߴ��� 480x960px. Ԥ��ͼ���ߴ��� 1440x900px. ԭͼ��������
     * @author �ɭ mutoe@foxmail.com at 2016-12-26
     */
    public function save()
    {
        // �ļ��Ϸ�����֤
        $file = request()->file('image');
        $mine = ['image/jpeg', 'image/gif', 'image/png', 'image/jpg'];
        $info = $file->validate(['size' => 8*1024*1024, 'type' => $mine]);
        if (!$info) {
            return $this->error($info->getError());
        }

        // ��ʼ���ļ�����
        $savepath = 'uploads/'. date('Ym', time()). '/';
        $public_path = ROOT_PATH. 'public/'. $savepath;
        $image = \think\Image::open($file);
        $height = $image->height();
        $width = $image->width();

        // ��ʼ��ģ��
        $share = \think\Loader::model('Share');
        $savedata = input('post.');
        $savedata['width'] = $width;
        $savedata['height'] = $height;
        $savedata['savepath'] = $savepath;
        $result = $share->data($savedata, true);

        // ���ݺϷ�����֤
        $validate = \think\Loader::validate('Share');
        if(!$validate->check($result)) {
            $this->error($validate->getError());
        }

        // ԭͼ����ǰ׺ 'o_'
        $info = $file->rule('uniqid')->move(ROOT_PATH. 'public/'. $savepath);
        $savename = $info->getFilename();
        copy($public_path. $savename, $public_path. 'o_'. $savename);

        // ��������ͼ
        $image->thumb(480, 9999);
        // ��ͼ��ȡ���� 960px
        if ($height > 3 * $width) {
            $image->crop(480, 960);
        }
        $image->save($public_path. 't_'. $savename);

        // ����Ԥ��ͼ
        $image = \think\Image::open($file);
        $image->thumb(1440, 900)->save($public_path. $savename);

        // ���±�������
        $share->savename = $savename;
        $result = $share->save();
        if (!$result) {
            return $this->error($share->getError());
        }

        // ���ݴ����ɹ���ɾ����ʱ�ļ�
        @unlink($info->getInfo('tmp_name'));
        return $this->redirect(url('/share/'. $share->share_id));
    }

}