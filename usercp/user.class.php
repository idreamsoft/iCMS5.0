<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
class User{
	public static $uId;
	public static $Rs;
	public static $nickname;
	public static $group;
	public static $cpower;
	public static $power;
	//初始化 操作 PHP5
	function __construct(){
	}
	//检验用户
	function checkuser($a,$p,$ajax=false){
		//验证用户 账号/密码
		self::$Rs = iCMS_DB::getRow("SELECT * FROM `#iCMS@__members` WHERE `username`='{$a}' AND `password`='{$p}'");
		if(empty(self::$Rs)){
			//记录
			$a && runlog('user.login', 'username='.$a.'&password='.$_POST['password']);
			if($ajax) return false;
			self::LoginPage();
		}else{
			self::$uId=self::$Rs->uid;
			self::$Rs->info && self::$Rs->info=unserialize(self::$Rs->info);
			self::$group = iCMS_DB::getRow("SELECT * FROM `#iCMS@__group` WHERE `gid`='{self::$Rs->groupid}'");//用户组
			self::$power = explode(',',self::merge(self::$group->power,self::$Rs->power));
			$cpower		 = self::merge(self::$group->cpower,self::$Rs->cpower);
			self::$cpower= empty($cpower)?array(0):explode(',',$cpower);
			self::$nickname= empty(self::$Rs->nickname)?self::$Rs->username:self::$Rs->nickname;
			if($ajax) return true;
		}
	}
	//检查栏目权限
	function CP($p=NULL,$T="F",$url=__REF__){
		if(self::$group->gid=="1") return TRUE;
		if(is_array($p)?array_intersect($p,self::$cpower):in_array($p,self::$cpower)){
			return TRUE;
		}else{
			if($T=='F'){
				return FALSE;
			}else{
//				$this->cleancookie();
				redirect(lang($T),$url);
				exit();
			}
		};
	}
	//检查后台权限
	function MP($p=NULL,$T="Permission_Denied",$url=__REF__){
		if(is_array($p)?array_intersect($p,self::$power):in_array($p,self::$power)){
			return TRUE;
		}else{
			if($T=='F'){
				return FALSE;
			}else{
//				$this->cleancookie();
				UI::redirect(lang($T),$url);
				exit();
			}
		}
	}
	//登陆验证
	function checklogin($a,$p,$ajax=false){
		if(empty($a) && empty($p)){
			$auth		= get_cookie('user');
			list($a,$p)	= explode('#=iCMS!=#',authcode($auth,'DECODE'));
			return self::checkuser($a,$p,$ajax);
		}else{
			$crs = self::checkuser($a,$p,$ajax);
			if($ajax && !$crs) return false;
			set_cookie('user',authcode($a.'#=iCMS!=#'.$p,'ENCODE'));
			set_cookie('username',self::$nickname);
	        iCMS_DB::query("UPDATE `#iCMS@__members` SET `lastip`='".getip()."',`lastlogintime`='".time()."',`logintimes`=logintimes+1 WHERE `uid`='self::$uId'");
			if($ajax) return true;
			UI::redirect('登陆成功, 请稍候......', __SELF__);
		}
	}
	//登陆页
	function LoginPage(){
		//include iCMS_usercp_tpl('login');
	}
	//注销
	function logout($url){
		self::cleancookie();
		UI::redirect('注销成功, 请稍后......',$url);
	}
	function cleancookie(){
		set_cookie("user", '',-31536000);
		set_cookie("seccode",'',-31536000);
		set_cookie("username",'',-31536000);
	}
	function merge($G,$A){
		$G && $tmp[]=$G;
		$A && $tmp[]=$A;
		return @implode(',',$tmp);
	}
}
?>