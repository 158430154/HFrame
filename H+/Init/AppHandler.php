<?php

class AppHandler {

    public function init(){
        set_exception_handler(array($this,'handleException'));//异常回调
        set_error_handler(array($this,'handleError'),error_reporting());//错误回调
        register_shutdown_function(array($this,'handleFatalError'));//致命错误回调
    }

    /**
     * 异常处理
     * @param HException $exception
     */
    private function handleException($exception){
        $code = $exception->getCode();
        //服务器错误
        if($code == 500){
            Controller::renderErr($exception->getMessage(),$exception->getFile(),$exception->getLine(),$exception->data);
        }elseif($this->_h_config['is_log']){
            $log = 'Exception Code['.$code.'] Msg['.$exception->getMessage().'] '.$exception->getFile().' on line '.$exception->getLine();
            HLog::model()->add($log,HLog::LEVEL_ERROR);
        }
        H::app()->end();
    }

    /**
     * 错误处理
     * @param int $code 错误码
     * @param string $message 错误消息
     * @param string $file 错误文件
     * @param int $line 错误行
     */
    private function handleError($code,$message,$file,$line){
        if(H::getConfig('is_log')){
            //$trace = debug_backtrace();//需要时候再用
            $log = 'Error Code['.$code.'] Msg['.$message.'] '.$file.' on line '.$line;
            HLog::model()->add($log,HLog::LEVEL_ERROR);
        }else{
            Controller::renderErr($message,$file,$line);
        }
        H::app()->end();
    }

    /**
     * 致命错误处理
     */
    public function handleFatalError(){
        $error = error_get_last();
        if($error){
            if(H::getConfig('is_log')){
                $log = 'FatalError Type['.$error['type'].'] Msg['.$error['message'].'] '.$error['file'].' on line '.$error['line'];
                HLog::model()->add($log,HLog::LEVEL_ERROR);
                HLog::model()->save();
            }else{
                Controller::renderErr($error['message'],$error['file'],$error['line']);
            }
            H::app()->end();
        }
    }

}