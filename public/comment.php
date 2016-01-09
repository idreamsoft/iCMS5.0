<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once(dirname(__FILE__).'/config.php');
if(empty($_POST['do'])){
	$iCMS->comment((int)$_GET['indexId'],(int)$_GET['mId'],(int)$_GET['sortId']);
}elseif($_POST['do']=='save'){
	require_once iPATH.'include/UI.class.php';
	$frame=$_POST['iframe']?true:false;
    ckseccode($_POST['seccode']) && javascript::json(0,'error:seccode',$frame);
//去除链接
 //   $contents	= preg_replace("/(<a[ \t\r\n]{1,}href=[\"']{0,}http:\/\/[^\/]([^>]*)>)|(<\/a>)/isU","",stripslashes($_POST['commentext']));
 //   $contents	= addslashes(dhtmlspecialchars($contents));
    $contents	= dhtmlspecialchars($_POST['commentext']);
    $title		= dhtmlspecialchars($_POST['title']);
    $username	= dhtmlspecialchars($_POST['username']);
    $indexId	= (int)$_POST['indexId'];
    $sortId		= (int)$_POST['sortId'];
    $mId		= (int)$_POST['mId'];
    $quote		= (int)$_POST['quote'];
    $reply		= (int)$_POST['reply'];
    $floor		= (int)$_POST['floor'];
    $anonymous	= (int)$_POST['anonymous'];
    empty($contents) && javascript::json(0,'comment:empty',$frame);
    WordFilter($username) && javascript::json(0,'filter:username',$frame);
    WordFilter($contents) && javascript::json(0,'filter:content',$frame);
    WordFilter($title) && javascript::json(0,'filter:title',$frame);
    empty($mId) && $mId=0;
	empty($iCMS->config['anonymousname']) && $iCMS->config['anonymousname']=$iCMS->language('guest');
	$uid	='0';
	$auth	= get_cookie('user');
    require_once iPATH."usercp/user.class.php";
//    require_once iPATH.'usercp/usercp.lang.php';
	if($auth){
		list($a,$p)	= explode('#=iCMS!=#',authcode($auth,'DECODE'));
		User::checkuser($a,$p);
    	$uid		= User::$uId;
    	$username	= ($anonymous && $iCMS->config['anonymous']) ? $iCMS->config['anonymousname']:User::$nickname;
	}else{
		$iCMS->config['anonymous'] ? $username = $iCMS->config['anonymousname'] : javascript::json(0,'login:no',$frame);
	}
    $status=$iCMS->config['isexamine']?'0':'1';
	$query=iCMS_DB::query("INSERT INTO `#iCMS@__comment` (`mid`, `sortId`, `indexId`, `userId`, `username`, `title`, `contents`, `quote`, `floor`, `reply`, `up`, `down`, `ip`, `addtime`, `status`) VALUES ('$mId', '$sortId', '$indexId', '$uid', '$username', '$title', '$contents', '$quote', '$floor', '$reply', '0', '0', '".getip()."', '".time()."', '$status')");
    if($query){
		if($status){
			if(empty($mId)){
				$__TABLE__='article';
			}else{
				$__MODEL__	= $iCMS->cache('model.id','include/syscache',0,true);
				$model		= $__MODEL__[$mId];
				$__TABLE__	= $model['table'].'_content';
			}
			iCMS_DB::query("UPDATE `#iCMS@__$__TABLE__` SET `comments` = comments+1  WHERE `id` ='$indexId'");
			javascript::json(1,'comment:post',$frame);
		}else{
			javascript::json(1,'comment:examine',$frame);
		}
	}else{
		javascript::json(1,'comment:Unknown',$frame);
	}
}
?>