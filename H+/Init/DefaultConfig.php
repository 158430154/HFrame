<?php

//配置项
return array(
    /*--项目相关开始--*/
    'app_name' => '',//项目名称
    /*--项目相关结束--*/

    /*--数据库相关开始--*/
    'db' => array(
        'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=test',//数据库IP和端口(端口可以省略 如果是默认端口的话) 数据库名称
        'username' => 'root',//用户名
        'password' => '123456',//密码
        'table_prefix' => 't_'//表前缀
    ),
    /*--数据库相关结束--*/

    /*--框架目录结构相关开始--*/
    'app_file_name' => 'app',//APP文件夹名称
    'log_file_name' => 'log',//日志文件夹名称
    'controller_file_name' => 'controller',//控制器文件夹名称
    'view_file_name' => 'view',//视图文件夹名称
    /*--框架目录结构相关结束--*/

    /*--路由相关开始--*/
    'is_param_route' => false,//是否为参数路由
    'param_route_key' => 'a',//参数路由的获取关键字
    'param_route_separator' => '_',//参数路由的分割符
    'controller' => 'index',//默认控制器
    'action' => 'index',//默认方法
    /*--路由相关结束--*/

    /*--自动加载相关开始--*/
    'auto_import' => array(
        'models'
    ),
    /*--自动加载相关结束--*/

    /*--日志相关开始--*/
    'is_log' => true,//是否需要记录日志
    /*--日志相关结束--*/

    /*--文件上传相关开始--*/
    'file_upload' => array(
        'file_name' => 'files',//上传文件的目录名称
        'max_size' => 2097152,//上传最大限制 默认2M; 0表示不限制上传大小
        'allow_files' => array(//定义允许上传的文件扩展名 和 对应的类型
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp')
        )
    ),
    /*--文件上传相关结束--*/
);