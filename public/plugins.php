<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once(dirname(__FILE__).'/config.php');
$plugName	= $_GET['name'];
$plugins	= $iCMS->getCache('system/plugins',$plugName);
if(!$plugins['status'] || !$plugins['isSetup'])exit();
include iPATH.'include/plugin.class.php';
include_once plugin::path($plugName,'message.mo.php');

$method = 'do'.(empty($_POST['do'])?'index':$_POST['do']);
$plugin = new $plugName();
method_exists($plugin,$method) ? $plugin->$method() : exit('What are you doing?');
