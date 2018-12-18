<?php 
    namespace core\lib;
    
    class Conf implements \ArrayAccess
    {
        protected $path;
        protected $configs=[];
        public static $instance=null;
        
        private function __construct($path){
            $this->path=rtrim($path,"/")."/";
        }
        
        public static function init($path){
            if(!self::$instance){
                self::$instance=new self($path);
            }
            
        }
        
        public static function get($fn,$key=''){
            $instance=self::$instance;
            $config=$instance[$fn];
        
            if($key){
                $config=isset($config[$key])?$config[$key]:false;
            }
            
            return $config;
        }
        
        function offsetGet($key){
            if(empty($this->configs[$key])){
                $fp=$this->path.$key.".php";
                if(!file_exists($fp)){
                    throw new \Exception("The Config file you found does not exist");
                }
                $this->configs[$key]=include($fp);
            }
            
            return $this->configs[$key];
        }
        
        function offsetSet($key,$value){
            return false;
        }
        
        function offSetUnset($key){
            return false;
        }
        
        function offsetExists($key){
            return isset($this->configs[$key]);
        }
        
        
    }
?>