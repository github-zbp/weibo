<?php 
    namespace app\index\controller;
    use core\lib\Controller;
    use core\lib\Datasource as ds;
	use app\index\model\user as m_user;
	use app\index\model\post as m_post;
	use core\lib\Mysql\Page;
	
    class indexCtrl extends Controller
    {
		protected $checkAuth=[
			"login"=>"noAuth",
			"doLogin"=>"noAuth",
			"home"=>"auth",
			"post"=>"auth",
			"profile"=>"auth"
		];
		private $redis=null;
		private $user=[];
		
		public function __construct(){
			parent::__construct();
			
			$this->redis=ds::getRedis();
			
			if(isset($this->checkAuth[self::$action])){
				$checkAction=$this->checkAuth[self::$action];
				$this->$checkAction();
			}
			
			//获取当前用户
			// var_dump($_COOKIE['user_id']);
			$id=isset($_COOKIE['user_id'])?$_COOKIE['user_id']:0;
			$this->user=$this->redis->hgetall("user:id:".$id);
			
			//如果当前有用户登陆,那么获取他的所有关注者id
			if($id){
				$star_ids=$this->redis->smembers("stars:user_id:".$id);
				$this->assign(["star_ids"=>$star_ids]);
			}
			// var_dump($star_ids);
			$this->assign(["user"=>$this->user]);
			
		}
		
		/*
		* 验证用户是否登陆,登陆才有权限访问该访问的控制器
		*
		*/
		protected function auth(){
			
			if(!isset($_COOKIE['user_id']) || !isset($_COOKIE['user_name'])){
				showMsg("Please login!","/index/index/login");
			}
			
			//获取redis中用户的盐
			$salt=$this->redis->hget("user:id:".$_COOKIE['user_id'],"salt");
			
			if(!isset($_COOKIE['user_salt']) || $_COOKIE['user_salt'] != $salt){
				showMsg("Wrong user salt");
			}
			
		}
		
		protected function noAuth(){
			if(isset($_COOKIE['user_id'])){
				showMsg("You have login already!");
			}
		}
		
		public function login(){
			$this->display("login");
		}
		
		/*
		* 登陆逻辑
		*/
		public function doLogin(){
			//实例化用户模型
			$m_user=new m_user();
			
			//执行登陆逻辑
			$m_user->login($_POST);
			
			//跳转
			redirect("/index/index/home");
		}
		
		/*
		* 注册逻辑
		*
		*/
		public function doRegister(){
			//实例化用户模型
			$m_user=new m_user();
			
			//执行注册逻辑
			if($m_user->register($_POST)){
				showMsg("Register successfully! Go to login","/index/index/login");
			}else{
				showMsg("Something wrong with register");
			}
		}
		
        public function index(){ 
		    //显示最新的50个用户
			$m_user=new m_user();
			$new_users=$m_user->getNewUsers();
			
			//显示最新的1000条微博,这1000条分页显示,每页15条
			$allRows=$this->redis->llen("post_list");
			$total=($allRows > 1000)?1000:$allRows;
			$page_num=15;
			$m_post=new m_post();
			$new_posts=$m_post->getNewPosts($total,$page_num);
			
			//获取分页信息
			$o_page=new Page($total,$page_num);
			$links=$o_page->getLinks();
			
		    $this->display("index",["users"=>$new_users,"posts"=>$new_posts,"links"=>$links]);
        }
		
		/*
		* 个人主页要求显示关注者和粉丝的数量,自己发过的微博和关注者发过的微博
		*
		*/
		public function home(){
			$num=5;
			
			//获取自己用户名
			$name=$this->user['name'];
			$id=$_COOKIE["user_id"];
			
			//获取粉丝和关注者数量
			$fans_num=$this->redis->ssize("fans:user_id:".$id);
			$stars_num=$this->redis->ssize("stars:user_id:".$id);
			
			//获取自己所有的微博(分页，倒序排)
			$m_post=new m_post();
			$my_wb=$m_post->getPostsByUid($id,$num);
			
			//获取分页信息
			$allRows=$this->redis->ssize("post:user_id:".$id);
			$o_page=new Page($allRows,$num);
			$links=$o_page->getLinks();
			
			//获取我关注的人的部分的微博(分页，倒序排)
			$star_posts_num=1000;
			$stars_wb=$m_post->getPostsFromStars($id,$star_posts_num,$num);
			$o_page=new Page($star_posts_num,$num,"s_page");
			$s_links=$o_page->getLinks();
			
			$this->display("home",['uid'=>$id,'name'=>$name,'fans_num'=>$fans_num,'stars_num'=>$stars_num,'my_wb'=>$my_wb,"links"=>$links,"stars_wb"=>$stars_wb,"s_links"=>$s_links]);
		}
		
		/*
		* 获取指定页数的关注者的文章(ajax请求)
		*
		*/
		public function starPost(){
			$num=5;
			$id=$_COOKIE["user_id"];
			
			$m_post=new m_post();
			$star_posts_num=1000;
			$stars_wb=$m_post->getPostsFromStars($id,$star_posts_num,$num);
			$o_page=new Page($star_posts_num,$num,"s_page");
			$s_links=$o_page->getLinks();
			
			$data=["errno"=>0,"errmsg"=>"","data"=>["stars_wb"=>$stars_wb,"s_links"=>$s_links]];
			
			return_result($data);
		}
		
		/*
		* 发微博
		*
		*/
		public function post(){
			//接受参数
			$post_info=$_POST;
			
			//文章入库
			$m_post=new m_post();
			$m_post->add($post_info);
			
			//跳转
			redirect("/index/index/home");
		}
		
		/*
		*  粉丝页 显示自己的所有粉丝
		*
		*/
		public function fans(){
			//获取用户主页的用户名
			$id=isset($_GET["user_id"])?$_GET["user_id"]:$_COOKIE["user_id"];
			$name=$this->redis->hget("user:id:".$id,"name");
			
			//获取粉丝和关注者数量
			$fans_num=$this->redis->ssize("fans:user_id:".$id);
			$stars_num=$this->redis->ssize("stars:user_id:".$id);
			
			//获取具体的粉丝和关注者的信息
			$fans=$this->redis->sort("fans:user_id:".$id,['sort'=>"desc","limit"=>[0,50],"get"=>["user:id:*->id","user:id:*->name"]]);
			$stars=$this->redis->sort("stars:user_id:".$id,['sort'=>"desc","get"=>["user:id:*->id","user:id:*->name"]]);
			$f=$s=[];
			foreach($fans as $k => $v){
				if($k%2){
					$f[$fans[$k-1]]=$v;
				}
			}
			foreach($stars as $k => $v){
				if($k%2){
					$s[$stars[$k-1]]=$v;
				}
			}
			
			//显示粉丝和关注者
			$this->display("fans",["name"=>$name,"id"=>$id,"fans_num"=>$fans_num,"stars_num"=>$stars_num,"f"=>$f,"s"=>$s]);
		}
		
		/*
		* 明星页 显示自己的关注者
		*
		*/
		public function stars(){
			
		}
		
		/*
		* 关注功能(异步请求)
		*
		*/
		public function doFans(){
			//接收user_id
			$star_id=$_GET["user_id"];
			
			//关注 
			$m_user=new m_user();
			$m_user->doFans($star_id);
		}
		
		/*
		* 取消关注(异步请求)
		*/
		public function unFans(){
			//接收user_id
			$star_id=$_GET["user_id"];
			
			//取消关注 
			$m_user=new m_user();
			$m_user->unFans($star_id);
		}
		
		
		/*
		* 其他用户主页
		*/
		public function profile(){
			$uid=$_GET["user_id"];
			$num=5;
			
			//获取该用户的信息
			$u_info=$this->redis->hgetall("user:id:".$uid);
			
			//获取他的粉丝和关注数量
			$fans_num=$this->redis->ssize("fans:user_id:".$uid);
			$stars_num=$this->redis->ssize("stars:user_id:".$uid);
			
			//获取该用户所有文章
			$m_post=new m_post();
			$posts=$m_post->getPostsByUid($uid,$num);
			
			//获取分页信息
			$allRows=$this->redis->ssize("post:user_id:".$uid);
			$o_page=new Page($allRows,$num);
			$links=$o_page->getLinks();
			
			//获取本用户和该用户的共同关注者和共同粉丝
			$m_user=new m_user();
			$c_fans_num=count($m_user->getCommonFans($this->user['id'],$uid));
			$c_stars_num=count($m_user->getCommonStars($this->user['id'],$uid));
			
			$this->display("profile",['u_info'=>$u_info,"fans_num"=>$fans_num,'stars_num'=>$stars_num,'posts'=>$posts,'links'=>$links,"c_fans_num"=>$c_fans_num,"c_stars_num"=>$c_stars_num]);
		}
		
		/*
		* 退出
		*
		*/
		public function logout(){
			setcookie("user_id","",-1,'/');
			setcookie("user_name","",-1,'/');
			setcookie("user_salt","",-1,'/');
			
			redirect();
		}
		
		/*
		* 测试redis是否能用
		*/
		private function testRedis(){
			$redis=ds::getRedis();
			$redis->set("name","zbp");
			$name=$redis->get("name");
			var_dump($name);
		}
		
		// public function 

    }
?>