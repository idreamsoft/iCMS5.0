/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.cn iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
**/
(function($) {
	window.iCMS = window.iCMS || {};
	iCMS.config = {
		commURI:'/action.php?do=comment&callback=?&action=',
		diggURI:'/action.php?do=digg&callback=?&action='
	}
	iCMS.digg = function (act,indexId,cid){
		if(act=='up'||act=='down'){
			var pars = {'id':indexId,'cid':cid,'ajax':'1'};
			$.getJSON(this.publicURL+this.config.commURI+act,pars, function(json){
			 	if(json.state=='1'){
			 		var obj=$("#"+act+"_"+cid);
				 	var Num=parseInt(obj.text());
				 	obj.text(Num+1); 
			 	}else if(json.state=='0'){
			 	 	alert(json.text);
			 	 }
			});
			return;
		}else if(act=='good'||act=='bad'){
			var pars = {'id':indexId};
			$.getJSON(this.publicURL+this.config.diggURI+act,pars, function(json){
			 	if(json.state=='1'){
			 		var obj=$("#"+act+"_"+indexId);
				 	var Num=parseInt(obj.text());
				 	obj.text(Num+1); 
			 	}else if(json.state=='0'){
			 	 	alert(json.text);
			 	 }
			});
			return;
		}
	}
	iCMS.quote = function (id,floor){
		var offset 		= $("#quote"+id).offset();
		var snapTop 	= offset.top+25;
		var snapLeft 	= offset.left;
//		alert(snapTop+"-"+snapLeft);

		$("#comment_quote").val(id);
		$("#comment_floor").val(parseInt(floor)+1);
		$("a[id^=unquote]").hide();
		$("a[id^=quote]").show();
		$("#quote"+id).hide();
		$("#unquote"+id).show();
		$('.comment-wrapper').addClass("comment-quote").css({"top" : snapTop, "left" : snapLeft-500});
		this.commentText('');
	}
	iCMS.unquote = function (id){
		$("#comment_quote").val(0);
		$("#comment_floor").val(0);
		$("#quote"+id).show();
		$("#unquote"+id).hide();
		$('.comment-wrapper').removeClass("comment-quote");
	}
	iCMS.reply = function (cid){
			//this.addUBB('[reply]---[i]回复[/i] ' + $("#lou_" + cid).text() + ' [' + $("#comment_username_" + cid).text() + '] 时间:[' + $("#comment_time_" + cid).text() + "]---[/reply]\r\n");
	}
	iCMS.commentText = function (text){
		$('#comment_text').val(text).focus();
	}
	iCMS.smiley = function (sid){
		var cText=$('#comment_text').val();
		$('#comment_text').val(cText+"[s:"+sid+"]").focus();
	}
	iCMS.setcookie = function (cookieName, cookieValue, seconds, path, domain, secure) {
		var expires = new Date();
		expires.setTime(expires.getTime() + seconds);
		document.cookie = escape(cookieName) + '=' + escape(cookieValue)
			+ (expires ? '; expires=' + expires.toGMTString() : '')
			+ (path ? '; path=' + path : '/')
			+ (domain ? '; domain=' + domain : '')
			+ (secure ? '; secure' : '');
	}
	iCMS.getcookie = function (name) {
		var cookie_start = document.cookie.indexOf(name);
		var cookie_end = document.cookie.indexOf(";", cookie_start);
		return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
	}
	iCMS._cookie = function (name) {
		var cookie_start = document.cookie.indexOf(name);
		var cookie_end = document.cookie.indexOf(";", cookie_start);
		return cookie_start == -1 ? '' : decodeURI(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
	}
	iCMS.userInfo = function () {
		var u = this._cookie(this.CPN+'username');
		if(u){
//			var info ='<a href="'+this.publicURL+'/passport.php?do=usercp" target="_blank">'+u+'</a> '+
			var info ='<a href="javascript:void(0);">'+u+'</a> 已登录, '+
			'<a href="javascript:void(0);" onclick="return iCMS.logout();">退出</a>';
			$("#iUserInfo").html(info).show();
			$("#iUserLogin").hide();
			return true;
		}
		return false;
	}
	iCMS.logout = function () {
		$.get(this.publicURL+"/passport.php?do=logout");
		$("#iUserInfo").empty().hide();
		$("#iUserLogin").show();
	}
})($);
$(function(){
	$("#search").submit( function() {
		var Qval=$("#search #q").val();
		if(Qval==""||Qval=="请输入关键字"){
			alert("请填写关键字");
			if(Qval=="请输入关键字")$("#search #q").val("");
			$("#search #q").focus();
			return false;
		}
	});
});
function textareasize(obj,height) {
	height=height||70;
	if(obj.scrollHeight > height) {
		obj.style.height = obj.scrollHeight + 'px';
	}
}