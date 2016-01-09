<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
function iCMS_link($vars,&$iCMS){
	$limit =isset($vars['row'])?(int)$vars['row']:"100";
	$cacheTime =isset($vars['time'])?(int)$vars['time']:-1;
	
	switch($vars['type']){
		case "text":$sql[]=" `logo`='' ";break;
		case "logo":$sql[]=" `logo`!='' ";break;
	}
	isset($vars['sortid']) && $sql[]=" sortid='".$vars['sortid']."'";
	$sql && $where ='WHERE '.implode(' AND ',$sql);
	$iscache=true;
	if($vars['cache']==false||isset($vars['page'])){
		$iscache=false;
		$rs = '';
	}else{
		$cacheName='links/'.md5($sql);
		$rs=$iCMS->getCache($cacheName);
	}
	if(empty($rs)){
		$rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__links`{$where} ORDER BY orderNum ASC,id DESC LIMIT 0 , $limit");
		$iscache && $iCMS->SetCache($cacheName,$rs,$cacheTime);
	}
	return $rs;
}

?>