<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once(dirname(__FILE__).'/config.php');

switch($_GET['do']){
	case 'register':
		$iCMS->assign('forward',__REF__);
		$iCMS->iPrint("usercp/register.htm","register");
	break;
	case 'login':
		$iCMS->assign('forward',__REF__);
		$iCMS->iPrint("usercp/login.htm","login");
	break;
	case 'logout':
		set_cookie("user", '',-31536000);
		set_cookie("seccode",'',-31536000);
		set_cookie("username",'',-31536000);
	break;
	default:
		require_once iPATH.'include/UI.class.php';
		$action	= $_POST['action'];
		//$forward= $_POST['forward'];
		if($action=='register'){
			ckseccode($_POST['seccode']) && javascript::json('seccode','error:seccode');
			$username	= dhtmlspecialchars($_POST['username']);
			$email		= dhtmlspecialchars($_POST['email']);
			!preg_match("/^([\w\.-]+)@([a-zA-Z0-9-]+)(\.[a-zA-Z\.]+)$/i",$email) && javascript::json('email','register:emailerror');
			WordFilter($username) && javascript::json('username','filter:username');
			iCMS_DB::getValue("SELECT uid FROM `#iCMS@__members` where `username`='$username'") && javascript::json('username','register:usernameusr');
			$password	=md5(trim($_POST['password']));
			$pwdrepeat	=md5(trim($_POST['pwdrepeat']));
			$password!=$pwdrepeat && javascript::json('pwdrepeat','register:different');
			
		    $gender		=intval($_POST['gender']);
		    $nickname	=dhtmlspecialchars($_POST['nickname']);
		    cstrlen($nickname)>12 && javascript::json(0,'register:nicknamelong');
		    $info=array();
		    $_POST['icq'] && $info['icq']=intval($_POST['icq']);
			$_POST['home'] && $info['home']=dhtmlspecialchars(stripslashes($_POST['home']));
		    $_POST['year'] && $info['year']=intval($_POST['year']);
		    $_POST['month'] && $info['month']=intval($_POST['month']);
		    $_POST['day'] && $info['day']=intval($_POST['day']);
		    $_POST['from'] && $info['from']=dhtmlspecialchars(stripslashes($_POST['from']));
		    $_POST['signature'] && $info['signature']=dhtmlspecialchars(stripslashes($_POST['signature']));
		    $info && $info=addslashes(serialize($info));
			iCMS_DB::query("INSERT INTO `#iCMS@__members` (`groupid`,`username`,`password`,`email`,`nickname`,`gender`,`info`,`power`,`cpower`,`lastip`,`lastlogintime`,`logintimes`,`post`) VALUES ('4','$username','$password', '$email','$nickname','$gender','$info','','','".getip()."', '".time()."','0','0') ");
			//设置为登陆状态
			set_cookie('user',authcode($username.'#=iCMS!=#'.$password,'ENCODE'));
			set_cookie('username',empty($nickname)?$username:$nickname);
			javascript::json(1,'register:finish');
		}elseif($action=="login"){
			require_once iPATH."usercp/user.class.php";
			if(User::checklogin($_POST['username'],md5($_POST['password']),true)){
				javascript::json(1,'login:success');
			}else{
				javascript::json(0,'login:failed');
			}
		}
}
