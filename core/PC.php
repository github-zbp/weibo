<?php 
    namespace core;
    use core\lib\Route; //引入路由类
    use core\lib\Conf;  //引入配置类
    use core\lib\View; //引入模板类
    use core\lib\Controller;
    use core\lib\DebugLog;     //调试类
    
    class PC
    {
        public static $classMap=[]; //存储已经引入过的类
        
        public static function run(){
            /*加载调试类,这个调试类一定要放在比较前面的地方，如果放在加载控制器初始化的调用后面，那么页面调用控制器的时间就会不被计入页面的总加载时间中；
            *
            */
			if(!isset($_GET["_debug"]) || $_GET["_debug"] != "0"){
				DebugLog::init();
			}
            
            //初始化自动加载配置文件类
            Conf::init(CONF_DIR);
            
            //加载路由
            Route::init();
            
            //加载模板类
            View::init(["module"=>Route::$module,"controller"=>Route::$controller,"action"=>Route::$action]);
            
            // 加载控制器
            Controller::init();
            
            
            
            //模型类Crud.php无需初始化，只需控制器去继承它即可
        }
        
        public static function load($class){
            //自动加载类库
            // var_dump($class); $class会连着命名空间和类名一起,比如Route.php命名空间是core,则new \core\Route();时$class时core\Route而不是\core\Route;
            
            if(isset($classMap[$class])){
                return true;
            }
            
            $class = str_replace("\\","/",$class);
            $file=ROOT."/".$class.".php";
            if(is_file($file)){
                include_once($file);
                self::$classMap[$class]=$class;
            }else{
                return false;
            }
        }
    }
?>