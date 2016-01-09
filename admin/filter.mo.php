<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class filter extends AdminCP {
    function doDefault() {
        Admin::MP("menu_filter");
        $cache	= $this->iCMS->getCache(array('system/word.filter','system/word.disable'));
        foreach((array)$cache['system/word.filter'] AS $k=>$val) {
            $filterArray[$k]=implode("=",(array)$val);
        }
        include admincp::tpl('filter');
    }
    function doEdit() {
        $disable=explode("\r\n",dhtmlspecialchars($_POST['disable']));
        $filter=explode("\r\n",dhtmlspecialchars($_POST['filter']));
        foreach($filter AS $k=> $val) {
            $filterArray[$k]=explode("=",$val);
        }
        $this->iCMS->setCache('system/word.filter',$filterArray,0);
        $this->iCMS->setCache('system/word.disable',$disable,0);
        javascript::dialog('更新完成!<br />3秒后返回','url:'.__SELF__.'?mo=filter');
    }
}
