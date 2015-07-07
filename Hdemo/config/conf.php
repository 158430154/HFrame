<?php

return array(
    'app_name' => 'H+框架DEMO',//项目名称
    'db' => array(
        'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=test',//数据库IP和端口(端口可以省略 如果是默认端口的话) 数据库名称
        'username' => 'root',//用户名
        'password' => '123456',//密码
        'table_prefix' => 'm_'//表前缀
    ),
    'app_file_name' => 'app',//APP文件夹名称
    'log_file_name' => 'log',//日志文件夹名称
    'controller_file_name' => 'controller',//控制器文件夹名称
    'view_file_name' => 'view',//视图文件夹名称
    'is_param_route' => false,//是否为参数路由
    'param_route_key' => 'a',//参数路由的获取关键字
    'param_route_separator' => '_',//参数路由的分割符
    'controller' => 'index',//默认控制器
    'action' => 'index',//默认方法
    'auto_import' => array(
        'models'
    ),
    'is_log' => false//是否需要记录日志
);