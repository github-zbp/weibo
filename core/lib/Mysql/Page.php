<?php
	/*
	*	分页类
	*/
	namespace core\lib\Mysql;
	
	class Page
	{
		private $allRows;
		private $pageRows;
		private $currentPage;
		private $allPages;
		private $url;
		private $shownPage;
		
		public function __construct($allRows,$pageRows,$shownPage=7){
			$this->allRows=$allRows;
			$this->pageRows=$pageRows;
			$this->shownPage=$shownPage;
			$this->allPages=$this->getAllPages();
			$this->currentPage=$this->getCurrentPage();
			$this->url=$this->getUrl();
		}
		
		public function limit(){
			$start=($this->currentPage-1)*$this->pageRows;
			return [$start,$this->pageRows];
		}
		
		public function getLinks(){
			$pageInfo=$this->getPageInfo();
			$pageLinks=$this->getPageLinks();
			$pageText=$pageInfo.$pageLinks;
			
			return $pageText;
		}
		
		private function getPageInfo(){
			$str="<div class='info' style='float:left;line-height:70px;font-size:16px;margin-right:50px'>";
			$str.="<span class='tr'>共{$this->allRows}条记录</span>";
			$str.="<span class='tp'>".$this->getCurrentPage()."/{$this->allPages}页</span>";
			$str.="</div>";
			
			return $str;
		}
		
		private function getPageLinks(){
			$start=$this->currentPage-floor($this->shownPage/2);
			$end=$this->currentPage+floor($this->shownPage/2);
			
			if($start<1){
				$start=1;
				// $end=$this->shownPage;
			}
			
			if($end>$this->allPages){
				// $start=$this->allPages-$this->shownPage+1;
				$end=$this->allPages;
			}
			
			if($start ==1 && $end ==1){
				return "";
			}
			
			$str="<ul class='pagination' style='float:left'>";
			$str.=$this->getFirstLastLi(1);
			$str.=$this->getTextLi($this->currentPage-1,"上一页");
			
			for($i=$start;$i<=$end;$i++){
				$str.=$this->getNumLi($i);
			}
			
			$str.=$this->getTextLi($this->currentPage+1,"下一页");
			$str.=$this->getFirstLastLi($this->allPages);
			$str.="</ul>";
			$str.=$this->getPageForm();
			
			return $str;
		}
		
		private function getPageForm(){
			$str="<form style='float:left;font-size:16px;margin:20px 50px;width:300px'>";
			$str.="<div class='form-group'><div class='col-md-2'><select name='page' style='margin-top:3px'>";
			for($i=1;$i<=$this->allPages;$i++){
				$str.=$this->currentPage==$i?"<option value='{$i}'  selected>{$i}</option>":"<option value='{$i}'>{$i}</option>";
			}
			$str.="</select></div>";
			$str.="<div class='col-md-2'><button type='submit' class='btn btn-default btn-sm'>跳转</button></div></div>";
			$str.="</form>";
			return $str;
		}
		
		private function getFirstLastLi($page){
			$str="";
			switch($page){
				case 1:
					$str=$this->currentPage==1?"<li><a>首页</a></li>":"<li><a href='{$this->url}&page=1'>首页</a></li>";
				break;
				case $this->allPages:
					$str=$this->currentPage==$this->allPages?"<li><a>尾页</a></li>":"<li><a href='{$this->url}&page={$this->allPages}'>尾页</a></li>";
				break;
				default:return false;	
			}
			
			return $str;
		}
		
		private function getTextLi($page,$text){
			if($page<1 || $page>$this->allPages){
				$li="<li><a>{$text}</a></li>";
			}else{
				$li="<li><a href='{$this->url}&page={$page}'>{$text}</a></li>";
			}
			
			return $li;
		}
		
		private function getNumLi($page){
			if($this->currentPage==$page){
				$li="<li><a>{$page}</a></li>";
			}else{
				$li="<li><a href='{$this->url}&page={$page}'>{$page}</a></li>";
			}
			
			return $li;
		}
		
		private function getUrl(){
			$url=$_SERVER["REQUEST_URI"];
			$urlArr=parse_url($url);
			
			if(isset($urlArr["query"])){	//判断url中是否有参数
				parse_str($urlArr["query"],$query);
				
				if(array_key_exists("page",$query)){	//如果有page参数，干掉他，在拼接成完整的最终的url;没有page参数，则最终的url就是原来的url
					unset($query["page"]);
					$queryStr=http_build_query($query);
					$url=$urlArr["path"]."?".$queryStr;
				}
				
			}else{
				$url=rtrim($url,"?")."?";
			}
			
			return $url;
		}
		
		private function getAllPages(){
			return ceil($this->allRows/$this->pageRows);
		}
		
		private function getCurrentPage(){
			if(!empty($_GET["page"])){
				$page=$_GET["page"];
				
				if($page<1){
					$page=1;
				}
				
				if($page>$this->allPages){
					$page=$this->allPages;
				}
			}else{
				$page=1;
			}
			
			return $page;
		}
	}
	
	
?>