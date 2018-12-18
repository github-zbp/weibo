<?php 
    namespace app\index\controller;
    use core\lib\Controller;
    use core\lib\Datasource as ds;
	
    class indexCtrl extends Controller
    {
		protected $checkAuth=["login"=>"noAuth","doLogin"=>"noAuth","index"=>"auth"];
		
		public function __construct(){
			parent::__construct();
			
			if(isset($this->checkAuth[self::$action])){
				$checkAction=$this->checkAuth[self::$action];
				$this->$checkAction();
			}
			
		}
		
		/*
		* 验证用户是否登陆,登陆才有权限访问该访问的控制器
		*
		*/
		protected function auth(){
			if(!isset($_COOKIE['user'])){
				showMsg("Please login!");
			}
		}
		
		protected function noAuth(){
			if(isset($_COOKIE['user'])){
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
			
		}
		
		/*
		* 注册逻辑
		*
		*/
		public function doRegister(){
			
		}
		
        public function index(){    
		   $this->display("index");
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

    }
?>