<?php
/*
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 *	================================
 *	Plugin Name: message/留言本
 *	Plugin URI: http://www.idreamsoft.com
 *	Description: message/留言本
 *	Version: 1.0
 *	Author: 枯木
 *	Author URI: http://G.idreamsoft.com
 *	TAG:<!--{iCMS:plugins name='message'}-->
 */
!defined('iPATH') && exit('What are you doing?');
//插件信息
$_plugins['config']=array(
	'key'=>'message',
	'Name'=>'留言本',
	'Home'=>'http://www.idreamsoft.com',
	'Description'=>'留言本',
	'Version'=>'1.0',
	'Author'=>'枯木',
	'Blog'=>'http://coolmoo.idreamsoft.com',
	'TAG'=>'<!--{iCMS:plugins name="message"}-->',
	'URL'=>'1'//前台地址:留空默认为:无; 1:public/plugins.php?name=message;可自定义前台地址
);
//插件后台信息
$_plugins['admincp']=array(
	'lang'=>array(
		'menu_message'=>'留言管理'
	),
	'menu'=>array(
		'tools'=>array('menu_message'=>'plugins&name=message'),
	),
	'url'=>'plugins&name=message'
);