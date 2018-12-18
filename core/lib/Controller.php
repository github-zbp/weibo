<?php 
    namespace core\lib;
    use core\lib\View;
    use core\lib\Route;
    
    class Controller
    {
        const CTRL_DIRNAME="controller";
        public static $module;
        public static $controller;
        public static $action;
        
        public function __construct(){
            self::$module=Route::$module;
            self::$controller=Route::$controller;
            self::$action=Route::$action;
        }
        
        public static function init(){
            $module=Route::$module;
            $controller=Route::$controller;
            $action=Route::$action;
            $ctrl_namespace="\\".APP_NAMESPACE."\\".$module."\\".self::CTRL_DIRNAME."\\".$controller."Ctrl";
            $ctrl=new $ctrl_namespace();
            $ctrl->$action();
        }
        
        public function assign($params=[]){
            View::assign($params);
        }
        
        public function display($tpl="",$params=[]){
            if($params){
                View::assign($params);
            }
            View::display($tpl);
        }
    }
?>