<?php
/*
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 *	================================
 *	Plugin Name: Archives/文章归档
 *	Plugin URI: http://www.idreamsoft.com
 *	Description: Archives/文章归档
 *	Version: 1.0
 *	Author: 枯木
 *	Author URI: http://G.idreamsoft.com
 *	TAG:<!--{iCMS:plugins name='archives'}-->
*/
!defined('iPATH') && exit('What are you doing?');
function iCMS_plugins_archives($vars,&$iCMS) {
    $rs     = iCMS_DB::getArray("SELECT A.pubdate FROM `#iCMS@__article` AS A,#iCMS@__forum AS F WHERE A.status='1' AND A.fid=F.fid AND F.status='1' ORDER BY pubdate DESC");
    var_dump($rs);
    $_count = count($rs);
    for ($i=0;$i<$_count;$i++) {
        $article[] = get_date($rs[$i]['pubdate'],'Y-m');
    }
    $arr = array_count_values($article);
    $i=0;
    foreach($arr as $key => $val) {
        list($y, $m) = explode('-', $key);
        $archive[$i]['url']=$y.'_'.$m;
        $archive[$i]['date']="{$y}年{$m}月";
        $archive[$i]['count']=$val;
        $i++;
    }
    $iCMS->value('archive',$archive);
    $iCMS->output('archive',plugin::tpl('archive'));
}
