<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class defaults extends AdminCP {
    function doDefault() {
        Admin::MP("menu_defaults");
        $defArray = $this->iCMS->getCache('system/default');
        include admincp::tpl('default');
    }
    function doEdit() {
        $defArray['source']=explode("\r\n",dhtmlspecialchars($_POST['source']));
        $defArray['author']=explode("\r\n",dhtmlspecialchars($_POST['author']));
        $defArray['editor']=explode("\r\n",dhtmlspecialchars($_POST['editor']));
        $this->iCMS->setCache('system/default',$defArray,0);
        javascript::dialog('编辑完成!<br />3秒后返回','url:'.__SELF__.'?mo=defaults');
    }
}
