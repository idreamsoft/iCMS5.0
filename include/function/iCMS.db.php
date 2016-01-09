<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
function iCMS_DB($vars,&$iCMS){
	if(empty($vars['sql'])){
		echo $iCMS->language('SQL:empty');
		return false;
	}else{
		if ( preg_match("/^\\s*(insert|delete|update|replace) /i",$vars['sql']) ) {
			echo $iCMS->language('SQL:IDUR');
			return false;
		}
		if(strstr($vars['sql'], 'members')){
			echo $iCMS->language('SQL:members');
			return false;
		}
		if(strstr($vars['sql'], 'admin')){
			echo $iCMS->language('SQL:admin');
			return false;
		}
		$cacheTime =isset($vars['time'])?(int)$vars['time']:-1;
		$iscache=true;
		if($vars['cache']==false||isset($vars['page'])){
			$iscache=false;
			$rs = '';
		}else{
			$cacheName='DB/'.md5($vars['sql']);
			$rs=$iCMS->getCache($cacheName);
		}
		if(empty($rs)){
			$rs=iCMS_DB::getArray($vars['sql']);
			$iscache && $iCMS->SetCache($cacheName,$rs,$cacheTime);
		}
		return $rs;
	}
}

?>