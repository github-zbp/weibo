<?php
/*
*  ORM 数据对象映射模型
*
*/ 
    namespace core\lib;
    use core\lib\Mysql\Db;
    
    class Crud
    {
        //存储Db实例
        protected $db=null;
        
        //存储该数据表的所有字段
        protected $fields=[];
        
        //存储当前一条数据的各字段的值，可以只含该表的部分字段
        protected $vals;
        
        //存该表主键名称
        protected $pk;
        
        //存储表名
        protected $table;
        
        //创建数据的时间字段
        protected $create_time_field="";
        
        //可以传id或者数据
        public function __construct($db='master',$data=0){
            $this->setDb($db);
            
            if(is_int($data)){
                $this->get($data);
            }
			
            $this->setVals($data);
        }
        
        public function setDb($db='master'){
            $this->db = Db::getInstance($db);
        }
        
        public function getDb(){
            return $this->db;
        }
        
        /*
        * $name 字段名
        * $val  字段值
        */
        public function __set($name,$val){
            if(in_array($name,$this->fields)){
                $this->vals[$name]=$val;
            }
        }
        
        public function __get($name){
            if(!in_array($name,$this->fields)){
                throw new \Exception("The field you got does not exist");
            }
            
            if(array_key_exists($name,$this->vals)){
                return $this->vals[$name];
            }
            
            return null;
        }
        
        /*
        *  根据id获取一条数据
        *  如果不传id，则返回$this->vals的字段数据
        *  
        */
        public function get($id=0){
            if(!$id){
                if(empty($this->vals[$this->pk])){
                    return false;
                }
                
                $flag=true;
                foreach($this->fields as $v){
                    if(!array_key_exists($v,$this->vals)){
                        $flag=false;
                    }
                }
                if($flag){
                    return $this->vals;
                }
                
                $id=$this->vals[$this->pk];
            }
            
            $sql="select * from `{$this->table}` where `{$this->pk}`=:{$this->pk} limit 1";
            $this->vals=$this->db->row($sql,[$this->pk=>$id]);
            
            return $this->vals;
        }
        
        /*
        *  更新操作
        *  根据id更新数据
        */
        public function save($id=0){
            $this->vals[$this->pk]= $id ? $id : $this->vals[$this->pk];
            
            $set="";
            foreach($this->vals as $k=>$v){
                if($k !== $this->pk){
                    $columns[$k]=$v;
                    $set.="`{$k}`=:{$k},";
                }
            }
			
            $set=rtrim($set,",");
            $columns[$this->pk]=$this->vals[$this->pk];
            if(count($columns)>0){
                $sql="update `{$this->table}` set {$set} where `{$this->pk}`=:{$this->pk}";
				// var_dump($sql);
                return $this->db->query($sql,$columns);
            }
			
        }
        
        /*
        *  新增
        *
        */
        public function create($data=[]){
            if($this->create_time_field && !isset($data[$this->create_time_field])){
                $data[$this->create_time_field]=time();
            }
            $this->setVals($data);
            
            if(isset($this->vals[$this->pk])){
                throw new \Exception("Can not create a row with primary key");
            }
            
            $keys=array_keys($this->vals);
            $fieldsVal=["`".implode("`,`",$keys)."`",":".implode(",:",$keys)];
            if($keys){
                $sql="insert into `{$this->table}` ({$fieldsVal[0]}) values ({$fieldsVal[1]})";
            }else{
                //插入一条除了id之外，其他字段都为空的数据
                $sql="insert into `{$this->table}` () values ()";
            }
            
            $rowCount=$this->db->query($sql,$this->vals);
            if($rowCount){
                return $this->db->lastInsertId();
            }else{
                return false;
            }
        }
        
        public function delete($id=0){
            $id= $id ? $id : $this->vals[$this->pk];
            
            if(!$id){
                return false;
            }
            
            $sql="delete from `{$this->table}` where `{$this->pk}`=:{$this->pk} limit 1";
            return $this->db->query($sql,[$this->pk=>$id]);
        }
        
        public function all($where=[]){
            if(is_array($where)){
                $whereSql="";
                foreach($where as $k=>$v){
                    $whereSql.="`{$k}`=:{$k} and";
                }
                $whereSql=rtrim($whereSql,"and");
                $params=$where;
                
            }elseif(is_string($where)){
                $whereSql=str_replace("where","",$where);
                $params=[];
            }
            
			if(trim($whereSql)){
				$sql="select * from `{$this->table}` where {$whereSql}";
			}else{
				$sql="select * from `{$this->table}`";
			}
            
			
			// var_dump($sql);
            return $this->db->query($sql,$params);
        }

        public function count($where=""){
			if($where){
				$where="where ".trim($where,"where");
			}
            return $this->db->row("SELECT COUNT(*) FROM `" . $this->table . "` {$where} ",[],\PDO::FETCH_NUM)[0];
        }
        
        /*
        *  为字段设置值
        *
        */
        public function setVals($data=[]){
            if(is_array($data) && $data){
                $this->vals=[];  //清空一下$this->vals
                
                //过滤掉不相关的字段
                foreach($data as $k=>$v){
                    if(in_array($k,$this->fields)){
                        $this->vals[$k]=$v;
                    }
                }
            }
        }
        
    }
?>