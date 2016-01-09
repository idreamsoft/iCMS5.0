<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include_once iPATH.'include/plugin.class.php';
function iCMS_plugins($arguments,&$iCMS){
	add_magic_quotes($arguments);
	$plugName = $arguments['name'];
	strpos($plugName,'..')!==false && exit('Forbidden');
	$plugins	= $iCMS->getCache('system/plugins',$plugName);
	//!$plugins['status'] && $iCMS->trigger_error("'" . $plugName . "' plugins status is 0 ", E_USER_ERROR,__FILE__,__LINE__);
	if(!$plugins['status'] || !$plugins['isSetup']) return;
	$fn='iCMS_plugins_'.$plugName;
	if (!function_exists($fn)){
		!plugin::fn($plugName) && $iCMS->trigger_error("function '" . $fn . "' does not exist in iCMS plugins", E_USER_ERROR,__FILE__,__LINE__);
	}
	$iCMS->pluginName=$plugName;
	$rs=$fn($arguments,$iCMS);
	$iCMS->value($plugName,$rs);
	return $rs;
//	$iCMS->output($plugName,plugin::path($plugName,'templates/'.$plugName),'file:');
//	return call_user_func_array($fn,array($arguments,$iCMS));
}
?>