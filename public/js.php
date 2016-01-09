<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once(dirname(__FILE__).'/config.php');

$action=$_GET['action'];
switch($action){
	case 'list':
		$iCMS->assign('get',$_GET);
		echo $iCMS->iPrint("iTPL","list.js");
	break;
	case 'comment':
		$iCMS->assign('commentInfo',array('indexId'=>(int)$_GET['indexId'],'mId'=>(int)$_GET['mId'],'sortId'=>(int)$_GET['sortId']));
		echo $iCMS->iPrint("iTPL","comment.js");
	break;
}
