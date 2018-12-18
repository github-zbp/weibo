<?php 
    //调试的严格程度设置,可以通过在url中传递_debug参数来决定
    if(!isset($_GET[DEBUG_PASS])){
        if(!DEBUG){
            define("DEBUG_LEVEL",0);
        }elseif(DEBUG){
            //如果开启了调试模式，那么默认调试严格程度是1
            define("DEBUG_LEVEL",1);
        }
    }else{
        switch($_GET[DEBUG_PASS]){
            case 0:
            case 1:
            case 2:
                define("DEBUG_LEVEL",$_GET[DEBUG_PASS]);
            break;
            default:define("DEBUG_LEVEL",intval(DEBUG));
        }
    }
    
    if(DEBUG){
        ini_set("display_errors","On");
    }else{
        ini_set("display_errors","Off");
    }
    
    include_once(CORE_DIR."/lib/DebugLog.php");
    // ob_start();
    //捕获warning和notice的错误，并且交给自定义函数处理，一般会配合try catch使用
    set_error_handler(["\\core\\lib\\DebugLog","errorToException"]);
    
    //在程序最后显示或者记录调试信息
    register_shutdown_function(['\\core\\lib\\DebugLog', '_show']);
?>