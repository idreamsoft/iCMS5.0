<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require(dirname(__FILE__).'/global.php');

$do	= $_GET['do'];
switch($do){
	case 'forum':
		$fid		= (int)$_GET['fid'];
		$dir		= $_GET['dir'];
		if(empty($fid)){
			$fid	= $iCMS->getCache('system/forum.dir2fid',$_GET['dir']);
		}
		if(empty($fid)){
			header("HTTP/1.1 404 Not Found");
			exit;
		}
		$F	= $iCMS->getCache('system/forum.cache',$fid);
		$iCMS->htmlConf=array('enable'=>true,'ext'=>empty($F['htmlext'])?$iCMS->config['htmlext']:$F['htmlext']);	
		$iCMS->url	= $iCMS->getCache('system/forum.url',$fid)->href."/index_";
		$iCMS->iList($fid);
	break;
	case 'show':
		$id	= (int)$_GET['id'];
		if(empty($id)){
			$id	= iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__article` WHERE `clink`='".irawurldecode($_GET['clink'])."'");
		}
		if(empty($id)){
			header("HTTP/1.1 404 Not Found");
			exit;
		}
		$iCMS->htmlConf=array('enable'=>true,'ext'=>$iCMS->config['htmlext']);
		$iCMS->Show($id,$page);
	break;
	case 'tag':
		$name	= irawurldecode($_GET['name']);
		$iCMS->htmlConf=array('enable'=>true,'ext'=>$iCMS->config['htmlext']);
		$iCMS->tag($name);
	break;
	
}