<?php
/*
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 *	================================
 *	Plugin Name: Statistics/统计
 *	Plugin URI: http://www.idreamsoft.com
 *	Description: Statistics/统计
 *	Version: 1.0
 *	Author: 枯木
 *	Author URI: http://G.idreamsoft.com
 *	TAG:<!--{iCMS:plugins name='statistics'}-->
*/
!defined('iPATH') && exit('What are you doing?');
function iCMS_plugins_statistics($vars,&$iCMS) {
    $a     = iCMS_DB::getRow("SELECT count(*) AS c,SUM(hits) AS h FROM #iCMS@__article WHERE status='1'");
    $c     = iCMS_DB::getValue("SELECT count(*) FROM #iCMS@__comment WHERE `status`='1'");
    echo"<p>日志: <b>{$a->c}</b> 篇</p><p>评论: <b>{$c}</b> 个</p><p>访问: <b>{$a->h}</b> 次</p>";
}