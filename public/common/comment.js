$(function(){
	iCMS.userInfo();
	$("#comment-login").click(function(){
		var param=login();
		if(!param)return false;
		param.action='login';
		$.post(iCMS.publicURL+"/passport.php",param,
			function(o){
				alert(o.msg);
				if(o.state=="1"){
					iCMS.userInfo();
				}
			},"json");
	});
	$("#anonymous").click(function(){
		if(this.checked){
			$('#iUserName').val(iCMS.anonymousname).attr("readonly",true).attr("disabled",true);
			$("#iPassWord").hide();
			$("label[for=iPassWord]").hide();
			$("#comment-login").hide();
		}else{
			$('#iUserName').val('').attr("readonly",false).attr("disabled",false);
			$("#iPassWord").show();
			$("label[for=iPassWord]").show();
			$("#comment-login").show();
		}
	});
//验证码
	$("#iSeccode").click(function(){
		$("#seccode-img").remove();
		$('<img />').attr('id',"seccode-img").attr('title',"看不清楚!点击图片换一张")
		.attr('src',iCMS.publicURL+"/seccode.php?"+Math.random())
		.insertAfter(this).bind("click",function(){$("#iSeccode").click();});
	}); 
//提交事件
	$("#iComment").submit(function (){
		if(iCMS.anonymous){
			comment();
		}else{
			var islogin=true;
			if(!iCMS.userInfo()){
				islogin=false;
				var param=login();
				if(!param)return false;
				param.action='login';
				$.post(iCMS.publicURL+"/passport.php",param,
					function(o){
						if(o.state=="0"){
							alert(o.msg);
							return false
						}else{
							iCMS.userInfo();
							comment();
						}
					},"json");
			}
			if(islogin) comment();
		}
	  return false;
	}); 
});
function login(){
	var p = {"username":$("#iUserName").val(),"password":$("#iPassWord").val()};
	if(p.username==""){
		alert("用户名不能为空!");
		$("#iUserName").focus();
		return false;
	}
	if(p.password==""){
		alert("密码不能为空!");
		$("#iPassWord").focus();
		return false;
	}
	return p;
}
function comment(){
	var param={"do":'save',"username":$("#iUserName").val(),"seccode":$("#iSeccode").val(),"anonymous":(typeof $("#anonymous[checked]").val()=="undefined"?0:1),
		"title":$("#comment_title").val(),"indexId":$("#comment_indexId").val(),"mId":$("#comment_mid").val(),"sortId":$("#comment_sortId").val(),
		"quote":$("#comment_quote").val(),"floor":$("#comment_floor").val(),
		"commentext":$("#comment_text").val()
	}
	if(param.commentext==""){
		alert("评论内容不能为空!");
		$("#comment_text").focus();
		return false;
	}
	if(param.seccode==""){
		$(".comment-seccode").show();
		$("#iSeccode").click().select();
		return false;
	}
	$.post(iCMS.publicURL+"/comment.php",param,
		function(o){
			$("#iSeccode").val('');
			$("#seccode-img").attr('src',iCMS.publicURL+"/seccode.php?"+Math.random());
			alert(o.msg);
			if(o.state=="1")window.location.reload();
		},"json");
}