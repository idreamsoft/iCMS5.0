<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once dirname(__FILE__).'/../global.php';
require_once iPATH.'admin/admin.class.php';
require_once iPATH.'admin/function.php';
require_once iPATH.'admin/admincp.lang.php';
require_once iPATH.'include/UI.class.php';
require_once iPATH.'admin/menu.class.php';
require_once iPATH.'admin/admincp.class.php';

define('__ADMINCP__',__SELF__.'?mo');

if ($_POST['action'] =="login") {
	ckseccode($_POST['seccode']) && javascript::alert('验证码错误！');
	$username	= $_POST['username'];
	$password	= md5($_POST['password']);
}
Admin::checklogin($username,$password);
admincp_log();
Admin::MP("ADMINCP","ADMINCP_Permission_Denied");
?>