$(function(){
	var btn_dofan=$(".dofan");
	var btn_unfan=$(".unfan");
	var dofan_url="/index/index/doFans";
	var unfan_url="/index/index/unFans";
	var tab_profile=$("#profile");
	var star_post_url="/index/index/starPost";
	
	btn_dofan.click(function(){
		var uid=$(this).attr("data-uid");
		var _this=$(this);
		$.get(dofan_url,{"user_id":uid},function(data){
			
			_this.hide();
			_this.parent().find(".unfan").show();
			
		});
	});
	
	btn_unfan.click(function(){
		var uid=$(this).attr("data-uid");
		var _this=$(this);
		$.get(unfan_url,{"user_id":uid},function(data){
			_this.hide();
			_this.parent().find(".dofan").show();
			
		});
	});
	
	$("#profile").on("click",".pagination a",function(){
		//获取跳转页数
		var page=$(this).attr("page"); 
		
		getStarPagePost(page);
		
		return false;
	});
	
	$("#profile").on("click",".page-form button",function(){
		//获取跳转页数
		var page=$("#profile .page-form select").val(); 
		
		getStarPagePost(page);
		
		return false;
	});
	
	function getStarPagePost(page){
		$.get(star_post_url,{"s_page":page},function(data){
			if(!data.errno){
				var html="";
				$.each(data.data.stars_wb,function(k,v){
					html+='<div class="post col-md-12" style="padding:15px;font-size:18px;border:1px solid #ccc;border-radius:10px;margin:15px 0"><div><h3>';
					html+=v.title+"</h3>";
					html+='<p>发表人:<a class="username" href="/index/index/profile?user_id='+ v.user_id +'">'+v.user_name+'</a></p></div>';
					html+='<p>'+v.content+'</p>';
					html+='<i>'+ timeFormat(v.create_time) +' 前 发布</i></div>'
				});
				
				tab_profile.find(".tab-post").html(html);
				tab_profile.find(".page-wraper").html(data.data.s_links);
			}
		},"json");
	}
	
	function timeFormat(timestamp){
		var now = Date.parse(new Date())/1000;
		var diff=now-timestamp;
		
		if(diff >= 86400){
			return Math.ceil(diff/86400)+"天";
		}else if(diff>3600){
			return Math.ceil(diff/3600)+"小时";
		}else if(diff>60){
			return Math.ceil(diff/60)+"分钟";
		}else{
			return Math.ceil(diff)+"秒";
		}
	}
});