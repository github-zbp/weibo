<?php 
namespace core\lib\Mysql;
use core\lib\DebugLog;
use core\lib\Conf;

class Db
{
    //存pdo对象
    private $pdo=null;
    
    //存pdoStatement对象
    private $stat=null;
    
    //存相应配置
    private $config=[];
    
    //存绑定参数
    private $params=[];
    
    private $connected=false;
    
    //存Db对象实例
    private static $instances=[];
    
    public static function getInstance($name="master"){
        if(empty(self::$instances[$name])){
            self::$instances[$name]=new self($name);
        }
        
        return self::$instances[$name];
    }
    
    //单例模式
    private function __construct($name="master"){
        $this->connect($name);
    }
    
    private function connect($name="master"){
        $config=Conf::get("db");    //获取配置
        $this->config=$config["master"];
        extract($this->config);
        
        $mt1=microtime();
        
        $dsn="mysql:dbname=".$dbname.";host=".$host;
        try{
            $this->pdo=new \PDO($dsn,$user,$password,[\PDO::MYSQL_ATTR_INIT_COMMAND=>"set names utf8"]);
            
            //设置执行sql如有错误时抛出异常
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
            
            //设置必须使用预处理语句
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES,true);
            
        }catch(\PDOException $e){
            print_r($e);
            $this->ExceptionLog($e->getMessage());
            die;
        }
        $this->connected=true;
        DebugLog::_mysql('connect', null, array('host' => $host, 'dbname' => $dbname), $mt1, microtime(), null);
    }
    
    public function close(){
        $this->pdo=null;
    }
    
    /*
    *预处理语句
    *绑定参数
    *执行语句但不获取结果集
    *
    */
    public function init($query,$params=[]){
        if(!$this->connected){
            $this->connect();
        }
        
        $this->stat=$this->pdo->prepare($query);
        
        //绑定参数
        if(is_array($params) && count($params)){
            if(isset($params[0])){
                //? 形式占位符
                foreach($params as $k=>$v){
                    $this->stat->bindParam($k+1,$v);
                }
            }else{
				// var_dump($params);
                $this->bindAssoc($params);
            }
        }
        
        //执行语句
        $this->stat->execute();
        
        $this->params=[];
    }
    
    //绑定关联参数，并将参数存到属性中
    protected function bindAssoc($params=[]){
		// var_dump($params);
        foreach($params as $k=>$v){
            $k=":".$k;
            $this->params[$k]=$v;
            $this->stat->bindParam($k,$this->params[$k]);	//这里千万不要写成了$this->stat->bindParam($k,$v);,因为第二参是引用
        }
    }
    
    //获取多条结果
    public function query($query,$params=[],$mode=\PDO::FETCH_ASSOC){
        $mt1=microtime();
        
        $query=trim($query);
        $this->init($query,$params);
        $statement=explode(" ",$query)[0];
        
        switch(strtolower($statement)){
            case "select":
			case "show":
                 //获取结果集
                $res=$this->stat->fetchAll($mode);
				
            break;
            case "update":
            case "insert":
            case "delete":
                $res=$this->stat->rowCount();
            break;
            default:$res=false;
        }
        
        DebugLog::_mysql('query: ' . $query, $params, array('host' => $this->config['host'], 'dbname' => $this->config['dbname']), $mt1, microtime(), $res);
        
        return $res;
    }
    
    //获取一条结果
    public function row($query,$params=[],$mode=\PDO::FETCH_ASSOC){
        $mt1=microtime();
        
        $this->init($query,$params);
        
        $res=$this->stat->fetch($mode);
        
        DebugLog::_mysql('row: ' . $query, $params, array('host' => $this->config['host'], 'dbname' => $this->config['dbname']), $mt1, microtime(), $res);
        
        return $res;
    }
    
    //获取一列
    public function column($query,$params=[]){
        $mt1=microtime();
        $this->init($query,$params);
        
        $res=$this->stat->fetchAll(\PDO::FETCH_NUM);
        foreach($res as $k=>$v){
            $column[]=$v[0];
        }
        
        DebugLog::_mysql('row: ' . $query, $params, array('host' => $this->config['host'], 'dbname' => $this->config['dbname']), $mt1, microtime(), $column);
        
        return $column;
    }
    
    //获取一个键
    public function single($query,$params=[]){
        $mt1=microtime();
        $this->init($query,$params);
        
        $res=$this->stat->fetchColumn();
        
        DebugLog::_mysql('single: ' . $query, $params, array('host' => $this->config['host'], 'dbname' => $this->config['dbname']), $mt1, microtime(), $res);
        
        return $res;
    }
    
    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }
    
    private function ExceptionLog($message, $sql = "")
    {
        $exception = 'Unhandled Exception. <br />';
        $exception .= $message;
        $exception .= "<br /> You can find the error back in the log.";

        if (!empty($sql)) {
            # Add the Raw SQL to the Log
            $message .= "\r\nRaw SQL : " . $sql;

            return $exception;
        }
    }
}

?>