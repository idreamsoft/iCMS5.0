<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once(dirname(__FILE__).'/config.php');
empty($_GET['id']) && exit();
iCMS_DB::query("UPDATE `#iCMS@__article` SET hits=hits+1 WHERE `id` ='".(int)$_GET['id']."' LIMIT 1");
_header(urldecode($_GET['url']));
?>