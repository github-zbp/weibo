<?php 
/**
 * 调试日志操作类
 * DEBUG_LEVEL=0的时候不会在后端运行，
 * DEBUG_LEVEL=1的时候会记录错误、警告信息以及资源调用的耗时汇总统计，
 * DEBUG_LEVEL=2的时候，会记录全部的数据
 * 如果在参数列表中出现 __DEBUG_LEVEL ，则会强制覆盖 DEBUG_LEVEL 的值
 * 功能列表如下：
 * 1 time 性能探针，计算运行的步骤以及每一步的执行效率
 * 2 log 日志记录，把每一个日志信息记录下来
 * 3 http 接口调用的记录以及耗时的汇总统计
 * 4 redis redis调用的记录以及耗时的汇总统计
 * 5 mysql mysql调用的记录以及耗时的汇总统计
 * 6 cache memcache调用的记录以及耗时的汇总统计
 * @author zbp
 */
    namespace core\lib;
    
    class DebugLog{
        private $logId="";
        private $logList=[];
        private $timeList=[];
        private $redisList=[];
        private $mysqlList=[];
        private $httpList=[];
        private $cacheList=[];
        
        private static $instance=null;
        
        //只在页面初始化类中调用
        public static function init(){
            if(!self::$instance){
                self::$instance=new self();
                self::$instance->logId=microtime();
            }
            
            //调试探针，页面开始计时
            self::_time("PC.php , start page");
        }
        
        public static function _time($label,$handler=false){
            if(!self::$instance){
                return;
            }
            
            self::$instance->timeList[]=[$label,microtime(),$handler];
        }
        
        public static function _log($label,$info,$level=DEBUG_LOG_INFO,$handler=false){
            if(!self::$instance){
                return;
            }
            if(DEBUG_LEVEL<2 && $level == DEBUG_LOG_INFO){
                return;
            }
            
            self::$instance->logList[]=[$label,$info,$level,$handler];
        }
        
        public static function _redis($label,$params,$config,$mt1,$mt2,$data=null,$handler=false){
            if(!self::$instance){
                return;
            }
            if(DEBUG_LEVEL == 1){
                if("setex" == $label){
                    $params[1]=null;
                }
                self::$instance->redisList[]=[$label,json_encode($params),json_encode($config),$mt1,$mt2,null,$handler];
            }else{
                self::$instance->redisList[]=[$label,json_encode($params),json_encode($config),$mt1,$mt2,$data,$handler];
            }
            
        }
        
        public static function _mysql($label,$params,$config,$mt1,$mt2,$data=null,$handler=false){
            if(!self::$instance){
                return;
            }
            if(DEBUG_LEVEL == 1){
                self::$instance->mysqlList[]=[$label,json_encode($params),json_encode($config),$mt1,$mt2,null,$handler];
            }else{
                self::$instance->mysqlList[]=[$label,json_encode($params),json_encode($config),$mt1,$mt2,$data,$handler];
            }
            
        }
        
        public static function _http($label,$params,$config,$mt1,$mt2,$data=null,$handler=false){
            if(!self::$instance){
                return;
            }
            if(DEBUG_LEVEL == 1){
                self::$instance->httpList[]=[$label,json_encode($params),json_encode($config),$mt1,$mt2,null,$handler];
            }else{
                self::$instance->httpList[]=[$label,json_encode($params),json_encode($config),$mt1,$mt2,$data,$handler];
            }
            
        }
        
        public static function _cache($label,$params,$config,$mt1,$mt2,$data=null,$handler=false){
            if(!self::$instance){
                return;
            }
            if(DEBUG_LEVEL == 1){
                self::$instance->cacheList[]=[$label,json_encode($params),json_encode($config),$mt1,$mt2,null,$handler];
            }else{
                self::$instance->cacheList[]=[$label,json_encode($params),json_encode($config),$mt1,$mt2,$data,$handler];
            }
            
        }
        
        /*将错误转为异常抛出
        * 该方法不会主动调用，而是会配合debug.php的set_error_handler()被动调用
        * 这样waring和info类的错误会被try catch捕获到
        */
        public static function errorToException($type,$message,$file,$line){
            throw new \Exception($message."({$file} in line {$line})");
        }
        
        private static function floatvalTime($mt){
            if(strpos($mt," ")){
                list($ms,$s)=explode(" ",$mt);
                $time=($s+$ms)*1000;
            }else{
                $time=floatval($mt)*1000;
            }
            
            return round($time);
        }
        
        private static function calTimeDiff($mt1,$mt2){
            return abs(self::floatvalTime($mt1)-self::floatvalTime($mt2));
        }
        
        public static function _show(){
            if(!self::$instance){
                return;
            }
            
            self::_time("shutdown page");
            if(isset($_SERVER["HTTP_USER_AGENT"])){
                self::$instance->showView();
            }else{
                self::$instance->writeLog();
            }
        }
        
        private function showView(){
            $now=microtime();
            $total_time=$this->calTimeDiff($this->logId,$now);
            $output[]="<ul><li><srtong style='font-size:18px'>页面运行时间：".$total_time."ms</strong></li>";
            
            if($this->timeList){
                $last_time=$this->logId;
                $output[]="<li><srtong style='font-size:18px'>时间探针记录数：".count($this->timeList)."</strong></li><ul>";
                foreach($this->timeList as $info){
                    $last_time2=$info[1];
                    $output[]="<li>".$this->calTimeDiff($last_time,$last_time2)." ms:".implode("\t",$info)."</li>";
                    $last_time=$last_time2;
                }
                $output[]="</ul>";
            }
            
            if($this->logList){
                $output[]="<li><srtong style='font-size:18px'>调试信息记录数：".count($this->logList)."</strong></li><ul>";
                foreach($this->logList as $info){
                    $output[]="<li>".implode("\t",$info)."</li>";
                }
                $output[]="</ul>";
            }
            
            if($this->redisList){
                $total_time=0;
                foreach($this->redisList as $info){
                    $process_time=$this->calTimeDiff($info[3],$info[4]);
                    if($info[5] && is_array($info[5])){
                        $info[5]=json_encode($info[5]);
                    }
                    $total_time+=$process_time;
                    $redisArr[]="<li>{$process_time} ms:".implode("\t",$info)."</li>";
                }
                
                $output[]="<li><srtong style='font-size:18px'>redis操作记录数：".count($this->redisList)."，总时长：{$total_time}</strong></li><ul>";
                $output=array_merge($output,$redisArr);
                $output[]="</ul>";
            }
            
            if($this->cacheList){
                $total_time=0;
                foreach($this->cacheList as $info){
                    $process_time=$this->calTimeDiff($info[3],$info[4]);
                    if($info[5] && is_array($info[5])){
                        $info[5]=json_encode($info[5]);
                    }
                    $total_time+=$process_time;
                    $cacheArr[]="<li>{$process_time} ms:".implode("\t",$info)."</li>";
                }
                
                $output[]="<li><srtong style='font-size:18px'>memcache操作记录数：".count($this->cacheList)."，总时长：{$total_time}</strong></li><ul>";
                $output=array_merge($output,$cacheArr);
                $output[]="</ul>";
            }
            
            if($this->mysqlList){
                $total_time=0;
                foreach($this->mysqlList as $info){
                    $process_time=$this->calTimeDiff($info[3],$info[4]);
                    if($info[5] && is_array($info[5])){
                        $info[5]=json_encode($info[5]);
                    }
                    $total_time+=$process_time;
                    $mysqlArr[]="<li>{$process_time} ms:".implode("\t",$info)."</li>";
                }
                
                $output[]="<li><srtong style='font-size:18px'>mysql操作记录数：".count($this->mysqlList)."，总时长：{$total_time}</strong></li><ul>";
                $output=array_merge($output,$mysqlArr);
                $output[]="</ul>";
            }
            
            $output[]="</ul>";
            echo implode("",$output);
        }
        
        private function writeLog(){
            $logTime=date("Y-m-d H:i:s");
            $server=[
               "script_name"=>$_SERVER["SCRIPT_NAME"],
                "request_uri"=>$_SERVER["REQUEST_URI"],
                "remote_addr:port"=>$_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"]
            ];
            $info=json_encode([
                "logId"=>$this->logId,
                "logTime"=>$logTime,
                "timeList"=>$this->timeList,
                "logList"=>$this->logList,
                "redisList"=>$this->redisList,
                "mysqlList"=>$this->mysqlList,
                "cacheList"=>$this->cacheList,
                "httpList"=>$this->httpList,
                "server"=>$server
            ]);
            $info=str_replace("\n"," ",$info)."\n";
            
            $fn=date("YmdHis").".log";
            if(!defined(DEBUG_LOG_PATH)){
                define("DEBUG_LOG_PATH","/log");
            }
            if(!file_exists(DEBUG_LOG_PATH)){
                self::makeDir(DEBUG_LOG_PATH);
            }
            if($fp=@fopen(DEBUG_LOG_PATH.$fn,"a")){
                fwrite($fp,$info);
                fclose($fp);
            }
            
        }
        
        //该方法用于生成自定义日志
        public static function writeDebugLog($msg,$fn=""){  //$msg可以是数组或字符串
            //日志的内容包括 记录时间、在哪个文件发生的、客户端ip、日志内容
            $info=json_encode([
                "logtime"=>time(),
                "script_name"=>$_SERVER["SCRIPT_NAME"],
                "request_uri"=>$_SERVER["REQUEST_URI"],
                "remote_addr:port"=>$_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"],
                "msg"=>$msg
            ]);
            $info=str_replace("\n"," ",$info)."\n";
            
            $fn="_".date("YmdHis").".log";  //加个_和系统日志做区分
            if(!file_exists(DEBUG_LOG_PATH)){
                self::makeDir(DEBUG_LOG_PATH);
            }
            if($fp=@fopen(DEBUG_LOG_PATH.$fn,"a")){
                fwrite($fp,$info);
                fclose($fp);
            }
        }
        
        private static function makeDir($path){
            $path=trim($path,"/");
            $pathArr=explode("/",$path);
            $realPath=defined("DOCUMENT_ROOT")?DOCUMENT_ROOT:__DIR__;
            foreach($pathArr as $dir){
                $realPath.="/".$dir;
                if(!file_exists($realPath)){
                    mkdir($realPath);
                }
            }
        }
    }
?>