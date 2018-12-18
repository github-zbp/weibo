<?php 
    namespace core\lib;
    use core\lib\Conf;
    
    class Route
    {
        public static $module;
        public static $controller;
        public static $action;
        public static $alias=[];
        public static $config=[];
        const CONFIG_NAME="route";
        
        public static function init(){
            /*url形式如下：xxx/com/index.php/index/index 
            * 表示访问index控制器的index方法
            * 但是我希望用户输入xxx/com/index/index的情况下也能够访问到。所以这里涉及到url中隐藏index.php，只需在框架根目录下写个.htaccess即可，当然只限Apache服务器，如果是Nignx可以网上搜索一下
            *
            
            */
            
            //读取路由配置
            self::$config=Conf::get(self::CONFIG_NAME);
            
            if(isset($_SERVER['REQUEST_URI']) && trim($_SERVER['REQUEST_URI'],"/")){
                include(ROUTE_ALIAS);
                $path=trim($_SERVER['REQUEST_URI'],"/");
				$path=explode("?",$path)[0];
                foreach(self::$alias as $k=>$v){
                    if(strpos($path,$k) === 0){
                        $path=str_replace($k,$v,$path);
                        break;
                    }
                }
                
                $pathArr=explode("/",$path);
                //当$_SERVER['REQUEST_URI']是/index而非/index/index时，$pathArr[0]是有的。
                if(isset($pathArr[0])){
                    self::$module=$pathArr[0];
                    unset($pathArr[0]);
                }
                if(isset($pathArr[1])){
                    self::$controller=$pathArr[1];
                    unset($pathArr[1]);
                }else{
                    self::$controller=self::$config["default_controller"];
                }
                if(isset($pathArr[2])){
                    self::$action=$pathArr[2];
                    unset($pathArr[2]);
                }else{
                    self::$action=self::$config["default_action"];
                }
                
                //将url中的其他/后的东西作为get参数
                $pathArr=array_values($pathArr);
                for($i=0,$n=count($pathArr);$i<$n;$i++){
                    if(!isset($pathArr[$i+1])){ //如果$pathArr元素个数为基数，那么最后一个参数就不设置
                        break;
                    }
                    $_GET[$pathArr[$i]]=$pathArr[$i+1];
                    $i++;
                }
            }else{
                self::$module=self::$config["default_module"];
                self::$controller=self::$config["default_controller"];
                self::$action=self::$config["default_action"];
            }
        }
        
        public static function alias($alias,$route){
            if(!Conf::get("Route","alias")){
                return false;
            }
            self::$alias[trim($alias,"/")]=trim($route,'/');
        }
    }
?>