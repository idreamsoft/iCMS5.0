<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');

class message extends plugin{
	function dodefault(){
        global $firstcount,$pagenav;
		Admin::MP("menu_message");
        $_GET['keywords'] && $sql[]=" CONCAT(author,email,url,ip) REGEXP '{$_GET['keywords']}'";
        isset($_GET['status']) && $sql[]=" `status`='".$_GET['status']."'";
        $where =$sql ? ' where '.implode(' AND ',(array)$sql):'';
        $maxperpage =(int)$_GET['perpage']>0?$_GET['perpage']:20;
		$total=iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__plugins_message` {$where} order by id DESC");
		page($total,$maxperpage,"条留言");
		$rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__plugins_message` {$where} order by id DESC LIMIT {$firstcount},{$maxperpage}");
		$_count=count($rs);
		include plugin::acptpl();
	}
	function dodel(){
		$id=(int)$_GET['id'];
		$id && iCMS_DB::query("DELETE FROM `#iCMS@__plugins_message` WHERE `id` ='$id'");
        javascript::dialog('成功删除!','js:parent.$("#mid'.$id.'").remove();parent.iCMS.closeDialog();');
	}
	function dodelete(){
		if(isset($_POST['delete'])){
			foreach($_POST['delete'] as $k=>$id){
				iCMS_DB::query("DELETE FROM `#iCMS@__plugins_message` WHERE `id` ='$id'");
            	$js[]='#mid'.$id;
        	}
	        javascript::dialog('全部成功删除!','js:parent.$("'.implode(',', $js).'").remove();parent.iCMS.closeDialog();');
		}else{
			javascript::alert("请选择要删除的留言！");
		}
	}
	function doreply(){
	    $id =intval($_POST['id']);
		$reply=dhtmlspecialchars($_POST['replytext']);
		iCMS_DB::update('plugins_message',compact('reply'),compact('id')) && exit('1');
	}
	//前台默认页面
	function doIndex(){
		global $iCMS;
		$iCMS->output('index',plugin::tpl('message'));
	}
	//前台表单处理
	function dosave(){
		$author	= dhtmlspecialchars($_POST['author']);
		$email	= dhtmlspecialchars($_POST['email']);
		$url	= dhtmlspecialchars($_POST['url']);
		$content= dhtmlspecialchars($_POST['content']);
		
		empty($author) && exit('昵称不能为空!');
		empty($content) && exit('留言内容不能为空!');
		WordFilter($author) && exit('昵称包含被系统屏蔽的字符，请返回重新填写。');
		WordFilter($url) && exit('您的网址包含被系统屏蔽的字符，请返回重新填写。');
		WordFilter($content) && exit('留言内容包含被系统屏蔽的字符，请返回重新填写。');
		!preg_match("/^([\w\.-]+)@([a-zA-Z0-9-]+)(\.[a-zA-Z\.]+)$/i",$email) && exit('邮箱格式错误!');
		strpos($url,'http://')===false && $url='http://'.$url;
		
        iCMS_DB::query("INSERT INTO `#iCMS@__plugins_message` (`author`,`email`,`url`,`content`,`reply`,`addtime`,`ip`,`status`) VALUES ('$author','$email','$url','$content','','".time()."','".getip()."','0')");
		exit('1');
	}
}