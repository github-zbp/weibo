<?php 
	namespace app\index\controller;
	use core\lib\Controller;
    use core\lib\Datasource as ds;
	use app\index\model\user as m_user;
	use app\index\model\post as m_post;
	
	/*
	* 用于测试和添加数据
	*
	*/
	class testCtrl extends Controller
	{
		private $redis=null;
		
		public function __construct(){
			parent::__construct();
			
			$this->redis=ds::getRedis();
		}
		
		/*
		* 添加100个用户
		*
		*/
		public function addUser(){
			$m_user=new m_user();
			$str="1234567890qwertyuiopasdfghjlzxcvbnm";
			$user_num=100;
			
			for($k=0;$k<$user_num;$k++){
				$name_len=rand(4,8);
				$name="";
				for($i=0;$i<$name_len;$i++){
					$j=rand(0,strlen($str)-1);
					$name.=$str[$j];
				}
				
				$password=md5("123");
				
				$user_info=['name'=>$name,"password"=>$password];
				$m_user->register($user_info);
			}
			
			echo "添加用户成功! 添加了 {$user_num} 个用户";
		}
		
		/*
		* 添加1000篇文章
		*
		*
		*/
		public function addPost(){
			$r=$this->redis;
			$str="1234567890qwertyuiopasdfghjlzxcvbnm";
			$max_uid=$r->get("global:user_id");
			$all_users_name=$r->sort("new_user_list",['sort'=>"asc","get"=>"user:id:*->name"]);
			$post_num=1000;
			
			for($i=0;$i<$post_num;$i++){
				$post['title']="";
				for($j=0;$j<rand(4,10);$j++){
					$post['title'].=$str[rand(0,strlen($str)-1)];
				}
				$post["content"]="";
				for($j=0;$j<rand(10,100);$j++){
					$post['content'].=$str[rand(0,strlen($str)-1)];
				}
				$post['user_id']=rand(1,$max_uid);
				$post['user_name']=$all_users_name[$post['user_id']-1];
				$post['create_time']=time();
				
				//自增主键
				$id=$r->incr("global:post");
				$post['id']=$id;
				
				//数据入库
				$res=$r->hmset("post:id:".$id,$post);
				
				//建立user_id索引,这里是一对多所以用集合
				$r->sadd("post:user_id:".$post['user_id'],$id);
				
				//要在首页显示前50条最新文章，所以存入列表
				$r->lpush("post_list",$id);
			}
			
			
			echo "添加文章成功! 添加了 {$post_num} 篇文章";
		}
		
		/*
		* 随机关注和被粉
		*
		*/
		public function addFans(){
			$r=$this->redis;
			$max_uid=$r->get("global:user_id");
			$iter_times=ceil($max_uid/2);
			
			for($i=1;$i<=$max_uid;$i++){
				//随机抽取$iter_times次粉丝,但可能有重复,要去重
				for($j=0;$j<$iter_times;$j++){
					$fan_ids[]=rand(1,$max_uid);
				}
				$fan_ids=array_unique($fan_ids);
				
				if(($index=array_search($i,$fan_ids)) !== false){
					unset($fan_ids[$index]);
				}
				
				//批量添加这个用户的粉丝
				foreach($fan_ids as $k => $v){
					if(!$r->sismember("fans:user_id:".$i,$v)){
						$r->sadd("fans:user_id:".$i,$v);
						$r->sadd("stars:user_id:".$v,$i);
					}
					
				}
			}
			
			echo "互粉成功!";
		}
	}
?>