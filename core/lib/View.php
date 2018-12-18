<?php 
    namespace core\lib;
    use core\lib\Conf;
    
    class View
    {
        const CONFIG_NAME="view";
        const VIEW_DIRNAME="view";
        const ENGINE="smarty";
        public static $config=[];
        public static $view=null;   //保存模板引擎实例
        public static $tplPath=[];
        
        public static function init($tplPath=[]){
            if(!isset($tplPath['module']) || !isset($tplPath["controller"])){
                return false;
            }
            
            self::$tplPath=$tplPath;
            
            //读取配置
            self::$config=Conf::get(self::CONFIG_NAME,self::ENGINE);
            
            //引入模板引擎并且实例化
            include(self::$config["file"]);
            
            self::$view=new self::$config["class_name"]();
            
            //配置模板引擎
            foreach(self::$config["params"] as $k=>$v){
                self::$view->$k=$v;
            }
            
            //设置模板目录路径
            self::$view->template_dir=APP."/".$tplPath["module"]."/".self::VIEW_DIRNAME."/".$tplPath["controller"];
			
			//设置静态文件目录
			define("__STATIC__",self::$config['static']);
        }
        
        public static function assign($params=[]){
            foreach($params as $k=>$v){
                self::$view->assign($k,$v);
            }
        }
        
        public static function display($tpl=""){
            if(!$tpl){
                $tpl=self::$tplPath["action"].".html";
            }
            self::$view->display($tpl.".html");
        }
    }
?>