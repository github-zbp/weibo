<?php 
	function showMsg($msg,$redirection=""){
		if(is_array($msg)){
			$msg_copy=$msg;
			$msg="";
			foreach($msg_copy as $k=>$v){
				$msg.=$k.":".$v." ";
			}
		}
		
		if(!$redirection && isset($_SERVER["HTTP_REFERER"])){
			$redirection=$_SERVER["HTTP_REFERER"];
		}else{
			echo "<script>alert('{$msg}');history.go(-1)</script>";
		
			die;
		}
		
		echo "<script>alert('{$msg}');location.href='{$redirection}'</script>";
		
		die;
	}
	
	/*
	* 重定向
	*/
	function redirect($url="/"){
		echo "<script>location.href='{$url}'</script>";
	}
	
	/*
	* 返回数据给ajax响应
	*/
	function return_result($msg){
		if(is_array($msg)){
			echo json_encode($msg);
			die;
		}
		
		echo $msg;
		die;
	}
	
	/*
	* 获取客户端Ip
	*
	*/
	function getClientIp()
	{
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		return $onlineip;
	}
	
	/*
	* 文件上传
	*
	*/
	function upload($destin,$file,$typearr=[])
	{var_dump($file);
		if(!empty($file))
		{
			//目的路径
			$destin=rtrim($destin,'/').'/';
			//$file=$_FILES['file1'];
			//判断上传有没有什么错误！
			$error=$file['error'];
			if($error>0)
			{
				switch($error){
					case 1:$info="上传文件的大小超出了约定值。";break;
					case 2:$info="文件大小超过了隐藏域的MAX_FILE_SIZE规定的大小！";break;
					case 3:$info="文件只有部分上传！";break;
					case 4:$info="没有任何上传文件！";break;
					case 6:$info="找不到临时文件夹！";break;
					case 7:$info="写入文件失败！";break;
					default:$info='未知的上传文件错误！';
				}
				$info='上传失败！原因：'.$info;
				return false;
			}
			//判断文件类型是否被允许！
			//$typearr=array('jpg','png','gif');
			$filetype=explode('.',$file['name'])[1];
			if(count($typearr)>0)
			{
				if(!in_array($filetype,$typearr))
				{
					$info='您的文件格式不能上传！';
					return false;
				}
			}
			//判断文件大小
			$allowsize=1000000;
			$filesize=$file['size'];
			if($allowsize>0 && $filesize>$allowsize)
			{
				$info='您的文件大小超过了规定大小';
				return false;
			}
			//判断文件是否有重名！
			//写文件时千万别忘了加后缀哦！
			$ext=strstr($file['name'],'.');
			do{
				$filename=date("YmdHis").rand(100000,999999).$ext;
			}while(file_exists($destin.$filename));
			//判断是否是上传来的文件
			if(is_uploaded_file($file['tmp_name']))
			{
				if(move_uploaded_file($file['tmp_name'],$destin.$filename))
				{
					$info=$filename;
					return $filename;
				}
				else{
					$info="文件上传时失败！";
					return false;
				}
			}else{
				$info="您的文件不是一个上传文件";
				return false;
			}
		}else{
			$info="<script>alert('兄弟你是直接进入这个页面的吧...');history.go(-1);</script>";
			return false;
		}
	}
?>