<?php
    define("ROOT",__DIR__);   //框架根目录
    
    //核心文件目录
    define("CORE_DIR",ROOT."/core"); 
    
    //应用层目录
    define("APP",ROOT."/app");
    define("APP_NAMESPACE","app");
    
    //配置文件目录
    define("CONF_DIR",ROOT."/config");
    
    //路由别名文件路径
    define("ROUTE_ALIAS",ROOT."/route/route.php");
    
    //日志目录
    define('DEBUG_LOG_PATH',ROOT. '/log');
    
    //开启调试模式
    define("DEBUG",true);
    
    //调试信息的级别
    define("DEBUG_LOG_ERROR","ERROR");
    define("DEBUG_LOG_WARNING","WARNING");
    define("DEBUG_LOG_INFO","INFO");
    
    //url中调试参数秘钥
    define("DEBUG_PASS","_debug");
    
    // define("DEBUG_LEVEL",0);
?>