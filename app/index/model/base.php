<?php 
	namespace app\index\model;
	use core\lib\Datasource as ds;
	
	class base
	{
		public $redis=null;
		
		public function __construct(){
			$this->redis=ds::getRedis();
		}
		
	}
?>