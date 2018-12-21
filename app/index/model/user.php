<?php 
	namespace app\index\model;
	use app\index\model\base;
	
	class user extends base
	{
		/*
		* user表:id name password salt 4个字段
		*/
		
		
		/*
		* 注册
		* 
		*/
		public function register($user_info){
			$user_info["password"]=md5($user_info["password"]);
			
			//判断是否已存在该用户
			if($this->redis->get("user:name:".$user_info['name'].":id")){
				showMsg("This user has registered");
			}
			
			//主键自增
			$id=$this->redis->incr("global:user_id");
			$user_info['id']=$id;
			
			//添加数据
			$res=$this->redis->hmset("user:id:".$id,$user_info);
			$this->redis->lpush("new_user_list",$id);
			
			//添加name索引
			$this->redis->set("user:name:".$user_info['name'].":id",$id);
			
			return $res;
		}
		
		/*
		* 登陆
		*/
		public function login($user_info){
			//根据name获取用户数据
			if(($user_id = $this->redis->get("user:name:".$user_info['name'].":id")) === false){
				showMsg("This user does not exist");
			}
			$user=$this->redis->hgetall("user:id:".$user_id);
			
			//对照密码
			if(md5($user_info["password"]) != $user["password"]){
				showMsg("Wrong password");
			}
			
			//加盐
			$salt=create_salt();
			$this->redis->hset("user:id:".$user_id,"salt",$salt);
			
			//存入cookie
			$expire=time()+24*7*3600;
			setcookie("user_id",$user_id,$expire,'/');
			setcookie("user_name",$user['name'],$expire,'/');
			setcookie("user_salt",$salt,$expire,'/');
			
			return true;
		}
		
		/*
		* 获取最新用户
		*/
		public function getNewUsers($num=50){
			$user_ids=$this->redis->lrange("new_user_list",0,$num-1);
			// var_dump($user_ids);
			$users=[];
			foreach($user_ids as $uid){
				$users[]=$this->redis->hgetall("user:id:".$uid);
			}
			
			return $users;
		}
		
		/*
		* 关注功能
		*/
		public function doFans($star_id){
			//为自己的明星列表添加数据
			$this->redis->sadd("stars:user_id:".$_COOKIE["user_id"],$star_id);
			
			//为对方的粉丝列表添加数据
			$this->redis->sadd("fans:user_id:".$star_id,$_COOKIE["user_id"]);
		}
		
		/*
		* 取消关注
		*/
		public function unFans($star_id){
			$this->redis->srem("stars:user_id:".$_COOKIE["user_id"],$star_id);
			$this->redis->srem("fans:user_id:".$star_id,$_COOKIE["user_id"]);
		}
		
		/*
		* 获取共同关注者
		*
		*/
		public function getCommonStars($user1,$user2){
			return $this->redis->sinter("stars:user_id:".$user1,"stars:user_id:".$user2);
		}
		
		/*
		* 获取共同粉丝
		*
		*/
		public function getCommonFans($user1,$user2){
			return $this->redis->sinter("fans:user_id:".$user1,"fans:user_id:".$user2);
		}
	}
?>