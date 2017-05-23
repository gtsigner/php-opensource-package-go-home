<?php

namespace Admin\Controller;


use Think\Upload;

class UploadController extends AdminController
{

    public function upload_file($key)
    {
        //上传app文件
        $upConf = C($key);
        if (!$upConf) {
            $this->error("上传失败");
        }
        $upModel = new Upload($upConf);
        $upRet = $upModel->upload();
        if (!$upRet) {
            exit(__ROOT__ . $upModel->getError());
        }
        $upRet = $upRet['app_file'];
        $upload['name'] = $upRet['name'] . "";
        $upload['savename'] = $upRet['savename'];
        $upload['savepath'] = $upRet['savepath'];
        $upload['ext'] = $upRet['ext'];
        $upload['mime'] = $upRet['mime'];
        $upload['size'] = $upRet['size'];
        $upload['md5'] = $upRet['md5'];
        $upload['sha1'] = $upRet['sha1'];
        $upload['time'] = time();
        $upload['path'] = "./Uploads/" . $upRet['savepath'] . $upRet['savename'];
        $upAddRet = M('system_upload')->add($upload);
        if (!$upAddRet) {
            exit("error|服务器端错误");
        } else {
            exit("/" . $upload['path']);
        }
    }

    public function uploadImg()
    {

    }

    /**
     * 上传商品的图片
     */
    public function uploadGoodsImg()
    {

    }

    /**
     * 删除商品的图片
     */
    public function removeGoodsImg()
    {

    }
}