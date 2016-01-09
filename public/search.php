<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once(dirname(__FILE__).'/config.php');
require_once iPATH.'include/UI.class.php';
$_GET['q'] && $iCMS->search(irawurldecode($_GET['q']));
