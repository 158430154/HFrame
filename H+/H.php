<?php

class H{

    private static $_h_app = null;//APP缓存变量

    public $h_base_path = '';//框架目录

    public $base_path = '';//项目根路径
    public $app_path = '';//APP根路径
    public $base_url = '';//项目url
    public $public_url = '';//公共目录url
    public $file_url = '';//files目录url
    public $log_path = '';//日志路径
    public $view_path = '';//视图路径

    //配置项
    private $_h_config = array();

    /**
     * 应用初始化
     * @return H
     */
    public static function app(){
        if(self::$_h_app === null){
            self::$_h_app = new H();
        }
        return self::$_h_app;
    }

    function __construct(){
        $this->initCore();
        $this->initSystemSetting();
        $this->initConfigSetting();
        $this->initCommon();
    }

    //初始化核心代码
    private function initCore(){
        //自动加载
        include $this->h_base_path.'/Init/Autoloader.php';
        //AppHandler
        include $this->h_base_path.'/Init/AppHandler.php';
        //基类控制器
        include $this->h_base_path.'/Core/Controller/Controller.php';
    }

    //初始化系统设置
    private function initSystemSetting(){
        //入口文件
        defined('H_APP_ENTRY_FILE') or define('H_APP_ENTRY_FILE','index.php');

        //设置时区
        date_default_timezone_set('Asia/Shanghai');
        //设置编码
        header('Content-type: text/html; charset=utf-8');

        //注册自动加载
        $autoloader = new Autoloader();
        $autoloader->init();

        //注册 异常 错误 致命错误 的回调
        $app_handler = new AppHandler();
        $app_handler->init();
    }

    //初始化路径相关
    private function initConfigSetting(){
        //框架根目录
        $this->h_base_path = dirname(__FILE__);
        //项目根路径
        $this->base_path = H_APP_PATH;
        //配置项初始化
        $default_config = include $this->h_base_path.'/Init/DefaultConfig.php';
        $user_config = include $this->base_path.'/config/conf.php';
        $this->_h_config = array_merge($default_config,$user_config);
        //其它相关路径
        $this->app_path = $this->base_path.'/'.$this->_h_config['app_file_name'];
        $this->base_url = dirname($_SERVER['SCRIPT_NAME']);
        if($this->base_url == DIRECTORY_SEPARATOR){
            $this->base_url = '';
        }
        $this->public_url = $this->base_url.'/public';
        $this->file_url = $this->base_url.'/files';
        $this->log_path = $this->app_path.'/'.$this->_h_config['log_file_name'];
        $this->view_path = $this->app_path.'/'.$this->_h_config['view_file_name'];
    }

    //引用框架外常用方法
    private function initCommon(){
        include $this->h_base_path.'/Common/Function.php';
    }

    /**
     * 获取APP配置项
     * @param string $key 配置项的KEY
     * @return mixed
     */
    public static function getConfig($key){
        return isset(self::$_h_app->_h_config[$key])?self::$_h_app->_h_config[$key]:'';
    }

    //运行程序
    public function run(){
        list($controller,$action) = $this->getRouteParams();

        $this->runController($controller,$action);

        $this->end();
    }

    //获取路由参数
    private function getRouteParams(){
        $route_arr = array();
        //是否为参数路由
        if($this->_h_config['is_param_route']){
            $route_str = isset($_GET[$this->_h_config['param_route_key']])?$_GET[$this->_h_config['param_route_key']]:'';
            if($route_str != ''){
                $route_arr = explode($this->_h_config['param_route_separator'],$route_str);
            }
        }else{
            $uri = $_SERVER['REQUEST_URI'];
            $uri_end = strpos($uri,'?');
            if($uri_end !== false){
                $uri = substr($uri,0,$uri_end);
            }
            if(strpos($uri,$_SERVER['SCRIPT_NAME']) === false){
                $dir_name = $this->base_url;
            }else{
                $dir_name = $_SERVER['SCRIPT_NAME'];
            }
            $route_str = substr($uri,strlen($dir_name)+1);

            if($route_str != false){
                $route_arr = explode('/',$route_str);
            }
        }

        //控制器
        $controller = (isset($route_arr[0]) && $route_arr[0])?$route_arr[0]:$this->_h_config['controller'];
        //方法
        $action = (isset($route_arr[1]) && $route_arr[1])?$route_arr[1]:$this->_h_config['action'];

        return array($controller,$action);
    }

    /**
     * 运行控制器里面的对应方法
     * @param string $controller 控制器
     * @param string $action 方法
     * @throws HTTPException
     */
    public function runController($controller,$action){
        //控制器类名 首字母被转换为大写字符
        $controllerClass = ucfirst($controller).'Controller';
        $path = $this->app_path.'/'.$this->_h_config['controller_file_name'].'/'.$controllerClass.'.php';

        //文件是否存在
        if(!file_exists($path)){
            throw new HTTPException($controllerClass.'.php not found');
        }
        include $path;

        $controller_obj = new $controllerClass;
        $controller_obj->controller = lcfirst($controller);
        $controller_obj->action = lcfirst($action);

        //是否继承于核心控制器类
        if(!($controller_obj instanceof Controller)){
            throw new HTTPException($controllerClass.'php is must extends Controller');
        }

        //方法名
        $action_method = 'action'.ucfirst($action);

        //方法是否存在
        if(!method_exists($controller_obj,$action_method)){
            throw new HTTPException('method '.$action_method.' not found');
        }

        //方法必须为public
        if(!is_callable(array($controller_obj,$action_method))){
            throw new HTTPException('method '.$action_method.' is must a public method');
        }

        if($controller_obj->beforeAction()){
            $controller_obj->$action_method();
            $controller_obj->afterAction();
        }
    }

    //APP结束
    public function end(){
        if($this->_h_config['is_log']){
            HLog::model()->save();
        }
        exit;
    }

}