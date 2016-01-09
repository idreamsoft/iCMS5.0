<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once(dirname(__FILE__).'/config.php');
header("Content-Type: text/xml");
$iCMS->assign('fid',(int)$_GET['id']);
$iCMS->iPrint("iTPL","rss");
