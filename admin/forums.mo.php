<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include_once(iPATH.'include/forum.class.php');
class forums extends AdminCP {
	function doAdd(){
        Admin::MP(array("menu_index_forum_add","menu_forums_add"));
		include_once(iPATH.'include/model.class.php');
        $forum = new forum();
        if($_GET['fid']) {
            Admin::CP(intval($_GET['fid']),'Permission_Denied',__SELF__.'?mo=forums');
            $rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__forum` WHERE `fid`='".intval($_GET['fid'])."'",ARRAY_A);
            $rootid=$rs['rootid'];
        }else {
            $rootid=intval($_GET['rootid']);
            $rootid && Admin::CP($rootid,'Permission_Denied',__SELF__.'?mo=forums');
        }
        if(empty($rs)) {
            $rs=array();
            $rs['attr']		= '1';
            $rs['status']	= '1';
            $rs['isexamine']    = '1';
            $rs['issend']	= '1';
            $rs['orderNum']	= $rs['mode'] = '0';
            $rs['htmlext']	= '.html';
            $rs['forumRule']	= '{FDIR}/index{EXT}';
            $rs['contentRule']	= '{FDIR}/{YYYY}/{MM}-{DD}/{AID}{EXT}';
	        if($rootid){
	            $rootRs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__forum` WHERE `fid`='".$rootid."'",ARRAY_A);
	            $rs['htmlext']	= $rootRs['htmlext'];
	            $rs['forumRule']	= $rootRs['forumRule'];
	            $rs['contentRule']	= $rootRs['contentRule'];
	        }
        }
        include admincp::tpl("forums.add");
    }
    function doSave(){
		$fid		= (int)$_POST['fid'];
		$rootid		= (int)$_POST['rootid'];
		$modelid	= (int)$_POST['modelid'];
		$status		= (int)$_POST['status'];
		$issend		= (int)$_POST['issend'];
		$isexamine	= (int)$_POST['isexamine'];
		$orderNum	= (int)$_POST['orderNum'];
		$mode		= (int)$_POST['mode'];
		$name		= dhtmlspecialchars($_POST['name']);
		$subname	= dhtmlspecialchars($_POST['subname']);
		$domain		= dhtmlspecialchars($_POST['domain']);
		$htmlext	= dhtmlspecialchars($_POST['htmlext']);
		$url		= dhtmlspecialchars($_POST['url']);
		$password	= dhtmlspecialchars($_POST['password']);
		$pic		= dhtmlspecialchars($_POST['pic']);
		$dir		= dhtmlspecialchars($_POST['dir']);
		$title		= dhtmlspecialchars($_POST['title']);
		$keywords	= dhtmlspecialchars($_POST['keywords']);
		$description= dhtmlspecialchars($_POST['description']);
		$attr		= dhtmlspecialchars($_POST['attr']);
		$forumRule	= dhtmlspecialchars($_POST['forumRule']);
		$contentRule= dhtmlspecialchars($_POST['contentRule']);
		$indexTPL	= dhtmlspecialchars($_POST['indexTPL']);
		$listTPL	= dhtmlspecialchars($_POST['listTPL']);
		$contentTPL	= dhtmlspecialchars($_POST['contentTPL']);
       
        ($fid && $fid==$rootid) && javascript::dialog('不能以自身做为上级栏目');
        empty($name) && javascript::dialog('栏目名称不能为空!');

        if(empty($dir) && empty($url)) {
            include iPATH.'include/cn.class.php';
            $dir = strtolower(CN::pinyin($name));
        }
        
        if($mode=="2"){
        	if(strpos($forumRule,'{FDIR}')=== FALSE && strpos($forumRule,'{FID}')=== FALSE){
        		javascript::dialog('伪静态模式下版块URL规则<br />必需要有<br />{FDIR}版块目录<br />或者<br />{FID}版块ID','js:','ok',10);
        	}
        	if(strpos($contentRule,'{AID}')=== FALSE && strpos($contentRule,'{0xID}')=== FALSE && strpos($contentRule,'{LINK}')=== FALSE){
        		javascript::dialog('伪静态模式下内容URL规则<br />必需要有<br />{AID}文章ID <br />或者<br />{0xID}文章ID补零<br />或者<br />{LINK}文章自定义链接','js:','ok',10);
        	}
        	global $iCMS;
        	$htaFile=iPATH."/.htaccess";
//        	if(file_exists($htaFile)){
//        		$RewriteRule=FS::read($htaFile);
//        	}
//        	if(empty($RewriteRule)){
 			$RewriteBase=$iCMS->config['dir']=="/"?'':$iCMS->config['dir'];
			$RewriteRule="RewriteEngine On\nRewriteBase /".$RewriteBase."\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\n\n";
//        	}
        	//内容
        	$contentRR	= RewriteRule($contentRule,"show",$htmlext,$iCMS->config['htmldir']);
        	$cmd5		= md5($contentRR);
        	if(strstr($RewriteRule,$cmd5)===false){
	        	$RewriteRule.="#{$cmd5}\n".$contentRR."#{$cmd5}\n\n\n";
        	}
        	//版块
        	$forumRR	= RewriteRule($forumRule,"forum",$htmlext,$iCMS->config['htmldir']);
        	$fmd5		= md5($forumRR);
        	if(strstr($RewriteRule,$fmd5)===false){
	        	$RewriteRule.="#{$fmd5}\n".$forumRR."#{$fmd5}\n\n\n";
        	}
			FS::write($htaFile,$RewriteRule);
        }

        if(empty($fid)) {
            iCMS_DB::getValue("SELECT `dir` FROM `#iCMS@__forum` where `dir` ='$dir'") && empty($url) && javascript::dialog('该栏目别名/目录已经存在!请另选一个');
            iCMS_DB::query("INSERT INTO `#iCMS@__forum` (`rootid`,`modelid`,`orderNum`,`name`,`subname`,`password`,`title`,`keywords`,`description`,`dir`,`mode`,`domain`,`url`,`pic`,`htmlext`,`forumRule`,`contentRule`,`indexTPL`,`listTPL`,`contentTPL`,`attr`,`isexamine`,`issend`,`status`)
    		VALUES ('$rootid','$modelid', '$orderNum', '$name','$subname','$password','$title','$keywords', '$description', '$dir','$mode','$domain', '$url','$pic','$htmlext','$forumRule', '$contentRule','$indexTPL', '$listTPL', '$contentTPL', '$attr','$isexamine','$issend','$status')");
            $forum = new forum();
            $forum->cache();
            $msg="栏目添加完成!";
        }else {
            Admin::CP($fid,'Permission_Denied',__SELF__.'?mo=forums');
            $rootid!=$forum->forum[$fid]['rootid'] && Admin::CP($rootid,'Permission_Denied',__SELF__.'?mo=forums');
            iCMS_DB::getValue("SELECT `dir` FROM `#iCMS@__forum` where `dir` ='$dir' AND `fid` !='$fid'") && empty($url) &&  javascript::alert('该栏目别名/目录已经存在!请另选一个');
            iCMS_DB::query("UPDATE `#iCMS@__forum` SET `rootid` = '$rootid',`modelid` = '$modelid',`orderNum` = '$orderNum',`name` = '$name',`subname` = '$subname',`password`='$password',`title` = '$title',`keywords` = '$keywords',`description` = '$description',`dir` = '$dir',`url` = '$url',`mode` = '$mode',`domain` = '$domain',`pic`='$pic',`htmlext`='$htmlext',`forumRule`='$forumRule',`contentRule`='$contentRule',`indexTPL` = '$indexTPL',`listTPL` = '$listTPL',`contentTPL` = '$contentTPL',`attr` = '$attr',`isexamine`='$isexamine',`status`='$status',`issend`='$issend' WHERE `fid` ='$fid' ");
            $forum = new forum();
            $forum->cache();
            $msg="栏目编辑完成!";
        }
        javascript::dialog($msg,'url:'.__SELF__.'?mo=forums');
    }
    function doEdit(){
        foreach((array)$_POST['orderNum'] AS $fid=>$orderNum) {
            Admin::CP($fid) && iCMS_DB::query("UPDATE `#iCMS@__forum` SET `name` = '".$_POST['name'][$fid]."',`orderNum` = '".intval($orderNum)."' WHERE `fid` ='".intval($fid)."' LIMIT 1");
        }
        $forum =new forum();
        $forum->cache();
        javascript::dialog("栏目更新完成!",'url:0');
    }
    function doDel(){
        $fid=(int)$_GET['fid'];
        Admin::CP($fid,'Permission_Denied',__SELF__.'?mo=forums');
        $forum =new forum();
    	$msg='请选择要删除的栏目!';
        if(empty($forum->_array[$id])) {
            iCMS_DB::query("DELETE FROM `#iCMS@__forum` WHERE `fid` = '$fid'");
            $this->iCMS->iCache->delete('system/forum/'.$fid);
            $art=iCMS_DB::getArray("SELECT id FROM `#iCMS@__article` WHERE `fid` = '$fid'");
            foreach((array)$art as $a) {
                delArticle($a['fid']);
            }
            $forum = new forum();
            $forum->cache();
            $msg='删除成功!';
        }else {
        	$msg='请先删除本栏目下的子栏目!';
        }
        javascript::dialog($msg,'url:'.__SELF__.'?mo=forums');
    }
    function doMove(){
        javascript::dialog("暂无此功能!",'url:'.__SELF__.'?mo=forums');
    }
    function doDefault(){
        Admin::MP("menu_forums_manage");
        $forum = new forum();
        $do && set_cookie('selectopt',$do);
        $do	= get_cookie('selectopt');
        empty($do) && $do='fold';
        include admincp::tpl("forums.manage");
    }
}
