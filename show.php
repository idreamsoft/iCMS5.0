<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require(dirname(__FILE__).'/global.php');
$iCMS->Show($_GET['id'],isset($_GET['p'])?(int)$_GET['p']:1);
?>