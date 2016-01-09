<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');

function iCMS_forums($vars,&$iCMS){
	if(empty($vars['loop'])){
		$id=(int)$vars['fid'];
	    $_cache	= $iCMS->getCache(array('system/forum.cache','system/forum.rootid'));
	    $rs	= $_cache['system/forum.cache'][$id];
	    empty($rs) && $iCMS->error('error:page');
	    if($rs['url']) {
	        return $iCMS->go($rs['url']);
	    }
	    $iurl       = $iCMS->iurl('forum',$rs);
	    $rs['url']	= $iurl->href;
	    $rs['link']	= "<a href='{$rs['url']}'>{$rs['name']}</a>";
	    $rs['nav']	= $iCMS->shownav($rs['fid']);
	    $rs['subid']    = $_cache['system/forum.rootid'][$id];
	    $rs['subids']   = implode(',',(array)$rs['subid']);
	    return $rs;
    }
	$row		= isset($vars['row'])?(int)$vars['row']:"100";
	$cacheTime	= isset($vars['time'])?(int)$vars['time']:"-1";
	$status		= isset($vars['status'])?(int)$vars['status']:"1";
	$attr		= isset($vars['attr'])?(int)$vars['attr']:"1";
	$whereSQL=" WHERE `status`='$status' AND `attr`='$attr'";

	isset($vars['mid']) && $whereSQL.=" AND `mid` = '{$vars['mid']}'";
	isset($vars['mode']) && $whereSQL.=" AND `mode` = '{$vars['mode']}'";
	isset($vars['fid']) && !isset($vars['type']) && $whereSQL.= getSQL($vars['fid'],'fid');
	isset($vars['fid!']) && $whereSQL.= getSQL($vars['fid!'],'fid','not');
	switch ($vars['type']) {
		case "top":	
			$vars['fid'] && $whereSQL.= getSQL($vars['fid'],'fid');
			$whereSQL.=" AND rootid='0'";
		break;
		case "subtop":	
			$vars['fid'] && $whereSQL.= getSQL($vars['fid'],'fid');
		break;
		case "sub":	
			$whereSQL.= getSQL(getfids($vars['fid']),'fid');
		break;
		case "subone":	
			$whereSQL.= getSQL(getfids($vars['fid'],false),'fid');
		break;
//		case "allsub":
//			$whereSQL.= getSQL(getfids(),'fid');
//		break;
		case "self":
			$parent=$iCMS->getCache('system/forum.parent',$vars['fid']);
			$whereSQL.=" AND `rootid`='$parent'";
			//$whereSQL.=getSQL(getfids($parent,false),'rootid');
		break;
	}
	$iscache=true;
	if($vars['cache']==false){
		$iscache=false;
		$rs = '';
	}else{
		$cacheName='forum/'.md5($whereSQL);
		$rs=$iCMS->getCache($cacheName);
	}
	if(empty($rs)){
		$rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__forum`{$whereSQL} ORDER BY `orderNum`,`fid` ASC LIMIT $row");
//echo iCMS_DB::$last_query;
//iCMS_DB::$last_query='explain '.iCMS_DB::$last_query;
//$explain=iCMS_DB::getRow(iCMS_DB::$last_query);
//var_dump($explain);
		$_count=count($rs);
		for ($i=0;$i<$_count;$i++){
			$rs[$i]['url']=$iCMS->iurl('forum',$rs[$i])->href;
			$rs[$i]['link']="<a href='{$rs[$i]['url']}'>{$rs[$i]['name']}</a>";
		}
		$iscache && $iCMS->setCache($cacheName,$rs,$cacheTime);
	}
	return $rs;
}
?>