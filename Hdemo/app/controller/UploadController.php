<?php

/**
 * 文件上传控制器
 * Class UploadController
 */
class UploadController extends Controller{

    //上传文件
    public function actionFile(){
        $file_key = $this->getParams('file_key');
        $res_data = HFileUpload::instance()->save($file_key);
        var_dump($res_data);
    }

}