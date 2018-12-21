<?php 
	namespace app\index\model;
	use app\index\model\base;
	
	class post extends base
	{
		/*
		* post表:id user_id user_name title content create_time 6个字段
		*/
		
		
		/*
		* 文章入库
		* 
		*/
		public function add($post){
			$post['user_id']=$_COOKIE['user_id'];
			$post["user_name"]=$_COOKIE["user_name"];
			$post["create_time"]=time();
			
			//自增主键
			$id=$this->redis->incr("global:post");
			$post['id']=$id;
			
			//数据入库
			$res=$this->redis->hmset("post:id:".$id,$post);
			
			//建立user_id索引,这里是一对多所以用集合
			$this->redis->sadd("post:user_id:".$post['user_id'],$id);
			
			//要在首页显示前50条最新文章，所以存入列表
			$this->redis->lpush("post_list",$id);
			
			return $res;
		}
		
		/*
		*  获取最新的1000条微博
		*
		*/
		public function getNewPosts($total=1000,$page_num=15){
			$page=isset($_GET['page'])?$_GET['page']:1;
			
			$post_ids=$this->redis->sort("post_list",['sort'=>"desc","limit"=>[($page-1)*$page_num,$page_num]]);
			$posts=[];
			foreach($post_ids as $pid){
				$posts[]=$this->redis->hgetall("post:id:".$pid);
			}
			
			return $posts;
		}
		
		/*
		* 获取某用户的文章
		*
		*/		
		public function getPostsByUid($uid,$page_num=10){
			$page=isset($_GET['page'])?$_GET['page']:1;
			
			$post_ids=$this->redis->sort("post:user_id:".$uid,['sort'=>'desc','limit'=>[($page-1)*$page_num,$page_num]]);
			$posts=[];
			foreach($post_ids as $pid){
				$posts[]=$this->redis->hgetall("post:id:".$pid);
			}
			
			return $posts;
		}
		
		/*
		* 获取某用户的关注者的最新文章
		*
		*/
		public function getPostsFromStars($uid=0,$total=1000,$page_num=10){
			$page=isset($_GET['s_page'])?$_GET['s_page']:1;
			
			if(!$uid){
				$uid=$_COOKIE["user_id"];
			}
			
			//获取该用户所有关注者
			$stars_ids=$this->redis->smembers("stars:user_id:".$uid);
			
			$posts_ids=[];
			
			//获取所有关注者的所有文章id,限$total条
			foreach($stars_ids as $v){
				if(count($posts_ids)>=$total){
					break;
				}
				$posts_ids=array_merge($posts_ids,$this->redis->smembers("post:user_id:".$v));
			}
			rsort($posts_ids);
			
			//根据分页获取所需文章
			$pids=array_slice($posts_ids,($page-1)*$page_num,$page_num);
			
			//根据文章id获取文章
			$posts=[];
			foreach($pids as $k=>$v){
				$posts[]=$this->redis->hgetall("post:id:".$v);
			}
			
			return $posts;
		}
	}
?>