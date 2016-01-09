<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
function iCMS_tag($vars,&$iCMS){
	$whereSQL=" status='1'";
	isset($vars['sortid']) && $whereSQL.=" AND sortid='".(int)$vars['sortid']."'";
	$maxperpage =isset($vars['row'])?(int)$vars['row']:"10";
	$cacheTime =isset($vars['time'])?(int)$vars['time']:-1;
	$by=$vars['by']=='ASC'?"ASC":"DESC";
	switch ($vars['orderby']) {
		case "hot":		$orderSQL=" ORDER BY `count` $by";		break;
		case "new":		$orderSQL=" ORDER BY `id` $by";			break;
		case "order":	$orderSQL=" ORDER BY `ordernum` $by";	break;
//		case "rand":	$orderSQL=" ORDER BY rand() $by";		break;
		default:		$orderSQL=" ORDER BY `id` $by";
	}
	$offset	= 0;
	if($vars['page']){
		$total=iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__tags` WHERE {$whereSQL} {$orderSQL}");
		$iCMS->assign("total",$total);
		$pagenav= isset($vars['pagenav'])?$vars['pagenav']:"pagenav";
		$pnstyle= isset($vars['pnstyle'])?$vars['pnstyle']:0;
		$offset	= $iCMS->multi(array('total'=>$total,'perpage'=>$maxperpage,'unit'=>$iCMS->language('page:tag'),'url'=>$iCMS->url,'nowindex'=>$GLOBALS['page'],'pagenav'=>$pagenav,'pnstyle'=>$pnstyle));
	}
	$iscache=true;
	if($vars['cache']==false||isset($vars['page'])){
		$iscache=false;
		$rs = '';
	}else{
		$cacheName='tags/'.md5($whereSQL.$orderSQL);
		$rs=$iCMS->getCache($cacheName);
	}
	if(empty($rs)){
		$frs	= $iCMS->getCache('system/forum.cache');
		$rs		= iCMS_DB::getArray("SELECT * FROM `#iCMS@__tags` WHERE {$whereSQL} {$orderSQL} LIMIT {$offset},{$maxperpage}");
//echo iCMS_DB::$last_query;
//iCMS_DB::$last_query='explain '.iCMS_DB::$last_query;
//$explain=iCMS_DB::getRow(iCMS_DB::$last_query);
//var_dump($explain);
		$_count=count($rs);
		for ($i=0;$i<$_count;$i++){
			$rs[$i]['url']=$iCMS->iurl('tag',array($rs[$i],$frs[$rs[$i]['sortid']]))->href;
			$rs[$i]['link']='<a href="'.$rs[$i]['url'].'" class="tag" target="_self">'.$rs[$i]['name'].'</a> ';
			$rs[$i]['tags'].=$rs[$i]['link'];
		}
		$iscache && $iCMS->setCache($cacheName,$rs,$cacheTime);
	}
	return $rs;
}

?>