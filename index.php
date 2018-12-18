<?php
    /*
    * 入口文件
    * 1.定义常量
    * 2.加载函数库
    * 3.启动框架
    */
    
    //引入定义常量的文件，所有常量都定义在里面
    include("const.php");
    
    //引入调试文件
    include("debug.php");
    
    //引入函数库
    include(CORE_DIR."/common/function.php");  
    
    //引入框架启动类
    include(CORE_DIR."/PC.php"); 
    
    spl_autoload_register("\core\PC::load");
    
    \core\PC::run();
    
?>