<?php

class Autoloader {

    //核心自动加载列表
    private $core_auto_import = array(
        'HModel' => 'Db/HModel.php',
        'HPdo' => 'Db/HPdo.php',
        'HTransaction' => 'Db/HTransaction.php',
        'DBException' => 'Exception/DBException.php',
        'HException' => 'Exception/HException.php',
        'HTTPException' => 'Exception/HTTPException.php',
        'HLog' => 'Log/HLog.php',
        'HSession' => 'Extends/HSession.php',
        'HCookie' => 'Extends/HCookie.php',
        'HFileUpload' => 'Extends/HFileUpload.php'
    );

    public function init(){
        spl_autoload_register(array($this,'loader'));
    }

    /**
     * 自动加载
     * @param string $class_name 类名
     */
    private function loader($class_name){
        if(isset($this->core_auto_import[$class_name])){//核心列表
            include H::app()->h_base_path.'/Core/'.$this->core_auto_import[$class_name];
        }else{//文件夹
            foreach($this->_h_config['auto_import'] as $file_name){
                $path = H::app()->app_path.'/'.$file_name.'/'.$class_name.'.php';
                if(file_exists($path)){
                    include $path;
                }
            }
        }
    }

}