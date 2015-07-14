<?php

class HFileUpload {

    private $_file_dir = 'files'; //上传文件的目录
    private $_max_size = 2097152; //上传最大限制 默认2M; 0表示不限制上传大小
    private $_type_arr = array( //定义允许上传的文件扩展名
        'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp')
    );
    private $_base_path = '';
    private $_base_url = '';

    private $_file = false; //上传的文件
    private $_type = ''; //上传文件类型
    private $_suffix = ''; //文件后缀

    private $_res_data = array(
        'ok' => false,
        'msg' => '',//反馈消息 ok == false 的时候才有
        'path' => '',//文件上传的物理路径
        'url' => '',//文件的相对URL
        'thumburl' => ''//文件为图片且需要缩略图的时候才会有
    );

    private static $_HF = null;

    /**
     * 实例化模型
     * @return $this
     */
    public static function instance(){
        if(self::$_HF === null){
            self::$_HF = new HFileUpload();
            self::$_HF->_base_path = H::app()->base_path;
            self::$_HF->_base_url = H::app()->base_url;

            $file_upload = H::getConfig('file_upload');
            if($file_upload){
                self::$_HF->_file_dir = isset($file_upload['file_name'])?$file_upload['file_name']:self::$_HF->_file_dir;
                self::$_HF->_max_size = isset($file_upload['max_size'])?$file_upload['max_size']:self::$_HF->_max_size;
                self::$_HF->_type_arr = isset($file_upload['allow_files'])?$file_upload['allow_files']:self::$_HF->_type_arr;
            }
        }
        return self::$_HF;
    }

    /**
     * 保存上传的文件
     * @param string $file_key 获取文件的KEY名
     * @param array $param 参数
     *              array(
     *                  'width' => 100, //缩略图宽
     *                  'height' => 100 //缩略图高
     *              )
     * @return array
     */
    public function save($file_key,$param = array()) {
        //1.获取文件
        if(!$this->_getFile($file_key)){
            return $this->_res_data;
        }

        //2.保存文件
        $dir_path = $this->_file_dir.'/'.date('Y/m/d').'/'.uniqid();
        $file_path = $this->_base_path.'/'.$dir_path.'/'.$this->_file['name'];
        $this->_res_data['path'] = $file_path;
        $this->_res_data['url'] = $this->_base_url.'/'.$dir_path.'/'.$this->_file['name'];

        if(!$this->_makeDir($dir_path)){
            $this->_res_data['msg'] = '目录创建失败';
            return $this->_res_data;
        }

        //移动并且删除临时文件到指定目录
        $move_status = move_uploaded_file($this->_file['tmp_name'],$file_path);
        if($move_status){
            //判断是否需要缩略图
            if(isset($param['width']) && isset($param['height'])){
                $this->_res_data['ok'] = true;
                $this->_res_data['thumburl'] = $this->_res_data['url'];
            }else{
                $this->_res_data['ok'] = true;
            }
        }else{
            $this->_res_data['msg'] = '移动文件失败';
        }

        return $this->_res_data;
    }

    /**
     * 获取文件
     * @param string $file_key 获取文件的KEY名
     * @return bool
     */
    private function _getFile($file_key) {
        $this->_file = isset($_FILES[$file_key])?$_FILES[$file_key]:false;
        if(!$this->_file){
            $this->_res_data['msg'] = $this->_getError();
        }
        if($this->_verifySuffix() && $this->_verifySize()){
            return true;
        }
        return false;
    }

    //验证文件类型是否为允许的文件类型
    private function _verifySuffix() {
        //123.jpg  按 . 号分割成数组 array('123','jpg')
        $name_arr = explode('.',$this->_file['name']);
        //取数组第二个，也就是文件后缀名
        $this->_suffix = isset($name_arr[1])?$name_arr[1]:'';

        foreach($this->_type_arr as $type => $type_arr){
            if(in_array($this->_suffix,$type_arr)){
                $this->_type = $type;
                break;
            }
        }

        if($this->_type == ''){
            $this->data['nsg'] = '文件类型错误';
            return false;
        }

        return true;
    }

    //验证文件大小是否被允许
    private function _verifySize() {
        $size = $this->_file['size'];

        if ($size <= $this->_max_size || $this->_max_size == 0){
            return true;
        }

        $this->_res_data['msg'] = '文件超过最大上传限制';
        return false;
    }

    /**
     * 创建目录
     * @param string $dir 目录字符串
     * @return bool
     */
    private function _makeDir($dir) {
        if (!is_dir($dir)) {
            if($this->_makeDir(dirname($dir))) {
                return mkdir($dir);
            }
            return false;
        }
        return true;
    }

    /**
     * 获取系统上传文件的错误信息
     * @return string
     */
    private function _getError(){
        $error = $this->_file['error'];
        if($this->_file === false){
            return '没有获取到文件';
        }
        $error_data = array(
            1 => '文件大小超过服务器配置大小',
            2 => '文件大小超过HTML表单大小',
            3 => '文件只有部分被上传',
            4 => '没有文件被上传',
            5 => '',
            6 => '找不到临时文件夹',
            7 => '文件写入失败',
        );
        return isset($error_data[$error])?$error_data[$error]:'其它错误';
    }

} 